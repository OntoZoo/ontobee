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
 * @file UpdateOBOOntology.php
 * @author Edison Ong
 * @since Oct 18, 2015
 * @comment 
 */
 
if ( PHP_SAPI == 'cli' ) {
	require( 'Maintenance.php' );
}


class UpdateOBOOntology extends Maintenance {
	public $url;
	public $format;
	
	public function __construct( $format = 'jsonld', $options = array() ) {
		$this->setup();
		$this->openPDOConnection();
		
		$url = $GLOBALS['obo_registry'] . '.' . $format;
		
		$headers = @get_headers( $url );
		if( $headers[0] != 'HTTP/1.1 404 Not Found' ) {
			$this->url = $url;
			$this->format = $format;
		}
		else {
			throw new Exception( 'Invalid URL. Please check OBOFoundry registry link in configuration file.' );
		}
		
		
		
	}
	
	public function doUpdate() {
		$file = file_get_contents( $this->url );
		
		switch ( $this->format ) {
			case 'jsonld':
				$data = json_decode( $file, true );
		}
		$ontAbbrs = $this->updateSQL( $data['ontologies'] );
		$this->updateRDF( $ontAbbrs );
	}
	
	public function updateSQL( $ontologies ) {
		$ontAbbrs = array();
		foreach ( $ontologies as $ontology ) {
			if ( array_key_exists( 'ontology_purl', $ontology ) ) {
				if ( array_key_exists( 'is_obsolete', $ontology ) ) {
					if ( !$ontology['is_obsolete'] ) {
						$ontAbbrs[] = $ontology['id'];
					}
				} else {
					$ontAbbrs[] = $ontology['id'];
				}
			}
			
			$params = array(
				'id' => $ontology['id'],
				'ontology_abbrv' => strtoupper( $ontology['id'] ),
				'ontology_fullname' => $ontology['title'],
				'ontology_url' => $GLOBALS['ontology']['namespace']['obo'] . strtolower( $ontology['id'] ) . '.owl',
				'ontology_graph_url' => $GLOBALS['ontology']['namespace']['obo'] . 'merged/' . strtoupper( $ontology['id'] ),
				'end_point' => $GLOBALS['endpoint']['default'],
				
				'to_list' => ( array_key_exists( 'is_obsolete', $ontology ) && $ontology['is_obsolete'] ) ? 'n' : 'y',
				'loaded' => 'n',
				'download' => array_key_exists( 'ontology_purl', $ontology ) ? $ontology['ontology_purl'] : null,
				'home' => array_key_exists( 'homepage', $ontology ) ? $ontology['homepage'] : null,
				'documentation' => array_key_exists( 'documentation', $ontology ) ? $ontology['documentation'] : null,
				'contact' => array_key_exists( 'contact', $ontology ) ? $ontology['contact'] : null,
				'description' => array_key_exists( 'description', $ontology ) ? $ontology['description'] : null,
				'help' => array_key_exists( 'mailing_list', $ontology ) ? $ontology['mailing_list'] : null,		
				//'source' => null,
				//'foundry' => 'Yes',
			);
			
			$column = array();
			$field = array();
			$update = array();
			foreach ( $params as $key => $val ) {
				if ( !is_null( $val ) ) {
					$column[] = $key;
					if ( is_array( $val ) ) {
						$value = $this->db->quote( join( '\t', $val ) );
					} else {
						$value = $this->db->quote( $val );
					}
					$field[] = $value;
					$update[] = "$key = $value";
				}
			}
			
			$sql = 'INSERT INTO ontology (' . join( ', ', $column ) . ') VALUES (' . join( ', ', $field ) . ') ON DUPLICATE KEY UPDATE ' . join( ', ', $update );
			$this->db->query( $sql );
		}
		return $ontAbbrs;
	}
	
	public function updateRDF( $ontAbbrs ) {
		foreach( $ontAbbrs as $ontAbbr ) {
			exec( 'php ' . SCRIPTPATH . "script/UpdateOntology.php $ontAbbr" );
		}
	}
}

if ( PHP_SAPI == 'cli' ) {
	if ( sizeof( $argv ) > 1 ) {
		$args = $argv;
		unset( $args[0] );
		$args = array_values( $args );
		$format = $args[0];
		unset( $args[0] );
		$args = array_values( $args );
		$options = array();
		if ( sizeof( $args ) > 0 ) {
			if ( sizeof( $args ) % 2 == 0 ) {
				for ( $i = 0; $i < sizeof( $args ) / 2; $i++ ) {
					$options[$args[$i]] = $args[$i+1];
				}
			} else {
				throw new Exception( 'Invalid arguments.' );
			}
		}
		$update = new UpdateOBOOntology( $format, $options );
	} else {
		$update = new UpdateOBOOntology();
	}
	$update->doUpdate();
	
}



?>