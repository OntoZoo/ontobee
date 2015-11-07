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
 * @file instance.php
 * @author Yongqun Oliver He
 * @author Zuoshuang Allen Xiang
 * @author Edison Ong
 * @since Oct 1, 2015
 * @comment 
 */

if ( !$this ) {
	exit( header( 'HTTP/1.0 403 Forbidden' ) );
}

$site = SITEURL;

$html = '';

if ( !empty( $term->instance ) ) {
	$html .=
<<<END
<div class="section-title">Instances</div>
<div class="section">
<ul>
END;
	
	foreach ( $term->instance as $instanceIRI => $instanceLabel ) {
		$html .=
<<<END
<li><a class="term" oncontextmenu="return false;" href="{$site}ontology/$ontology->ontology_abbrv?iri={$GLOBALS['call_function']( Helper::encodeURL( $instanceIRI ) )}">
{$GLOBALS['call_function']( Helper::convertUTFToUnicode( $instanceLabel ) )}</a></li>
END;
	}
	$html .=
<<<END
</ul>
</div>
END;
}

?>

<!-- Start Ontobee Layout: Instance -->
<?php echo Helper::tidyHTML( $html ); ?>
<!-- End Ontobee Layout: Instance -->