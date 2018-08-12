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
 * @file SearchController.php
 * @author Zuoshuang Allen Xiang
 * @author Edison Ong
 * @since Sep 4, 2015
 * @comment 
 */

namespace Controller;

use Exception;

use Controller\Controller;

use Model\OntologyModel;

class SearchController extends Controller  {
	public function index( $params = array() ) {
		$GLOBALS['show_query'] = false;
		
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
		$keyword = trim( $keyword );
		
		if ( array_key_exists( 'submit', $params ) ) {
			$submit = true;
			unset( $params['submit'] );
		}
		
		if ( !is_null( $keyword ) ) {
			if ( !is_null( $ontAbbr ) && $ontAbbr != '' ) {
				$this->model->loadOntology( $ontAbbr, $keyword, null, false );
				$keyOntology = $this->model->getOntology();
				if ( empty( $ontology ) ) {
					$ontologies = $this->model->getAllOntology();
				}
			} else {
				$ontologies = $this->model->getAllOntology();
			}
			
			if ( !isset( $submit ) ) {
				$json = $this->model->searchKeyword( $keyword, $ontAbbr, 50 );
			} else {
				$json = $this->model->searchKeyword( $keyword, $ontAbbr );
				
			}
			
			#$this->writeExcel( $json );
			
		}	else {
			throw new Exception( "Excess parameters." );
		}
		
		require VIEWPATH . 'Search/search.php';
	}
	
	public function batchsearch( $params = array() ) {
		$GLOBALS['show_query'] = false;
		
		$this->loadModel( 'Ontology' );
		
		$keywords = array();
		if ( array_key_exists( 'batchkeywords' , $params ) ) {
			$tokens = explode( PHP_EOL, $params['batchkeywords'] );
			foreach ( $tokens as $token ) {
				if ( $token != '' ) {
					$keywords[] = trim( $token );
				}
			}
		}
		
		$batchontologies = $params['ontology'];
		if ( $batchontologies != '' ) {
			$batchontologies = explode( ',', $batchontologies );
		} else {
			$batchontologies = array();
		}
		//print_r($batchontologies);
		//print_r($keywords);
		
		$submit = false;
		if ( array_key_exists( 'submit', $params ) ) {
			$submit = true;
			unset( $params['submit'] );
		}
		
		$ontologies = $this->model->getAllOntology();
		
		if ( $submit && !empty($keywords) ) {
			$jsons = array();
			foreach ( $keywords as $keyword ) {
				if ( empty( $batchontologies ) ) {
					$jsons[$keyword] = $this->model->searchKeyword( $keyword, '', 10000, true );
				} else {
					$jsons[$keyword] = array();
					foreach( $batchontologies as $batchontology ) {
						$jsons[$keyword] = array_merge ( $jsons[$keyword], $this->model->searchKeyword( $keyword, $batchontology, 10000, true ) );
					}
				}
			}
		}
		
		require VIEWPATH . 'Search/batchsearch.php';
	}
	
	public function redirect( $params = array() ) {
		$id = $params['id'];
		if ( preg_match('/[a-zA-Z]+_\d/', $id ) ) {
			$redirect = "Location: http://purl.obolibrary.org/obo/$id";
		} else {
			if (preg_match('/[a-zA-Z]+:\d/', $id)) {
				$suggestion = 'http://purl.obolibrary.org/obo/' . preg_replace( '/:/', '_', $id );
			}
		}
	
		require VIEWPATH . 'Search/redirect.php';
	}
	
	private function writeExcel( $json ) {
		#TODO: Add to session folder
		$resultFileName = uniqid();
		
		$csvFileName =  TMPPATH . "search_result.csv";
		file_put_contents( $csvFileName, "Query term, Ontology, URI\n" );
		chmod( $csvFileName, 0777 );
		
		$tsvFileName =  TMPPATH . "search_result.tsv";
		file_put_contents( $tsvFileName, "Query term\tOntology\tURI\n" );
		chmod( $tsvFileName, 0777 );
		
		require_once PHPLIB . 'PHPExcel.php';
		# PHPExcel_Cell_AdvancedValueBinder
		require_once PHPLIB . 'PHPExcel/Cell/AdvancedValueBinder.php';
		# PHPExcel_IOFactory
		require_once PHPLIB . 'PHPExcel/IOFactory.php';
		# Set value binder
		\PHPExcel_Cell::setValueBinder( new \PHPExcel_Cell_AdvancedValueBinder() );
		# Create new PHPExcel object
		$objPHPExcel = new \PHPExcel();
		$objPHPExcel->getActiveSheet()->getColumnDimension( 'A' )->setWidth( 10 );
		$objPHPExcel->getActiveSheet()->getColumnDimension( 'B' )->setWidth( 45 );
		$objPHPExcel->getActiveSheet()->getColumnDimension( 'C' )->setWidth( 15 );
		$objPHPExcel->getActiveSheet()->getColumnDimension( 'D' )->setWidth( 50 );
		$styleArray = array( 'font' => array( 'bold' => true ) );
		$objPHPExcel->getActiveSheet()->getStyle( 'A1' )->applyFromArray( $styleArray );
		$objPHPExcel->getActiveSheet()->getStyle( 'B1' )->applyFromArray( $styleArray );
		$objPHPExcel->getActiveSheet()->getStyle( 'C1' )->applyFromArray( $styleArray );
		$objPHPExcel->getActiveSheet()->getStyle( 'D1' )->applyFromArray( $styleArray );
		$objPHPExcel->getActiveSheet()->getCellByColumnAndRow( 0, 1 )->setValue( "Index" );
		$objPHPExcel->getActiveSheet()->getCellByColumnAndRow( 1, 1 )->setValue( "Query Result Term" );
		$objPHPExcel->getActiveSheet()->getCellByColumnAndRow( 2, 1 )->setValue( "Ontology" );
		$objPHPExcel->getActiveSheet()->getCellByColumnAndRow( 3, 1 )->setValue( "Term IRI" );
		
		foreach ( $json as $index => $match ) {
			$row = array( $match['value'], $match['ontology'], $match['iri'] );
			
			file_put_contents( $csvFileName, implode( ",", $row ) . PHP_EOL, FILE_APPEND );
			
			file_put_contents( $tsvFileName, implode( "\t", $row ) . PHP_EOL, FILE_APPEND );
			
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow( 0, $index + 2 )->setValue( "" . ( $index + 1 ) );
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow( 1, $index + 2 )->setValue( $row[0] );
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow( 2, $index + 2 )->setValue( $row[1] );
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow( 3, $index + 2 )->setValue( $row[2] );
		}
		
		$objWriter = \PHPExcel_IOFactory::createWriter( $objPHPExcel, "Excel2007" );
		$objWriter->save( TMPPATH . 'search_result.xlsx' );
		chmod( TMPPATH . 'search_result.xlsx', 0777 );
	}
	
}

?>