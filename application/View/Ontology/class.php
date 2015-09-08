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
 * @file class.php
 * @author Edison Ong
 * @since Sep 5, 2015
 * @comment 
 */

if ( !$this ) {
	exit(header('HTTP/1.0 403 Forbidden'));
}

?>

<!-- Ontobee Template: header.default.dwt.php -->
<?php require TEMPLATE . 'header.default.dwt.php'; ?>

<link href="<?php echo SITEURL; ?>public/css/ontology.css" rel="stylesheet" type="text/css">
<script src="<?php echo SITEURL; ?>public/js/ontobee.ontology.js"></script>

<!-- Ontobee Ontology Template: title.php -->
<?php require TEMPLATE . 'Ontology/title.php'; ?>

<!-- Ontobee Template: search.keyword.php -->
<?php require TEMPLATE . 'search.keyword.php'; ?>

<?php require TEMPLATE . 'Ontology/about.php'; ?>

<?php require TEMPLATE . 'Ontology/annotation.php'; ?>

<?php require TEMPLATE . 'Ontology/equivalent.axiom.php'; ?>
 
<?php require TEMPLATE . 'Ontology/hierarchy.php'; ?>

<?php require TEMPLATE . 'Ontology/subclassof.axiom.php'; ?>

<?php require TEMPLATE . 'Ontology/term.use.php'; ?>

<?php require TEMPLATE . 'Ontology/current.use.php'; ?>

<?php require TEMPLATE . 'Ontology/ontology.use.php'; ?>

<?php require TEMPLATE . 'sparql.query.php'; ?>

<?php require TEMPLATE . 'system.time.php'; ?>

<?php require TEMPLATE . 'footer.default.dwt.php'; ?>