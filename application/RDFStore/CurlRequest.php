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
 * @file CurlRequest.php
 * @author Zuoshuang Allen Xiang
 * @author Edison Ong
 * @since Sep 5, 2015
 * @comment 
 */
 
namespace RDFStore;

class CurlRequest {

	public static function curlPostContents( $url, $field ) {
		if ( ENVIRONMENT == 'development' && $GLOBALS['show_query'] ) {
			print_r( htmlspecialchars( $field['query'] ) );
		}
		$request = curl_init();
		$fieldQuery = http_build_query( $field );
		
		curl_setopt( $request, CURLOPT_URL, $url );
		curl_setopt( $request, CURLOPT_POST, count( $field ) );
		curl_setopt( $request, CURLOPT_POSTFIELDS, $fieldQuery );
		curl_setopt( $request, CURLOPT_RETURNTRANSFER, true );
		
		$result = curl_exec( $request );
		
		curl_close( $request );

		return( trim( $result ) );
	}

	public static function curlMultiPostContents( $url, $fields ) {
		if ( ENVIRONMENT == 'development' && $GLOBALS['show_query'] ) {
			foreach ( $fields as $field ) {
				print_r( $field['query'] );
			}
		}

		$requests = curl_multi_init();
		$handles = array();
		$results = array();

		foreach ( $fields as $key => $field ) {
			$request = curl_init();

			$fieldQuery = http_build_query( $field );
			curl_setopt( $request, CURLOPT_URL, $url );
			curl_setopt( $request, CURLOPT_POST, count( $field ) );
			curl_setopt( $request, CURLOPT_POSTFIELDS, $fieldQuery );
			curl_setopt( $request, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $request, CURLOPT_TIMEOUT, 30 );

			curl_multi_add_handle( $requests, $request );

			$handles[$key] = $request;
		}

		$running = null;

		do {
			curl_multi_exec( $requests, $running );
			usleep( 1000 );
		} while ( $running > 0 );

		foreach ( $handles as $key => $handle ) {
			$results[$key] = curl_multi_getcontent( $handle );

			curl_multi_remove_handle( $requests, $handle );
		}

		curl_multi_close( $requests );

		return $results;
	}

}

?>