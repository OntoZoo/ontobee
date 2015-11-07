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
 * @author Yongqun Oliver He
 * @author Zuoshuang Allen Xiang
 * @author Edison Ong
 * @since Sep 6, 2015
 * @comment 
 */

if ( !$this ) {
	exit( header( 'HTTP/1.0 403 Forbidden' ) );
}

$rootURL = SITEURL . "ontology/$ontology->ontology_abbrv?iri=";

$html = '';

if ( !empty( $term->usage ) ) {
	$html .=
<<<END
<div class="section-title">Uses in this ontology</div>
<div class="section">
<ul>
END;
	foreach ( $term->usage as $useIRI => $usage ) {
		$html .=
<<<END
<li><a class="term" oncontextmenu="return false;" href="$rootURL{$GLOBALS['call_function']( Helper::convertUTFToUnicode( $useIRI ) )}">
{$GLOBALS['call_function']( htmlspecialchars( $usage['label'] ) )}
</a> {$GLOBALS['call_function']( Helper::getShortTerm( $usage['type'] ) )} : 
{$GLOBALS['call_function']( Helper::trimBracket( Helper::writeRecursiveManchester( $rootURL, $usage['axiom'], $term->related ) ) )}
</li>
END;
	}
	$html .= '</ul></div>';
}

?>

<!-- Start Ontobee Layout: Term Use -->
<?php echo Helper::tidyHTML( $html, true ); ?>
<!-- End Ontobee Layout: Term Use -->