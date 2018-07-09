<?php

/**
 * Copyright © 2015 The Regents of the University of Michigan
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 * http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and limitations under the License.
 * 
 * For more information, questions, or permission requests, please contact:
 * Yongqun “Oliver” He - yongqunh@med.umich.edu
 * Unit for Laboratory Animal Medicine, Center for Computational Medicine & Bioinformatics
 * University of Michigan, Ann Arbor, MI 48109, USA
 * He Group:  http://www.hegroup.org
 */

/**
 * @file MakeMgrepDict.php
 * @author Edison Ong
 * @since Jul 9, 2018
 * @comment 
 */
 
if ( !defined( 'MAINCLASS' ) ) {
	DEFINE( 'MAINCLASS', __FILE__ );
}

require_once( 'Maintenance.php' );

use RDFStore\SPARQLQuery;
use RDFStore\RDFQueryHelper;

DEFINE( 'MGREPPATH', SCRIPTPATH . 'annotator' . DIRECTORY_SEPARATOR );
DEFINE( 'ENVIRONMENT', 'production' );

class MakeMgrepDict extends Maintenance {
	
	public function __construct() {
		parent::__construct();
	
		$this->addArg( 'ontID', 'Ontology ID to be updated' );
	
		$this->connectDB();
		
		$this->search = $GLOBALS['search'];
	}

	public function setOntID( $ontID ) {
		$this->ontID = $ontID;
	}
	
	public function execute() {
		$this->setup();
		$this->make();
	}
	
	protected function setup() {
		$this->logger->info( "Setting up update process" );
	
		if ( !isset( $this->ontID ) ) {
			$this->ontID = $this->getArgByName( 'ontID' );
		}
		
		$this->dictDir = MGREPPATH . "dictionary" . DIRECTORY_SEPARATOR;
		if ( !file_exists( $this->dictDir ) ) {
			mkdir( $this->dictDir );
		}
		$this->mapDir = MGREPPATH . "mapping" . DIRECTORY_SEPARATOR;
		if ( !file_exists( $this->mapDir ) ) {
			mkdir( $this->mapDir );
		}
		
		$this->logger->debug( "Querying $this->ontID from MySQL ontology table" );
		$sql = "SELECT * FROM ontology WHERE id = '$this->ontID'";
		$query = $this->db->prepare( $sql );
		$query->execute();
		$this->ontology = $query->fetch();
		$this->fileName = $this->ontology->ontology_abbrv;
		$this->logger->debug( 'Complete' );
		
		$this->logger->info( 'Setup complete' );
	}
	
	private function make() {
		$this->query();
	}
	
	private function query() {
		$this->logger->debug( 'Starting query labels and synonyms for all classes' );
		$propertiesQuery = '<' . join( '>,<', $this->search['property'] ) . '>';
		
		$query =
		<<<END
SELECT ?s ?o FROM <{$this->ontology->ontology_graph_url}> WHERE {
	?s ?p ?o .
	?s rdf:type ?t .
	FILTER ( ?p in ( $propertiesQuery ) ) .
	FILTER ( isIRI( ?s ) ) .
	FILTER ( ?t = <{$GLOBALS['ontology']['type']['Class']}> )
}
END;
		
		$json = SPARQLQuery::queue( $this->ontology->end_point, $query );
		
		$results = RDFQueryHelper::parseSPARQLResult( $json );
		
		$this->logger->debug( 'Generating map file' );
		$map = array();
		foreach( $results as $index => $value ) {
			if ( !in_array( $value['s'], $map ) ) {
				$map[] = $value['s'];
			}
		}
		file_put_contents( "$this->mapDir$this->ontID.mapping", json_encode( $map ) );
		
		$this->logger->debug( 'Generating dictionary file' );
		$output = "";
		foreach( $results as $value ) {
			$index = array_search( $value['s'], $map );
			$output = $output . $index . "\t" . $value['o'] . "\n";
		}
		file_put_contents( "$this->dictDir$this->ontID.dict", $output );
		
		$this->logger->debug( 'Setting MySQL ontology table mgrep_ready to y' );
		$sql = "UPDATE ontology SET mgrep_ready='y' WHERE id = '{$this->ontology->id}'";
		$this->db->query( $sql );
		
		$this->logger->info( 'Complete' );
	}
	
}

if ( MAINCLASS == __FILE__ ) {
	$update = new MakeMgrepDict();
	$update->loadParameter();
	$update->execute();
}

?>