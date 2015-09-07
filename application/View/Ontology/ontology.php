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
 * @file ontology.php
 * @author Edison Ong
 * @since Sep 5, 2015
 * @comment 
 */

use View\Helper;

if ( !$this ) {
	exit(header('HTTP/1.0 403 Forbidden'));
}

$site = SITEURL;

?>

<?php require TEMPLATE . 'header.default.dwt.php'; ?>

<?php require TEMPLATE . 'Ontology/title.php'; ?>

<?php require TEMPLATE . 'Ontology/about.php'; ?>

<?php require TEMPLATE . 'Ontology/annotation.php'; ?>

<?php require TEMPLATE . 'search.keyword.php'; ?>

<div style="font-weight:bold">
Number of Terms (<span class="darkred">including imported terms</span>)  <a href="<?php echo SITEURL; ?>ontostat/?ontology=<?php echo $ontology->ontology_abbrv; ?>">(Detailed Statistics)</a></div>
<div style="background-color:#EAF1F2; border:#99CCFF 1px solid; margin-top:4px; margin-bottom:12px">
<ul>

<?php
$html = '';
foreach ( $GLOBALS['ontology']['type'] as $type => $typeIRI ) {
	$size = sizeof( $ontology->$type );
	if ( $size > 0 ) {
		$html .=
			"<li><a href=\"{$site}ontology/term/?o=$ontology->ontology_abbrv&amp;iri=" .
			urlencode( $typeIRI) .
			"\">$type</a> ($size)</li>";
	}
}
echo Helper::tidyHTML( $html );
?>

</ul>
</div>


<?php
$html = '';
if ( !empty( $ontology->key_term ) ) {
	$html =
<<<END
<div style="font-weight:bold">Top level terms and selected core <?php echo $ontology->ontology_abbrv; ?> terms</div>
<div style="background-color:#EAF1F2; border:#99CCFF 1px solid; margin-top:4px; margin-bottom:12px">
<ul>
END;
	foreach ( $ontology->key_term as $term ) {
		$html .= "<li><a href=\"{$site}ontology/?o=$ontology->ontology_abbrv&amp;iri=$term->term_url\">$term->term_label</a></li>";
	}
	$html .= '</ul></div>';
}
echo Helper::tidyHTML( $html );
?>

<?php require TEMPLATE . 'sparql.count.php'; ?>

<?php require TEMPLATE . 'footer.default.dwt.php'; ?>