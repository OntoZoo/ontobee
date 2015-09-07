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
 * @file term.use.php
 * @author Edison Ong
 * @since Sep 6, 2015
 * @comment 
 */
 
use View\Helper;

if ( !$this ) {
	exit(header('HTTP/1.0 403 Forbidden'));
}

$rootURL = SITEURL . "ontology/?ontology=$ontology->ontology_abbrv&iri=";
if ( !empty( $term->usage ) ) {
	$html =
<<<END
<div style="font-weight:bold">Uses in this ontology</div>
<div style="background-color:#EAF1F2; border:#99CCFF 1px solid; margin-top:4px; margin-bottom:12px">
<ul>
END;
	foreach ( $term->usage as $useIRI => $usage ) {
		$html .=
			"<li><a oncontextmenu=\"return false;\" href=\"" .
			Helper::convertUTFToUnicode( $useIRI ).
			"\">{$usage['label']}</a>  " .
			Helper::getShortTerm( $usage['type'] ).
			': ' .
			Helper::trimBracket( Helper::writeRecursiveManchester( $rootURL, $usage['axiom'], $term->related ) ) .
			'</li>';
	}
	$html .= '</ul></div>';
	
	echo Helper::tidyHTML( $html );
}

?>