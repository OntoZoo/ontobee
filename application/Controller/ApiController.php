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
 * @file ApiController.php
 * @author Edison Ong
 * @since Oct 23, 2015
 * @comment 
 */

namespace Controller;

use Exception;
use Helper;

use Controller\Controller;

use Model\OntologyModel;

class ApiController extends Controller  {
	public function search( $params = array() ) {
		$GLOBALS['show_query'] = false;
		error_reporting(0);
		
		$this->loadModel( 'Ontology' );

		list( $ontAbbr, $termIRI ) = $this->parseOntologyParameter( $params );

		$keyword = null;
		if ( array_key_exists( 'term' , $params ) ) {
			$keyword = $params['term'];
		} else if ( array_key_exists( 'keyword' , $params ) ) {
			$keyword = $params['keyword'];
		} else if ( array_key_exists( 'keywords' , $params ) ) {
			$keyword = $params['keywords'];
		} else if ( !empty ( $params ) ) {
			$keyword = array_shift( $params );
		}
		if ( !is_null( $keyword ) ) {
			$json = $this->model->searchKeyword( $keyword, $ontAbbr, 50 );
		} else {
			throw new Exception( "Excess parameters." );
		}
		$resultQueue = array();
		foreach ( $json as $index => $result ) {
			$resultString = join( '-', $result );
			if ( in_array( $resultString, $resultQueue ) ) {
				unset( $json[$index] );
			} else {
				$resultQueue[] = $resultString;
			}
		}
		echo json_encode( $json );
	}
	
	public function infobox( $params = array() ) {
		$GLOBALS['show_query'] = false;
		list( $ontAbbr, $termIRI ) = $this->parseOntologyParameter( $params );
		$this->loadModel( 'Ontology' );
		$this->model->loadOntology( $ontAbbr, $termIRI, null, false );
		echo json_encode( $this->model->describeTerm( $termIRI ) );
	}
	
	public function ontobeep( $params = array() ) {
		$site = SITEURL;
		if ( $params['method'] == 'getChildren' ) {
			$ontologies = array_flip( preg_split( '/,/', $params['ontologies'] ) );
			$termIRI = $params['termIRI'];
			$this->loadModel( 'Ontology' );
			
			$json = array();
			$json['label'] = 'name';
			$json['id'] = 'topLevelId';
			$json['items'] = array();
			
			if ( $termIRI == '' ) {
				foreach( $ontologies as $ontAbbr => $index ) {
					foreach( $this->model->getOntologyKeyTerm( $ontAbbr) as $keyTerm ) {
						if ( $keyTerm->is_root == '1' ) {
							$keyTerms[$keyTerm->term_url][$keyTerm->ontology_abbrv] = $keyTerm->term_label;
						}
					}
				}
				
				foreach( $keyTerms as $keyTermIRI => $keyTerm ) {
					$id = Helper::getShortTerm( $keyTermIRI );
					$label = $id;
					foreach( $keyTerm as $keyTermOnt => $keyTermLabel ) {
						if ( strpos( $label, "$keyTermLabel" ) !== false ) {
							$label .= 
<<<END
 (<span style="font-weight:bold; color:{$GLOBALS['ontobeep_colorkey'][$ontologies[$keyTermOnt]]}; cursor:pointer" onClick="window.open('{$site}ontology/$keyTermOnt?iri={$GLOBALS['call_function']( Helper::encodeURL( $keyTermIRI ) )}')">$keyTermOnt</span>)
END;
						} else {
							$label .=
<<<END
 | $keyTermLabel (<span style="font-weight:bold; color:{$GLOBALS['ontobeep_colorkey'][$ontologies[$keyTermOnt]]}; cursor:pointer" onClick="window.open('{$site}ontology/$keyTermOnt?iri={$GLOBALS['call_function']( Helper::encodeURL( $keyTermIRI ) )}')">$keyTermOnt</span>)
END;
						}
					}
					
					$item = array();
					$item['id'] = $id;
					$item['name'] = $label;
					$item['type'] = 'item';
					$item['term_url'] = $keyTermIRI;
					
					foreach( $ontologies as $ontAbbr => $index ) {
						$this->model->loadOntology( $ontAbbr, $detail = false );
						$subClasses = $this->model->getTermSubClass( $keyTermIRI );
						if ( !empty( $subClasses ) ) {
							$item['children'] = array();
							break;
						}
					}
					
					$json['items'][] = $item;
				}
			} else {
				$subTerms = array();
				$subTermsHasChild = array();
				foreach( $ontologies as $ontAbbr => $index ) {
					$this->model->loadOntology( $ontAbbr, $detail = false );
					$subClasses = $this->model->getTermSubClass( $termIRI );
					foreach( $subClasses as $subClass ) {
						$subTerms[$subClass['term']][$ontAbbr] = isset( $subClass['label'] ) ? $subClass['label'] : '';
						if ( isset( $subClass['subTerm'] ) ) {
							$subTermsHasChild[$subClass['term']] = true;
						}
					}
				}
				
				foreach( $subTerms as $subTermIRI => $subTerm ) {
					$id = Helper::getShortTerm( $subTermIRI );
					$label = $id;
					foreach( $subTerm as $subTermOnt => $subTermLabel ) {
						if ( strpos( $label, "$subTermLabel" ) !== false ) {
							$label .= 
<<<END
 (<span style="font-weight:bold; color:{$GLOBALS['ontobeep_colorkey'][$ontologies[$subTermOnt]]}; cursor:pointer" onClick="window.open('{$site}ontology/$subTermOnt?iri={$GLOBALS['call_function']( Helper::encodeURL( $subTermIRI ) )}')">$subTermOnt</span>)
END;
						} else {
							$label .=
<<<END
 | $subTermLabel (<span style="font-weight:bold; color:{$GLOBALS['ontobeep_colorkey'][$ontologies[$subTermOnt]]}; cursor:pointer" onClick="window.open('{$site}ontology/$subTermOnt?iri={$GLOBALS['call_function']( Helper::encodeURL( $subTermIRI ) )}')">$subTermOnt</span>)
END;
						}
					}
					
					$item = array();
					$item['id'] = $id;
					$item['name'] = $label;
					$item['type'] = 'item';
					$item['term_url'] = $subTermIRI;
					if ( isset( $subTermsHasChild[$subTermIRI] ) ) {
						$item['children'] = array();
					}
					$json['items'][] = $item;
				}
			}
			
			echo json_encode( $json );
		}
	}
}


?>
