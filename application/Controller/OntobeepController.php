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
 * @file OntobeepController.php
 * @author Edison Ong
 * @since Mar 28, 2017
 * @comment 
 */
 
namespace Controller;

use Controller\Controller;

class OntobeepController extends Controller {
	public function index( $params = array() ) {
		$this->loadModel( 'Ontology' );
		$ontologies = $this->model->getAllOntology();
		
		require VIEWPATH . 'Ontobeep/index.php';
	}
	
	public function compare( $params = array() ) {
		$ontologies = $params['ontology'];
		
		if ( sizeof( $ontologies ) < 2 || sizeof( $ontologies ) > 3 ) {
			$error = new ErrorController();
			$error->index( ErrorController::INVALID_INPUT );
		} else {
			require VIEWPATH . 'Ontobeep/compare.php';
		}
	}
	
	public function statistic( $params = array() ) {
		$ontologies = preg_split( '/,/', $params['ontologies'] );
		$this->loadModel( 'Ontology' );
		
		if ( sizeof( $ontologies ) < 2 || sizeof( $ontologies ) > 3 ) {
			$error = new ErrorController();
			$error->index( ErrorController::INVALID_INPUT );
		} else {
			$typeTerms = array();
			foreach ( $ontologies as $ontAbbr ) {
				$this->model->loadOntology( $ontAbbr, $detail = false );
				$typeTerms[$ontAbbr] = array();
				foreach ( $GLOBALS['ontology']['type'] as $type => $typeIRI ) {
					$typeTerm = $this->model->getTermFromType( $typeIRI );
					foreach ( $typeTerm as $prefix => $terms ) {
						if ( !array_key_exists( $prefix, $typeTerms[$ontAbbr] ) ) {
							$typeTerms[$ontAbbr][$prefix] = array();
						}
						$typeTerms[$ontAbbr][$prefix] = array_merge( $typeTerms[$ontAbbr][$prefix], $terms );
					}
					
				}
			}
			
			require VIEWPATH . 'Ontobeep/statistic.php';
		}
	}
}
?>