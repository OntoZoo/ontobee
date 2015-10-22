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
 * @file StatisticController.php
 * @author Edison Ong
 * @author Bin Zhao
 * @since Sep 8, 2015
 * @comment 
 */
 
namespace Controller;

use Controller\Controller;

class OntostatController extends Controller {
	public function index( $params = array() ) {
		$GLOBALS['show_query'] = false;
		
		$ontAbbr = null;
		if ( array_key_exists( 'ontology' , $params ) ) {
			$ontAbbr = $params['ontology'];
		} else if ( array_key_exists( 'o', $params ) ) {
			$ontAbbr = $params['o'];
		} else {
			$ontAbbr = array_shift( $params );
		}
		
		$this->loadModel( 'Ontology' );
		
		if ( is_null( $ontAbbr ) ) {
			$ontologies = $this->model->getAllOntology();
			$stats = array();
			$termIRI = null;
			foreach ( $ontologies as $ontology ) {
				$this->model->loadOntology( $ontology->ontology_abbrv, $termIRI, $ontology->end_point, false );
				$stats[$ontology->ontology_graph_url] = $this->model->countAllOntologyType();
			}
			require VIEWPATH . 'Ontostat/index.php';
		} else {
			$this->model->loadOntology( $ontAbbr, null, false );
			$ontology = $this->model->getOntology();
			$stats = $this->model->countOntologyType();
			require VIEWPATH . 'Ontostat/ontology.php';
		}
		
	}
	
	public function catalog( $params = array() ) {
		list( $ontAbbr, $termIRI ) = self::parseOntologyParameter( $params );
		
		if ( !is_null( $ontAbbr ) ) {
			if ( array_key_exists( 'prefix', $params ) ) {
				$prefix = strtoupper( $params['prefix'] );
			} else {
				$prefix = null;
			}
			
			if ( array_key_exists( 'letter', $params ) ) {
				$letter = strtoupper( $params['letter'] );
			} else if ( array_key_exists( 'l', $params ) ) {
				$letter = strtoupper( $params['l'] );
			} else {
				$letter = '*';
			}
			
			if ( array_key_exists( 'page', $params ) ) {
				$page = $params['page'];
			} else if ( array_key_exists( 'p', $params ) ) {
				$page = $params['p'];
			} else {
				$page = 1;
			}
			
			if ( array_key_exists( 'max', $params ) ) {
				$listMaxTerms = $params['max'];
			} else if ( array_key_exists( 'm', $params ) ) {
				$listMaxTerms = $params['m'];
			} else {
				$listMaxTerms = $GLOBALS['ontology']['term_max_per_page'][0];
			}
			
			$title = "Ontobee: $ontAbbr";
			$this->loadModel( 'Ontology' );
			$this->model->loadOntology( $ontAbbr, null, false );
			$ontology = $this->model->getOntology();
			if ( !empty( $ontology ) ) {
				list( $terms, $letters, $page, $pageCount ) = $this->model->getTermList( $termIRI, $prefix, $letter, $page, $listMaxTerms );
				require VIEWPATH . 'Ontology/catalog.php';
			} else {
				throw new Exception ( "Invalid ontology." );
			}
		} else {
			throw new Exception( "Invalid parameters." );
		}
	}
	
	private static function parseOntologyParameter( $params ) {
		$ontAbbr = null;
		$termIRI = null;
	
		if ( array_key_exists( 'ontology' , $params ) ) {
			$ontAbbr = $params['ontology'];
		} else if ( array_key_exists( 'o', $params ) ) {
			$ontAbbr = $params['o'];
		} else {
			$ontAbbr = array_shift( $params );
		}
			
		if ( array_key_exists( 'iri' , $params ) ) {
			$termIRI = $params['iri'];
		} else if ( array_key_exists( 'i', $params ) ) {
			$termIRI = $params['i'];
		}
	
		return array( $ontAbbr, $termIRI );
	}
}

?>