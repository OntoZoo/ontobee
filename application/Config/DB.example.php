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
 * @file DB.example.php
 * @author Edison Ong
 * @since Sep 6, 2015
 * @comment DEFINE database configuration in this file.
 *          Example set up for DB.php.
 *          After editing the correct configuration, please change the file name to DB.php
 */

# SQL database
# MySQL is used to store descriptive information of all ontologies and miscellaneous data
DEFINE('DB_DRIVER', 'mysql');
# Host (IP address)
DEFINE('DB_HOST', '127.0.0.1');
# The schema/table that store ontologies' information
DEFINE('DB_SCHEMA', 'schema');
# Username and Password
DEFINE('DB_USERNAME', 'username');
DEFINE('DB_PASSWORD', 'password');

# RDF database
# Username and Password
DEFINE('RDF_USERNAME', 'username' );
DEFINE('RDF_PASSWORD', 'password' );
# The command for maintenance script to manipulate RDF database locally
# In this ontobee main website, we deploy Virtuoso RDF database and use isql commend
DEFINE('RDF_ISQL_COMMAND', '/usr/local/virtuoso/bin/isql' );

?>