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
 * @file Hook.php
 * @author Edison Ong
 * @since Oct 19, 2015
 * @comment 
 */
 

class Hook {
	public static function run( $hook, $params = array() ) {
		$hooks = $GLOBALS['Hooks'];
		if ( array_key_exists( $hook, $hooks ) ) {
			foreach( $hooks[$hook] as $callback ) {
				if ( is_callable( $callback ) ) {
					call_user_func_array( $callback, $params );
				} else {
					trigger_error( "Unable to call $callback", E_USER_WARNING );
				}
			}
		}
	}
}


?>