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

if ( !defined( 'MAINCLASS' ) ) {
	DEFINE( 'MAINCLASS', __FILE__ );
}

require_once( 'Maintenance.php' );
require_once( 'UpdateOntology.php' );

class UpdateOBOOntology extends Maintenance {
	public $url;
	public $format;
	public $log;
	
	private $updateList;
	private $updateOntology;
	
	public function __construct() {
		parent::__construct();
		
		$this->addOption( 'ontologies', 'Ontologies to be updated', 'o', false );
		$this->addOption( 'format', 'OBO Foundry meta-data format', 'f', false );
		$this->addOption( 'exclude', 'Ontologies to be excluded', 'x', false );
				
		$this->connectDB();
	}
	
	protected function setup() {
		$this->logger->info( 'Setting up OBO Foundry update process' );
		
		if ( $this->hasOption( 'ontologies' ) ) {
			$tokens = preg_split( '/[|,]/', $this->getOption( 'ontologies' ) );
			$this->updateList = $tokens;
		} else {
			$this->updateList = array();
		}
		
		if ( $this->hasOption( 'format' ) ) {
			$format = $options['format'];
		} else {
			$format = 'jsonld';
		}
		
		if ( $this->hasOption( 'exclude' ) ) {
                        $tokens = preg_split( '/[|,]/', $this->getOption( 'exclude' ) );
                        $this->excludeList = $tokens;
                } else {
                        $this->excludeList = array();
                }
		
		$url = $GLOBALS['obo_registry'] . '.' . $format;
		
		$this->logger->info( 'Checking OBO Registry connection' );
		
		$headers = @get_headers( $url );
		if( $headers[0] != 'HTTP/1.1 404 Not Found' ) {
			$this->url = $url;
			$this->format = $format;
			$this->logger->info( 'Connection success' );
		}
		else {
			$this->logger->error( 'Connection Fail' );
			$msg = 'Invalid URL. Please check OBOFoundry registry link in configuration file.';
			throw new Exception( $msg );
			$this->logger->info( $msg );
		}
		
		$this->updateOntology = new UpdateOntology();
		
		$this->logger->info( 'Setup complete' );
	}
	
	public function execute() {
		$this->setup();
		$this->update();
	}
	
