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
 * @since Sep 8, 2015
 * @comment 
 */
 
use View\Helper;

if ( !$this ) {
	exit( header( 'HTTP/1.0 403 Forbidden' ) );
}

$rootURL = SITEURL . 'ontostat/';

?>

<?php require TEMPLATE . 'header.default.dwt.php'; ?>

<?php require TEMPLATE . 'Ontostat/title.php'; ?>

<?php
$html = '';

$html .=
<<<END
<table  align="center" width="800">
<tr align="center" height="25">
<td bgcolor="#AAAAAA"><strong>Index</strong></td>
<td bgcolor="#AAAAAA"><strong>Ontology Prefix</strong></td>
END;

$totals = array();
foreach ( $GLOBALS['ontology']['type'] as $type => $typeIRI ) {
	$html .=
<<<END
<td bgcolor="#AAAAAA"><strong>$type</strong></td>
END;
	$totals[$type] = 0;
}

$html .=
<<<END
<td bgcolor="#AAAAAA"><strong>Total</strong></td>
</tr>
END;

$index = 0;
foreach ( $stats as $graph => $stat ) {
	$total = 0;
	$index += 1;
	if ( $index % 2 == 0 ) {
		$bgcolor = '#CCECFB';
	} else {
		$bgcolor = '';
	}
	$html .=
<<<END
<tr align="center" height="25" bgcolor="$bgcolor">
<td><strong>$index</strong></td>
<td><a href="$rootURL{$ontologies[$graph]->ontology_abbrv}">
{$ontologies[$graph]->ontology_abbrv}</a></td>
END;
	
	foreach ( $GLOBALS['ontology']['type'] as $type => $typeIRI ) {
		$html .=
<<<END
<td>{$GLOBALS['call_function']( number_format( $stat[$type] ) )}</td>
END;
		$total += $stat[$type];
		$totals[$type] += $stat[$type];
	}
	$html .=
<<<END
<td>{$GLOBALS['call_function']( number_format( $total ) )}</td>
</tr>
END;
}

if ( $index % 2 == 0 ) {
	$bgcolor = '#CCECFB';
} else {
	$bgcolor = '';
}

$html .=
<<<END
<tr align="center" height="25" bgcolor="$bgcolor">
<td><strong>Total</strong></td>
<td>-</td>
END;

foreach ( $GLOBALS['ontology']['type'] as $type => $typeIRI ) {
	$html .=
<<<END
<td><strong>{$GLOBALS['call_function']( number_format( $totals[$type] ) )}</strong></td>
END;
}

$html .=
<<<END
<td><strong>{$GLOBALS['call_function']( number_format( array_sum( $totals ) ) )}</strong></td>
</tr>
</table>
END;

echo Helper::tidyHTML( $html );
?>

<br>
<p><strong>Note:</strong> This version of statistics metrics does not include any instance data yet. This feature will be added in the future. </p>
<p>&nbsp;</p>

<?php require TEMPLATE . 'footer.default.dwt.php'; ?>