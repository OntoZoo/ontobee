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
 * @file search.php
 * @author Yongqun Oliver He
 * @author Zuoshuang Allen Xiang
 * @author Edison Ong
 * @since Sep 6, 2015
 * @comment 
 */
 
if ( !$this ) {
	exit( header( 'HTTP/1.0 403 Forbidden' ) );
}

$site = SITEURL;

$tkeyword = preg_replace( '/\W/', ' ', $keyword );

?>

<?php require TEMPLATE . 'header.default.dwt.php'; ?>

&nbsp;

<?php require TEMPLATE . 'search.keyword.php'; ?>

<?php 
if ( empty( $json ) ) {
	echo '<p>No terms returned, please try different keywords.</p>';
}
?>

<p>Terms with '<?php echo $keyword; ?>' included in their label:
<ol>

<?php
if ( !empty( $keyOntology ) && !empty( $json ) ) {
	foreach ( $json as $index => $match ) {
		$termIRI = Helper::encodeURL( $match['iri'] );
		if ( $match['ontology'] == $ontology->ontology_abbrv && !$match['deprecate'] ) {
			echo 
<<<END
<li>{$GLOBALS['call_function']( preg_replace( "/($tkeyword)/i", '<strong>$1</strong>', $match['value'] ) )}
<strong>({$match['ontology']})</strong>: <a class="term" href="{$site}ontology/{$match['ontology']}?iri=$termIRI">
{$match['iri']}</a></li>
END;
			unset( $json[$index] );
		}
	}
}
?>

<?php 
if ( !empty( $json ) ) {
	foreach ( $json as $index => $match ) {
		$termIRI = Helper::encodeURL( $match['iri'] );
		if ( !$match['deprecate'] ) {
			echo
<<<END
<li>{$GLOBALS['call_function']( preg_replace( "/($tkeyword)/i", '<strong>$1</strong>', $match['value'] ) )}
<strong>({$match['ontology']})</strong>: <a class="term" href="{$site}ontology/{$match['ontology']}?iri=$termIRI">
{$match['iri']}</a></li>
END;
			unset( $json[$index] );
		}
	}
}
?>

<?php 
if ( !empty( $json ) ) {
	echo 
<<<END
<div style="margin-top:10px"><a href="javascript:switch_deprecate();" id="href_switch_deprecate">Show Deprecated Terms</a></div>
<div id="div_deprecate" style="display:none">
END;
	foreach ( $json as $index => $match ) {
		$termIRI = Helper::encodeURL( $match['iri'] );
		echo
		'<li style="text-decoration:line-through">' .
		preg_replace( "/($tkeyword)/i", '<strong>$1</strong>', $match['value'] ) .
		"<strong>({$match['ontology']})</strong>: <a href=\"{$site}ontology/{$match['ontology']}?iri=$termIRI\">{$match['iri']}</a></li>";
	}
}
?>

</ol>
</p>

<br>

<p>Download search result file in <a href="<?php echo TMP . 'search_result'; ?>.tsv" target="_blank">tsv (Tab-separated values)</a> or <a href="<?php echo TMP . 'search_result'; ?>.csv" target="_blank">csv (Comma-separated values)</a> format.<br>
Download search result file in <a href="<?php echo TMP . 'search_result'; ?>.xlsx" target="_blank">Excel</a> format.</p>

<?php require TEMPLATE . 'footer.default.dwt.php'; ?>