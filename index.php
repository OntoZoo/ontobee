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
 * @file index.php
 * @author Edison Ong
 * @since Sep 3, 2015
 * @comment This is the first entry of any request made to Ontobee server.
 *          This file will call composer for autoloading all classes.
 *          Then call Config.php for setting up Ontobee server configuration.
 *          Finally call Applicaton::webStart() for actually process of request.
 */

# Define Ontobee installation path and application path
DEFINE( 'SCRIPTPATH', __DIR__ . DIRECTORY_SEPARATOR );
DEFINE( 'APPPATH', SCRIPTPATH . 'application' . DIRECTORY_SEPARATOR );

# Call composer autoload function
if ( file_exists( SCRIPTPATH . 'vendor/autoload.php' ) ) {
	require SCRIPTPATH . 'vendor/autoload.php';
} else {
	throw new Exception( 'Composer autoload required' );
	exit;
}

# Load all required configuration
require APPPATH . 'Config/Config.php';

# Web start
Application::webStart();

?>