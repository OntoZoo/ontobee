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
 * @file SPARQLQuery.php
 * @author Zuoshuang Allen Xiang
 * @author Edison Ong
 * @since Sep 5, 2015
 * @comment 
 */
 
namespace RDFStore;

class SPARQLQuery {

	private $endpoint;

	private $fields = array();

	public function __construct( $endpoint ) {
		$this->endpoint = $endpoint;
	}

	public function add( $key, $query, $defaultGraph = '', $format = 'application/sparql-results+json', $debug = 'on' ) {
		$field = array();
		$field['default-graph-uri'] = $defaultGraph;
		$field['format'] = $format;
		$field['debug'] = $debug;
		$field['query'] = $query;
		$this->fields[$key] = $field;
	}

	public function execute() {
		if ( sizeof( $this->fields ) == 1 ) {
			$field = array_shift( $this->fields );
			$result = CurlRequest::curlPostContents( $this->endpoint );
			return $result;
		} else if ( sizeof ( $this->fields ) > 1 ) {
			$results = CurlRequest::curlMultiPostContents( $this->endpoint, $this->fields );
			return $results;
		}
	}

	public function clear() {
		$this->fields = array();
	}

	public static function queue( $endpoint, $query, $defaultGraph = '', $format = 'application/sparql-results+json', $debug = 'on' ) {
		$field = array();
		$field['default-graph-uri'] = $defaultGraph;
		$field['format'] = $format;
		$field['debug'] = $debug;
		$field['query'] = $query;
		return CurlRequest::curlPostContents( $endpoint, $field );
	}
}

?>