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

<!-- Ontobee Ontology home page -->

<!-- Ontobee Template: header.default.dwt.php -->
<?php require TEMPLATE . 'header.default.dwt.php'; ?>

<link href="<?php echo SITEURL; ?>public/css/ontology.css" rel="stylesheet" type="text/css">
<script src="<?php echo SITEURL; ?>public/js/ontobee.ontology.js"></script>

<!-- Ontobee Ontology Template: title.php -->
<?php require TEMPLATE . 'Ontology/title.php'; ?>

<!-- Ontobee Template: search.keyword.php -->
<?php require TEMPLATE . 'search.keyword.php'; ?>

<!-- Ontobee Ontology Template: about.php -->
<?php require TEMPLATE . 'Ontology/about.php'; ?>

<!-- Ontobee Ontology Template: annotation.php -->
<?php require TEMPLATE . 'Ontology/annotation.php'; ?>

<!-- Ontobee Ontology home page: Number of Terms -->
<div class="section-title">
Number of Terms (<span class="darkred">including imported terms</span>)  <a href="<?php echo SITEURL; ?>ontostat/<?php echo $ontology->ontology_abbrv; ?>">(Detailed Statistics)</a></div>
<div class="section">
<?php
$html =
<<<END
<ul>
END;
foreach ( $GLOBALS['ontology']['type'] as $type => $typeIRI ) {
	$size = sizeof( $ontology->$type );
	if ( $size > 0 ) {
		$html .=
<<<END
<li><a href="{$site}ontology/term/$ontology->ontology_abbrv?iri=
{$GLOBALS['call_function']( Helper::encodeURL( $typeIRI ) )}
">$type</a> ($size)</li>
END;
	}
}
$html .=
<<<END
</ul>
END;
echo Helper::tidyHTML( $html );
?>
</div>

<!-- Ontobee Ontology home page: Key terms -->
<?php
$html = '';
if ( !empty( $ontology->key_term ) ) {
	$html =
<<<END
<div class="section-title">Top level terms and selected core <?php echo $ontology->ontology_abbrv; ?> terms</div>
<div class="section">
<ul>
END;
	foreach ( $ontology->key_term as $term ) {
		$html .=
<<<END
<li><a href="{$site}ontology/$ontology->ontology_abbrv?iri=
{$GLOBALS['call_function']( Helper::encodeURL( $term->term_url ) ) }">$term->term_label</a></li>
END;
	}
	$html .= 
<<<END
</ul></div>
END;
}
echo Helper::tidyHTML( $html );
?>

<?php require TEMPLATE . 'sparql.count.php'; ?>

<?php require TEMPLATE . 'footer.default.dwt.php'; ?>