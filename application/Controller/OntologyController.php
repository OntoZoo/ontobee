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
 * @file OntologyController.php
 * @author Edison Ong
 * @since Sep 3, 2015
 * @comment 
 */
 
namespace Controller;

use Controller\Controller;

use Model\OntologyModel;

Class OntologyController extends Controller{	
	public function index( $params = array() ) {
		$showHTML = true;
		if (
			strpos( $_SERVER['HTTP_ACCEPT'], 'application/rdf+xml' ) === false &&
			strpos( $_SERVER['HTTP_ACCEPT'], 'application/xml' ) === false &&
			strpos( $_SERVER['HTTP_ACCEPT'], '*/*' ) === false
		) {
			$showHTML = false;
		} else if (
			strpos( $_SERVER['HTTP_USER_AGENT'], 'bot' ) ||
			strpos( $_SERVER['HTTP_USER_AGENT'], 'spider' ) ||
			strpos( $_SERVER['HTTP_USER_AGENT'], 'crawl' ) ||
			strpos( $_SERVER['HTTP_USER_AGENT'], 'search' )
		) {
			$showHTML = false;
		}
		
		list( $ontAbbr, $termIRI ) = self::parseOntologyParameter( $params );
		
		if ( !is_null( $ontAbbr ) ) {
			$title = "Ontobee: $ontAbbr";
			$this->loadModel( 'Ontology' );
			
			if ( is_null( $termIRI ) ) {
				$this->model->loadOntology( $ontAbbr );
				$ontology = $this->model->getOntology();
				if ( empty( $ontology ) ) {
					throw new Exception ( "Invalid ontology." );
				}
				$annotations = $ontology->annotation;
				$query = $this->model->getQueries();
				require VIEWPATH . 'Ontology/ontology.php';
			} else {
				$this->model->loadOntology( $ontAbbr, false );
				$ontology = $this->model->getOntology();
				if ( empty( $ontology ) ) {
					throw new Exception ( "Invalid ontology." );
				}
				$ontologyList = $this->model->getAllOntology();
				if ( in_array( $this->model->askTermType( $termIRI), array(
					'Class',
					'ObjectProperty',
					'DatatypeProperty',
					'AnnotationProperty'
				) ) ) {
					$this->model->loadClass( $termIRI );
					$term = $this->model->getClass();
					$annotations = $term->annotation;
					$query = $this->model->getQueries();
					require VIEWPATH . 'Ontology/class.php';
				}
					
			}
		} else {
			throw new Exception( "Ontology is not specified." );
		}
	}
	
	public function term( $params = array() ) {		
		list( $ontAbbr, $typeIRI ) = self::parseOntologyParameter( $params );
		
		if ( !is_null( $ontAbbr ) && !is_null( $ontAbbr ) ) {
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
			
			$title = "Ontobee: $ontAbbr";
			$this->loadModel( 'Ontology' );
			$this->model->loadOntology( $ontAbbr, false );
			$ontology = $this->model->getOntology();
			if ( !empty( $ontology ) ) {
				list( $terms, $letters, $page, $pageCount ) = $this->model->getTermList( $typeIRI, $letter, $page );
				require VIEWPATH . 'Ontology/term.php';
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
		} else {
			$termIRI = array_shift( $params );
		}
		
		return array( $ontAbbr, $termIRI );
	}
}



?>