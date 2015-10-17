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
 * @since Oct 16, 2015
 * @comment 
 */
 

if ( !$this ) {
	exit( header( 'HTTP/1.0 403 Forbidden' ) );
}

if ( isset( $xslt ) && $xslt ) {
	header("Content-type: text/xml");

	echo
	<<<END
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:xs="http://www.w3.org/2001/XMLSchema"
	xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#">
<xsl:output method="xml"
	doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"
	doctype-public="-//W3C//DTD XHTML 1.0 Transitional//EN" indent="yes"/>

<xsl:template match="/rdf:RDF">
END;
	require TEMPLATE . 'header.default.xml.php';
} else {
	require TEMPLATE . 'header.default.dwt.php';
}

?>

<link href="<?php echo SITEURL; ?>public/css/ontology.css" rel="stylesheet" type="text/css"/>
<script src="<?php echo SITEURL; ?>public/js/ontobee.ontology.js"></script>

<!-- Ontobee Ontology Template: title.php -->
<?php require TEMPLATE . 'Ontology/title.php'; ?>

<!-- Ontobee Template: search.keyword.php -->
<?php require TEMPLATE . 'search.keyword.php'; ?>

<?php require TEMPLATE . 'Ontology/about.php'; ?>

<?php require TEMPLATE . 'Ontology/annotation.php'; ?>

<?php require TEMPLATE . 'Ontology/equivalent.axiom.php'; ?>

<?php $hierarchyTitle = 'Class Hierarchy'; require TEMPLATE . 'Ontology/hierarchy.php'; ?>

<?php $superAxiomTitle = 'Superclasses &amp; Asserted Axioms'; require TEMPLATE . 'Ontology/super.axiom.php'; ?>

<?php require TEMPLATE . 'Ontology/disjoint.axiom.php'; ?>

<?php require TEMPLATE . 'Ontology/instance.php'; ?>

<?php require TEMPLATE . 'Ontology/term.use.php'; ?>

<?php require TEMPLATE . 'Ontology/current.use.php'; ?>

<?php require TEMPLATE . 'Ontology/ontology.use.php'; ?>

<?php require TEMPLATE . 'sparql.query.php'; ?>

<?php require TEMPLATE . 'system.time.php'; ?>

<?php require TEMPLATE . 'footer.default.dwt.php'; ?>

<?php 
if ( isset( $xslt ) && $xslt ) {
	echo
<<<END
</xsl:template>
</xsl:stylesheet>
END;
}
?>