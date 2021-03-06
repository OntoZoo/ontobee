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
 * @file Controller.php
 * @author Edison Ong
 * @since Sep 3, 2015
 * @comment 
 */
 
namespace Controller;

use PDO;

abstract class Controller {
	
	protected $db = null;
	
	protected $model = null;
	
	public function __construct() {
		$this->openPDOConnection();
	}
	
	private function openPDOConnection() {
		$options = array(
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
			PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
		);
		$this->db = new PDO( DB_DRIVER . ':host=' . DB_HOST . ';dbname=' . DB_SCHEMA, DB_USERNAME, DB_PASSWORD, $options );
	}
	
	protected function loadModel( $modelName ) {
		$modelName = ucfirst( $modelName );
		$model = "Model\\{$modelName}Model";
		$this->model = new $model( $this->db );
	}
	
	protected function parseOntologyParameter( $params ) {
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