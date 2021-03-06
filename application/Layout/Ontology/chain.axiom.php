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
 * @file chain.axiom.php
 * @author Yongqun Oliver He
 * @author Zuoshuang Allen Xiang
 * @author Edison Ong
 * @since Oct 16, 2015
 * @comment 
 */

if ( !$this ) {
	exit( header( 'HTTP/1.0 403 Forbidden' ) );
}

$rootURL = SITEURL . "ontology/$ontology->ontology_abbrv?iri=";
$operations = $GLOBALS['ontology']['restriction']['operation'];
$types = $GLOBALS['ontology']['restriction']['type'];

$html = '';

if ( !empty ( $term->axiom['chain'] ) ) {	
	$html .=
<<<END
<div class="section-title">Property Chains</div>
<div class="section"><ul>
END;
	
	foreach ( $term->axiom['chain'] as $data ) {
		$axiom = Helper::writeRecursiveManchester( $rootURL, $data, $term->related );
		
		$html .=
<<<END
<li>$axiom subPropertyOf <a class="term" href="$rootURL{$GLOBALS['call_function']( Helper::encodeURL( $term->iri ) )}">$term->label</a></li>
END;
	}
	
	$html .=
<<<END
</ul></div>
END;
}

?>

<!-- Start Ontobee Layout: Chain Axiom -->
<?php echo Helper::tidyHTML( $html ); ?>
<!-- End Ontobee Layout: Chain Axiom -->