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
 * @file ontology.use.php
 * @author Yongqun Oliver He
 * @author Zuoshuang Allen Xiang
 * @author Edison Ong
 * @since Sep 6, 2015
 * @comment 
 */
 
use View\Helper;

if ( !$this ) {
	exit(header('HTTP/1.0 403 Forbidden'));
}

$site = SITEURL;

$others = array();
foreach( $term->other as $index => $graph ) {
	if ( $graph != $ontology->ontology_graph_url ) {
		$others[] = $graph;
	}
}

if ( !empty( $others ) ) {
	$html =
<<<END
<div class="section-title">Ontologies that use the $term->type</div>
<div class="section">

<table>
<tr>
<td bgcolor="#EAF1F2" style="font-weight:bold">Ontology listed in Ontobee</td>
<td bgcolor="#EAF1F2" style="font-weight:bold">Ontology OWL file</td>
<td bgcolor="#EAF1F2" style="font-weight:bold">View class in context</td>
<td bgcolor="#EAF1F2" style="font-weight:bold">Project home page</td>
</tr>
END;
	foreach ( $others as $graph ) {
		$other = $ontologyList[$graph];
		if ( $other->download != '' ) {
			$download = $other->download;
		} else {
			$download = $other->ontology_url;
		}
		$html .=
<<<END
<tr>
<td bgcolor="#EAF1F2"><a href="{$site}ontology/$other->ontology_abbrv">$other->ontology_fullname</a></td>
<td bgcolor="#EAF1F2"><a href="$download">{$GLOBALS['call_function']( Helper::getShortTerm( $download ) )}</a></td>
<td bgcolor="#EAF1F2"><a oncontextmenu="return false;" href="{$site}ontology/$other->ontology_abbrv?iri=
{$GLOBALS['call_function']( Helper::encodeURL( $termIRI ) )}">
'$term->label' in {$GLOBALS['call_function']( Helper::getShortTerm( $download ) )}</a></td>
<td bgcolor="#EAF1F2">
END;
		if ( $other->home != '' ) {
			$tokens = preg_split( '/\|/', $other->home );
			if ( sizeof( $tokens ) == 2 ) {
				$html .=
<<<END
<a href="{$GLOBALS['call_function']( Helper::encodeURL( $tokens[1] ) )}">{$tokens[0]}</a>
END;
	 		} else {
	 			$html .=
<<<END
<a href=\"{$GLOBALS['call_function']( Helper::encodeURL( $tokens[0] ) )}\">Project home page</a>
END;
	 		}
		}
			
		
		$html .=
<<<END
</td></tr>
END;
		
		
	}
	
	$html .=
<<<END
</table>
END;
	
	echo Helper::tidyHTML( $html );
}

?>