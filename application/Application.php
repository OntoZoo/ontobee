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
 * @file Application.php
 * @author Edison Ong
 * @since Sep 3, 2015
 * @comment 
 */

class Application {
	
	public static function webStart () {
		$controller = null;
		$method = null;
		$params = array();
		
		if ( isset( $_REQUEST['url'] ) ) {
			$url = trim( $_REQUEST['url'] );
			$url = filter_var( $url, FILTER_SANITIZE_URL );
			unset( $_REQUEST['url'] );
			
			$urlComps = explode( '/', $url );
			$urlComps = array_diff($urlComps, array('', 'index.php'));
			$urlComps = array_values( $urlComps );
			
			if ( isset( $urlComps[0] ) ) {
				if ( method_exists( '\\Controller\\IndexController', $urlComps[0] ) ) {
					$method = $urlComps[0];
				} else {
					$controller = '\\Controller\\' . ucfirst( $urlComps[0] . 'Controller');
				}
				unset( $urlComps[0] );
				$urlComps = array_values( $urlComps );
				
				if ( isset( $urlComps[0] ) ) {
					if ( method_exists( $controller, $urlComps[0] ) ) {
						$method = $urlComps[0];
						unset( $urlComps[0] );
					}
					$params = array_values( $urlComps );
				}
				
				if ( !empty( $_REQUEST ) ) {
					foreach ( $_REQUEST as $name=>$value ) {
						$params[$name] = $value;
					}
				}
				
			}
		}
		
		#print_r( "$controller : $method" );
		#print_r( $params );
		
		if ( !is_null( $controller ) && class_exists( $controller )) {
			$controller = new $controller();
			if ( !is_null( $method ) && method_exists( $controller, $method ) ) {
				if ( !empty( $params ) ) {
					call_user_func_array( array( $controller, $method ), array( $params ) );
				} else {
					$controller->{$method}();
				}
			} else if ( is_null( $method ) ) {
				if ( !empty( $params ) ) {
					call_user_func_array( array( $controller, 'index' ), array( $params ) );
				} else {
					$controller->index();
				}
			} else {
				$controller = new \Controller\ErrorController();
				$controller->index();
			}
		} else if ( is_null( $controller ) && !is_null( $method ) ) {
			$controller = new \Controller\IndexController();
			$controller->{$method}();
		} else if ( is_null( $controller ) ) {
			$controller = new \Controller\IndexController();
			$controller->index();
		} else {
			$controller = new \Controller\ErrorController();
			$controller->index();
		}
	}
	
}



?>