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
 * @file statistic.php
 * @author Edison Ong
 * @since Mar 29, 2017
 * @comment 
 */
 
if ( !$this ) {
	exit( header( 'HTTP/1.0 403 Forbidden' ) );
}

$site = SITEURL;

?>

<?php require TEMPLATE . 'header.default.dwt.php'; ?>

<h3><span class="head3_darkred">Ontobeep: Term statistics </span></h3>

<?php 

$html = '';

$html .=
<<<END
<table cellpadding="6" style="border:1px solid #808080">
	<tr>
		<td style="font-weight:bold; border:1px #7FDFFF solid; text-align:center">Ontology</td>
		<td style="font-weight:bold; border:1px #7FDFFF solid; text-align:center">Number of terms<br>
		 (including imported)</td>
		<td style="font-weight:bold; border:1px #7FDFFF solid; text-align:center">Number of terms<br>
		 (excluding imported)</td>
	</tr>
END;
foreach ( $typeTerms as $ontAbbr => $typeTerm) {
	$countAll = 0;
	foreach( $typeTerm as $prefix => $terms ) {
		$count = 0;
		$count += sizeof( $terms );
		if ( $ontAbbr == $prefix ) {
			$countNative = $count;
		}
		$countAll += $count;
	}
	$html .=
<<<END
	<tr>
		<td style="font-weight:bold; border:1px #7FDFFF solid; text-align:center">$ontAbbr</td>
		<td style="font-weight:bold; border:1px #7FDFFF solid; text-align:center">$countAll</td>
 		<td style="font-weight:bold; border:1px #7FDFFF solid; text-align:center">$countNative</td>
 	</tr>
END;
}

$html .=
<<<END
</table>
END;

$termOntMap = array();
foreach ( $typeTerms as $ontAbbr => $typeTerm) {
	foreach( $typeTerm as $prefix => $terms ) {
		foreach( $terms as $term ) {
			if ( !array_key_exists( $term['term'], $termOntMap ) ) {
				$termOntMap[$term['term']] = array();
			}
			$termOntMap[$term['term']][] = $ontAbbr;
		}
	}
}

$shareCount = 0;
foreach( $termOntMap as $termIRI => $ontMap ) {
	if ( sizeof( $ontMap ) == sizeof( $ontologies ) ) {
		$shareCount ++;
	}
}
$html .=
<<<END
<p style="font-size:18px">Terms shared by all selected ontologies: $shareCount</p>
END;

if ( sizeof( $ontologies ) == 3 ) {
	$html .=
<<<END
<ul>
END;
	for ( $i = 0; $i < sizeof( $ontologies ) -1; $i++ ) {
		for ( $j = $i + 1; $j < sizeof( $ontologies ); $j++ ) {
			
			$pairCount = 0;
			$ontAbbr1 = $ontologies[$i];
			$ontAbbr2 = $ontologies[$j];
			foreach( $termOntMap as $termIRI => $ontMap ) {
				if ( in_array( $ontAbbr1, $ontMap ) && in_array( $ontAbbr2, $ontMap ) ) {
					$pairCount += 1;
				}
			}
			$html .=
<<<END
<li style="font-size:16px">
Terms shared by $ontAbbr1 & $ontAbbr2: $pairCount 
</li>
END;
		}
	}
	$html .=
<<<END
</ul>
END;
}

$html .=
<<<END
<p style="font-size:18px">Terms have two or more IDs:</p>
<ol>
END;

$labelOntMap = array();
foreach ( $typeTerms as $ontAbbr => $typeTerm) {
	foreach( $typeTerm as $prefix => $terms ) {
		foreach( $terms as $term ) {
			if ( $term['label'] == '' ) {
				continue;
			}
			if ( !array_key_exists( $term['label'], $labelOntMap ) ) {
				$labelOntMap[$term['label']] = array();
			}
			if ( !array_key_exists( $term['term'], $labelOntMap[$term['label']] ) ) {
				$labelOntMap[$term['label']][$term['term']] = array();
			}
			$labelOntMap[$term['label']][$term['term']][] = $ontAbbr;
		}
	}
}
ksort( $labelOntMap );
$colorkey = array_flip( $ontologies );
foreach( $labelOntMap as $label => $term ) {
	if ( sizeof( $term ) > 1 ) {
		$html .=
<<<END
<li style="font-size:16px"><b>{$label}: </b>
<ul>
END;
		foreach( $term as $termIRI => $ontAbbrs ) {
			$html .=
<<<END
<li>
$termIRI
END;
			foreach( $ontAbbrs as $ontAbbr ) {
				$html .=
<<<END
 (<span style="font-weight:bold; color:{$GLOBALS['ontobeep_colorkey'][$colorkey[$ontAbbr]]}; cursor:pointer" onClick="window.open('{$site}ontology/$ontAbbr?iri={$GLOBALS['call_function']( Helper::encodeURL( $termIRI ) )}')">$ontAbbr</span>)
END;
			}
			$html .=
<<<END
</li>
END;
		}
		$html .=
<<<END
</ul>
</li>
END;
	}
}

$html.=
<<<END
</ol>
END;

echo Helper::tidyHTML( $html );
?>

<?php require TEMPLATE . 'footer.default.dwt.php'; ?>