	public function update() {
		$this->logger->info( 'Starting OBO Foundry update process' );
		
		$this->logger->debug( 'Parsing registry data' );
		
		$file = file_get_contents( $this->url );
		switch ( $this->format ) {
			case 'jsonld':
				$data = json_decode( $file, true );
		}
		$ontologies = $data['ontologies'];
		$this->logger->debug( 'Parsing complete' );
		
		foreach ( $ontologies as $ontology ) {
			$loadRDF = false;
			if ( !empty( $this->updateList ) & !in_array( $ontology['id'], $this->updateList ) ) {
				continue;
			}
			if ( in_array( $ontology['id'], $this->excludeList ) ) {
				$this->logger->info( "Excluded {$ontology['id']}" );
				continue;
			}
			
			$this->logger->info( "Processing {$ontology['id']}" );
			
			if ( array_key_exists( 'ontology_purl', $ontology ) ) {
				if ( array_key_exists( 'is_obsolete', $ontology ) ) {
					if ( !$ontology['is_obsolete'] ) {
						$loadRDF = true;
					}
				} else {
					$loadRDF = true;
				}
			} else {
				$this->logger->info( "{$ontology['id']} is obsolete" );
				continue;
			}
			
			if ( array_key_exists( 'in_foundry_order', $ontology ) && intval( $ontology['in_foundry_order'] ) == 1 ) {
				$this->logger->info( "{$ontology['id']} is OBO Foundry ontology" );
				$foundry = 'Foundry';
			} else {
				$this->logger->info( "{$ontology['id']} is OBO Library ontology" );
				$foundry = 'Library';
			}
			
			$this->logger->info( 'Parsing meta-data' );
			$params = array(
				'id' => $ontology['id'],
				'ontology_abbrv' => strtoupper( $ontology['id'] ),
				'ontology_fullname' => $ontology['title'],
				'ontology_url' => $GLOBALS['ontology']['namespace']['obo'] . strtolower( $ontology['id'] ) . '.owl',
				'ontology_graph_url' => $GLOBALS['ontology']['namespace']['obo'] . 'merged/' . strtoupper( $ontology['id'] ),
				'end_point' => $GLOBALS['endpoint']['default'],
				
				'to_list' => ( array_key_exists( 'is_obsolete', $ontology ) && $ontology['is_obsolete'] ) ? 'n' : 'y',
				'download' => array_key_exists( 'ontology_purl', $ontology ) ? $ontology['ontology_purl'] : null,
				'home' => array_key_exists( 'homepage', $ontology ) ? $ontology['homepage'] : null,
				'documentation' => array_key_exists( 'documentation', $ontology ) ? $ontology['documentation'] : null,
				'contact' => array_key_exists( 'contact', $ontology ) ? $ontology['contact'] : null,
				'description' => array_key_exists( 'description', $ontology ) ? $ontology['description'] : null,
				'help' => array_key_exists( 'mailing_list', $ontology ) ? $ontology['mailing_list'] : null,		
				'source' => $this->getFinalURL( $ontology['ontology_purl'] ),
				'foundry' => $foundry,
				'domain' => array_key_exists( 'domain', $ontology ) ? $ontology['domain'] : null,
				
				'tracker' => array_key_exists( 'tracker', $ontology ) ? $ontology['tracker'] : null,
				'facebook' => array_key_exists( 'facebook', $ontology ) ? $ontology['facebook'] : null,
				'twitter' => array_key_exists( 'twitter', $ontology ) ? $ontology['twitter'] : null,
				'depicted_by' => array_key_exists( 'depicted_by', $ontology ) ? $ontology['depicted_by'] : null,
			);
			$params['license'] = null;
			if ( array_key_exists( 'license', $ontology ) ) {
				$params['license'] = array(
						array_key_exists( 'label', $ontology['license'] ) ? $ontology['license']['label'] : '',
						array_key_exists( 'logo', $ontology['license'] ) ? $ontology['license']['logo'] : '',
						array_key_exists( 'url', $ontology['license'] ) ? $ontology['license']['url'] : '',
				);
			}
			$params['publication'] = null;
			if ( array_key_exists( 'publications', $ontology ) ) {
				$params['publication'] = array();
				foreach ( $ontology['publications'] as $publication ) {
					if ( array_key_exists( 'id', $publication ) ) {
						$params['publication'][] = $publication['id'];
					}
				}
			}
			$this->logger->info( 'Parsing complete' );
			
			$this->logger->info( 'Updating MySQL table' );
			$column = array();
			$field = array();
			$update = array();
			foreach ( $params as $key => $val ) {
				if ( !is_null( $val ) ) {
					$column[] = $key;
					if ( is_array( $val ) ) {
						$value = $this->db->quote( join( '|', $val ) );
					} else {
						$value = $this->db->quote( $val );
					}
					$field[] = $value;
					if ( $key == 'ontology_abbrv' ) {
						continue;
					}
					$update[] = "$key = $value";
				}
			}
			$sql = 'INSERT INTO ontology (' . join( ', ', $column ) . ') VALUES (' . join( ', ', $field ) . ') ON DUPLICATE KEY UPDATE ' . join( ', ', $update );
			$this->db->query( $sql );
			$this->logger->info( 'MySQL update complete' );
			
			if ( $loadRDF ) {
				$this->updateOntology->setOntID( $ontology['id'] );
				$this->updateOntology->execute();
			}
		}
	}
}

if ( MAINCLASS == __FILE__ ) {
	$update = new UpdateOBOOntology();
	$update->loadParameter();
	$update->execute();
}


?>
