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
 * @file IndexController.php
 * @author Edison Ong
 * @since Sep 3, 2015
 * @comment 
 */

namespace Controller;

use Controller\Controller;

use Model\OntologyModel;
 
Class IndexController extends Controller {
	
	public function introduction() {
		require VIEWPATH . 'Index/introduction.php';
	}
	
	public function faqs() {
		require VIEWPATH . 'Index/faqs.php';
	}
	
	public function references() {
		require VIEWPATH . 'Index/references.php';
	}
	
	public function links() {
		require VIEWPATH . 'Index/links.php';
	}
	
	public function contactus() {
		require VIEWPATH . 'Index/contactus.php';
	}
	
	public function acknowledge() {
		require VIEWPATH . 'Index/acknowledge.php';
	}
	
	public function news() {
		require VIEWPATH . 'Index/news.php';
	}
	
	public function history() {
		require VIEWPATH . 'Index/history.php';
	}
	
	public function download() {
		require VIEWPATH . 'Index/download.php';
	}
	
	public function index( $params = array() ) {
		$this->loadModel( 'Ontology' );
		$ontologies = $this->model->getAllOntology();
		require VIEWPATH . 'Index/home.php';
	}
	
	public function sparql( $params = array() ) {
		if ( array_key_exists( 'query', $params ) ) {
			$query = $params['query'];
		} else {
			$query = '';
		}
		if ( array_key_exists( 'format', $params ) ) {
			$format = $params['format'];
		} else {
			$format = '';
		}
		if ( array_key_exists( 'maxrows', $params ) ) {
			$maxrows = $params['maxrows'];
		} else {
			$maxrows = '';
		}
		if ( array_key_exists( 'go', $params ) ) {
			$go = $params['go'];
		} else {
			$go = '';
		}
		require VIEWPATH . 'Index/sparql.php';
	}
	
	public function tutorial( $params = array() ) {
		if ( !empty( $params ) ) {
			if ( $params[0] == 'sparql' ) {
				require VIEWPATH . 'Tutorial/sparql.php';
			}
		} else {
			require VIEWPATH . 'Tutorial/index.php';
		}
	}
}



?>