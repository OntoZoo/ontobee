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
 * @file Api.php
 * @author Edison Ong
 * @since Oct 1, 2015
 * @comment 
 */

ini_set( 'memory_limit', '4096M' );

abstract class Maintenance {
	protected $db;
	
	public function setup() {
		$tokens = explode( DIRECTORY_SEPARATOR, __DIR__ );
		$dir = implode( DIRECTORY_SEPARATOR, array_splice( $tokens, 0, -1 ) );
		DEFINE( 'SCRIPTPATH',  $dir . DIRECTORY_SEPARATOR );
		DEFINE( 'APPPATH', SCRIPTPATH . 'application' . DIRECTORY_SEPARATOR );
		DEFINE( 'SYSTMP', sys_get_temp_dir() . DIRECTORY_SEPARATOR );
		DEFINE ( 'TMPPATH', SCRIPTPATH . 'tmp' . DIRECTORY_SEPARATOR );
		require APPPATH . 'Config/DB.php';
		require APPPATH . 'Config/OntologyConfig.php';
		require SCRIPTPATH . 'vendor/autoload.php';
	}
	
	protected function openPDOConnection() {
		$options = array(
			\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
			\PDO::ATTR_ERRMODE => \PDO::ERRMODE_WARNING,
		);
		$this->db = new \PDO( DB_DRIVER . ':host=' . DB_HOST . ';dbname=' . DB_SCHEMA, DB_USERNAME, DB_PASSWORD, $options );
	}
	
	protected function getFinalURL( $url ) {
		$request = curl_init ( $url );
		curl_setopt ( $request, CURLOPT_FOLLOWLOCATION, true );
		curl_setopt ( $request, CURLOPT_RETURNTRANSFER, true );
	
		curl_exec ( $request );
	
		if ( !curl_errno ( $request ) )
			$url = curl_getinfo ($request, CURLINFO_EFFECTIVE_URL );
	
		curl_close ( $request );
	
		return $url;
	}
}

?>
