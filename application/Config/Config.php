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
 * @file Config.php
 * @author Edison Ong
 * @since Sep 3, 2015
 * @comment This is basic configuration file of Ontobee installation.
 *          Memory is set to 4096Mb in order to handle large ontologies such as Gene Ontology(GO).
 *          If ENVIRONMENT is set to development, both SPARQL query and PHP error message will be display.
 *          This file also call DB.php and OntologyConfig.php for database and ontology configuration respectively.
 */

# Set maximum memory
ini_set( 'memory_limit', '4096M' );

# Error and SPARQL reporting if under development
DEFINE( 'ENVIRONMENT', 'development' );
if ( ENVIRONMENT == 'development' ) {
	error_reporting(E_ALL);
	ini_set("display_errors", 1);
}

# Define site URL and path constant
DEFINE( 'SITEURL', 'http://' . $_SERVER['HTTP_HOST'] . '/' );
DEFINE( 'TMPURL', SITEURL . 'tmp/' );
DEFINE( 'VIEWPATH', SCRIPTPATH . 'application/View' . DIRECTORY_SEPARATOR );
DEFINE ( 'TMPPATH', SCRIPTPATH . 'tmp' . DIRECTORY_SEPARATOR );
DEFINE( 'TEMPLATE', SCRIPTPATH . 'application/Layout' . DIRECTORY_SEPARATOR );
DEFINE( 'PHPLIB', SCRIPTPATH . 'library/php' . DIRECTORY_SEPARATOR );

# Load database configuration
require APPPATH . 'Config/DB.php';

# Load mail configuration
require APPPATH . 'Config/MailConfig.php';

# Load ontology configuration
require APPPATH . 'Config/OntologyConfig.php';

# Load hooks
require APPPATH . 'Config/HookConfig.php';

# Define function caller variable
$GLOBALS['call_function'] = function( $function ) {
	return $function;
};

?>