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
 * @file AnnotatorController.php
 * @author Edison Ong
 * @since Jul 9, 2018
 * @comment 
 */
 
namespace Controller;

use Controller\Controller;

use Model\OntologyModel;
use Stringy\Stringy;

class AnnotateController extends Controller {
	public function index( $params = array() ) {
		$this->loadModel( 'Ontology' );
		$ontologies = $this->model->getAllOntology();
		require VIEWPATH . 'Annotator/index.php';
	}
	
	public function submit( $params = array() ) {
		$GLOBALS['show_query'] = false;
		
		$this->loadModel( 'Ontology' );
		
		if ( array_key_exists( 'querytext' , $params ) ) {
			$text = $params['querytext'];
			$text = preg_replace( '/\t/', ' ', $text );
			$texts = explode( PHP_EOL, $text );
		}
		
		$ontologies = $params['ontology'];
		if ( $ontologies != '' ) {
			$ontologies = explode( ',', $ontologies );
		} else {
			$ontologies = array();
			foreach( $this->model->getAllOntology() as $ontology ) {
				if ( $ontology->mgrep_ready == 'y' ) {
					$ontologies[] = $ontology->ontology_abbrv;
				}
			}
		}
		$caseFile = MGREPPATH . 'CaseFolding.txt';
		
		$results = array();
		foreach( $texts as $text ) {
			$inputFile = tmpfile();
			fwrite( $inputFile, $text );
			$inputMeta = stream_get_meta_data( $inputFile );
			$inputName = $inputMeta['uri'];
			
			foreach( $ontologies as $ontology ) {
				$mapFile = MGREPPATH . 'mapping' . DIRECTORY_SEPARATOR . strtolower( $ontology ) . '.mapping';
				$map = json_decode( file_get_contents( $mapFile ) );
				
				$dictFile = MGREPPATH . 'dictionary' . DIRECTORY_SEPARATOR . strtolower( $ontology ) . '.dict';
				$outputFile = tmpfile();
				fwrite( $outputFile, $text );
				$outputMeta = stream_get_meta_data( $outputFile );
				$outputName = $outputMeta['uri'];
				
				exec( MGREPPATH . 'mgrep -m batch-mapping -w NonAlphanumeric -c ' . $caseFile . ' -d ' . $dictFile . 
						' < ' . $inputName . ' > ' . $outputName );
				$output = file_get_contents( $outputName );
				fclose( $outputFile );
				
				foreach( explode( PHP_EOL, $output ) as $line ) {
					$result = array();
					$tokens = preg_split( '/\t/', $line );
					if ( sizeof( $tokens ) > 1 ) {
						$termIRI = $map[$tokens[0]];
						$match = mb_substr( $text, $tokens[1]-1, $tokens[2] - $tokens[1]+1 );
						// Simple fix to address incorrect abbreviation matching, such as All, all, An, an etc.
						if ( in_array( strtolower( $match ), array( "all", "at", "of", "to", "was", "not", "in", "for", "a", "an", "is", "are", "am" ) ) ) {
							if ( strtolower( $match ) == $match || ucfirst( strtolower( $match ) ) == $match ) {
								continue;
							}
						} else if ( preg_match( '/^[\d]+$/', $match ) ) {
							continue;
						}
						if ( !array_key_exists( $termIRI, $results ) ) {
							$results[$termIRI] = array();
						}
						if ( !array_key_exists( $match, $results[$termIRI] ) ) {
							$results[$termIRI][$match] = array();
						}
						$result['dictID'] = $tokens[0];
						$result['iri'] = $termIRI;
						$result['ontology'] = $ontology;
						$result['start'] = $tokens[1];
						$result['end'] = $tokens[2];
						$result['length'] = $tokens[2] - $tokens[1];
						$results[$termIRI][$match][] = $result;
					}
				}
			}
			
			fclose( $inputFile );
		}
		
		require VIEWPATH . 'Annotator/result.php';
	}
}

?>