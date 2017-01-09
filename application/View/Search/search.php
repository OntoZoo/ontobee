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
$printed = array();

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
$validResult = array();
$depreResult = array();
foreach ( $json as $index => $match ) {
	if ( !$match['deprecate'] ) {
		if ( !array_key_exists( $match['iri'], $validResult ) ) {
			$validResult[$match['iri']] = array();
		}
		if ( !array_key_exists( $match['value'], $validResult[$match['iri']]) ) {
			$validResult[$match['iri']][$match['value']] = array();
		}
		$validResult[$match['iri']][$match['value']][] = $match['ontology'];
	} else {
		if ( !array_key_exists( $match['iri'], $depreResult ) ) {
			$depreResult[$match['iri']] = array();
		}
		if ( !array_key_exists( $match['value'], $depreResult[$match['iri']]) ) {
			$depreResult[$match['iri']][$match['value']] = array();
		}
		$depreResult[$match['iri']][$match['value']][] = $match['ontology'];
	}
}

foreach ( $validResult as $resultIRI => $resultValue ) {
	$termIRI = Helper::encodeURL( $resultIRI );
	$prefix = Helper::getIRIPrefix( $resultIRI );
	echo
<<<END
<li class="search-list">
<a class="term" href="$termIRI">$resultIRI</a> <strong>($prefix)</strong>:
<ul>
END;
	foreach ( $resultValue as $resultLabel => $availableOntologies ){
		$availableOntologies = array_unique( $availableOntologies );
		sort( $availableOntologies );
		if ( ( $index = array_search( $prefix, $availableOntologies ) ) !== false ) {
			/* In case purl link is not redirecting back to ontobee
			 * We still need to display the ontobee link
		     */
			$tmpToken = $availableOntologies[$index];
			unset( $availableOntologies[$index] );
			array_unshift( $availableOntologies, $tmpToken );
		}
		echo
<<<END
<li>
{$GLOBALS['call_function']( preg_replace( "/($tkeyword)/i", '<strong>$1</strong>', $resultLabel ) )}
END;
		if ( !empty( $availableOntologies ) ) {
			echo " <i> in Ontobee</i>: ";
		}
		foreach ( $availableOntologies as $index => $availableOntology ) {
			echo
<<<END
<a class="term" href="{$site}ontology/$availableOntology?iri=$termIRI">$availableOntology</a>
END;
			if ( $index < sizeof( $availableOntologies ) - 1 ) {
				echo ", ";
			}
		}
		echo
<<<END
</li>
END;
	}
	echo
<<<END
</ul>
</li>
END;
}
if ( !empty( $depreResult ) ) {
	echo
<<<END
<div class="term" style="margin-top:10px"><a href="javascript:switch_deprecate();" id="href_switch_deprecate">Show Deprecated Terms</a></div>
<div id="div_deprecate" style="display:none">
END;
	foreach ( $depreResult as $resultIRI => $resultValue ) {
		$termIRI = Helper::encodeURL( $resultIRI );
		$prefix = Helper::getIRIPrefix( $resultIRI );
		echo
<<<END
<li style="text-decoration:line-through">
<a class="term" href="$termIRI">$resultIRI</a> <strong>($prefix)</strong>:
<ul>
END;
		foreach ( $resultValue as $resultLabel => $availableOntologies ){
			$availableOntologies = array_unique( $availableOntologies );
			if ( ( $index = array_search( $prefix, $availableOntologies ) ) !== false ) {
				unset($availableOntologies[$index]);
			}
			echo
<<<END
<li>
{$GLOBALS['call_function']( preg_replace( "/($tkeyword)/i", '<strong>$1</strong>', $resultLabel ) )}
END;
			if ( !empty( $availableOntologies ) ) {
				echo " <i> also in</i>: ";
			}
			foreach ( $availableOntologies as $index => $availableOntology ) {
				echo
<<<END
<a class="term" href="{$site}ontology/$availableOntology?iri=$termIRI">$availableOntology</a>
END;
				if ( $index < sizeof( $availableOntologies ) - 1 ) {
					echo ", ";
				}
			}
			echo
<<<END
</li>
END;
		}
		echo
<<<END
</ul>
</li>
END;
	}
	echo
<<<END
</div>
END;
}



if ( !empty( $keyOntology ) && !empty( $json ) ) {
	foreach ( $json as $index => $match ) {
		$termIRI = Helper::encodeURL( $match['iri'] );
		if ( $match['ontology'] == $ontology->ontology_abbrv && !$match['deprecate'] ) {
			$check = "{$match['value']}|{$match['ontology']}|$termIRI";
			if ( !array_key_exists( $check, $printed ) ) {
				echo 
<<<END
<li>{$GLOBALS['call_function']( preg_replace( "/($tkeyword)/i", '<strong>$1</strong>', $match['value'] ) )}
<strong>({$match['ontology']})</strong>: <a class="term" href="{$site}ontology/{$match['ontology']}?iri=$termIRI">
{$match['iri']}</a></li>
END;
				$printed[$check] = null;
			}
			unset( $json[$index] );
		}
	}
}
?>

<?php 
/*if ( !empty( $json ) ) {
	foreach ( $json as $index => $match ) {
		$termIRI = Helper::encodeURL( $match['iri'] );
		if ( !$match['deprecate'] ) {
			$check = "{$match['value']}|{$match['ontology']}|$termIRI";
			if ( !array_key_exists( $check, $printed ) ) {
				echo
<<<END
<li>{$GLOBALS['call_function']( preg_replace( "/($tkeyword)/i", '<strong>$1</strong>', $match['value'] ) )}
<strong>({$match['ontology']})</strong>: <a class="term" href="{$site}ontology/{$match['ontology']}?iri=$termIRI">
{$match['iri']}</a></li>
END;
				$printed[$check] = null;
			}
			unset( $json[$index] );
		}
	}
}*/
?>

<?php 
/*if ( !empty( $json ) ) {
	echo 
<<<END
<div style="margin-top:10px"><a href="javascript:switch_deprecate();" id="href_switch_deprecate">Show Deprecated Terms</a></div>
<div id="div_deprecate" style="display:none">
END;
	foreach ( $json as $index => $match ) {
		$termIRI = Helper::encodeURL( $match['iri'] );
		$check = "{$match['value']}|{$match['ontology']}|$termIRI";
		if ( !array_key_exists( $check, $printed ) ) {
			echo
<<<END
<li style="text-decoration:line-through">{$GLOBALS['call_function']( preg_replace( "/($tkeyword)/i", '<strong>$1</strong>', $match['value'] ) )}
<strong>({$match['ontology']})</strong>: <a class="term" href="{$site}ontology/{$match['ontology']}?iri=$termIRI">
{$match['iri']}</a></li>
END;
			$printed[$check] = null;
		}
	}
}*/
?>

</ol>
</p>

<br>

<p>Download search result file in <a href="<?php echo TMPURL . 'search_result'; ?>.tsv" target="_blank">tsv (Tab-separated values)</a> or <a href="<?php echo TMPURL . 'search_result'; ?>.csv" target="_blank">csv (Comma-separated values)</a> format.<br>
Download search result file in <a href="<?php echo TMPURL . 'search_result'; ?>.xlsx" target="_blank">Excel</a> format.</p>

<?php require TEMPLATE . 'footer.default.dwt.php'; ?>