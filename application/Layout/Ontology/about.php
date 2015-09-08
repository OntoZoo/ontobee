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
 * @file general.php
 * @author Edison Ong
 * @since Sep 4, 2015
 * @comment 
 */

use View\Helper;

if ( !$this ) {
	exit(header('HTTP/1.0 403 Forbidden'));
}

$site = SITEURL;

$html = '';

if ( is_null( $termIRI ) ) {
	$html .=
<<<END
<p class="section-title">Ontology: <span class="section-title-value">$ontology->ontology_abbrv</span></p>
<div class="section">
<ul>	
<li><span class="label">IRI:</span> <a href="$ontology->ontology_url">$ontology->ontology_url</a></li>
END;
	if ( $ontology->foundry != '' ) {
		$html .= 
<<<END
<li><span class="label">OBO Foundry:</span> $ontology->foundry</li>
END;
	}
 	if ( $ontology->download != '' ) {
 		$html .= 
<<<END
<li><span class="label">Download:</span> <a href=\"$ontology->download\">$ontology->download</a></li>
END;
 	}
 	if ( $ontology->alternative_download != '' ) {
 	 	$html .= 
<<<END
<li><span class="label">Alternative Download:</span> <a href=\"$ontology->alternative_download\">$ontology->alternative_download</a></li>
END;
 	}
 	if ( $ontology->source != '' ) {
		$html .= 
<<<END
<li><span class="label">Source:</span> <a href=\"$ontology->source\">$ontology->source</a></li>
END;
	}
 	if ( $ontology->home != '' ) {
		$tokens = preg_split( '/\|/', $ontology->home );
		$html .= 
<<<END
<li><span class="label">Home:</span> <a href="
END;
		if ( sizeof( $tokens ) == 2 ) {
			$html .= $tokens[1];
		} else {
			$html .= $tokens[0];
		}
		$html .= 
<<<END
">{$tokens[0]}</a></li>
END;
	}
 	if ( $ontology->documentation != '' ) {
		$tokens = preg_split( '/[|\t]/', $ontology->documentation );
		$html .=
<<<END
<li><span class="label">Documentation:</span> <a href="
END;
		if ( sizeof( $tokens ) == 2 ) {
			$html .= $tokens[1];
		} else {
			$html .= $tokens[0];
		}
		$html .= 
<<<END
">{$tokens[0]}</a></li>
END;
	}
 	if ( $ontology->contact != '' ) {
		$tokens = preg_split( '/[|\t]/', $ontology->contact );
		$html .= 
<<<END
<li><span class="label">Contact:</span> <a href="mailto:{$tokens[1]}@{$tokens[2]}">{$tokens[0]}</a></li>
END;
 	}
 	if ( $ontology->help != '' ) {
		$tokens = preg_split( '/[|\t]/', $ontology->help );
		if ( sizeof( $tokens ) < 3 ) {
			$html .=
<<<END
<li><span class="-label">Help:</span> <a href="$ontology->help">$ontology->help</a></li>
END;
		} else {
			$html .=
<<<END
<li><span class="label">Help:</span> <a href="mailto:{$tokens[1]}@{$tokens[2]}">{$tokens[0]}</a></li>
END;
		}
 	}
 	if ( $ontology->description != '' ) {
		$html .=
<<<END
<li><span class="label">Description:</span> $ontology->description</li>
END;
 	}
 	$html .= 
<<<END
</ul>
</div>
END;
 	
} else {
	$html .=
<<<END
<p class="section-title">$term->type: <span class="section-title-value">$term->label</span></p>
END;
	$html .=
<<<END
<div class="iri">Term IRI: <a href="$term->iri">$term->iri</a></div>
END;
	foreach ( $GLOBALS['ontology']['definition']['priority'] as $defIRI ) {
		if ( isset( $term->describe[$defIRI] ) ) {
			foreach ($term->describe[$defIRI] as $object ) {
				$html .=
<<<END
<div class="def"><span class="label">Definition:</span> 
{$GLOBALS['call_function']( ucfirst( Helper::convertUTFToUnicode( $object['value'] ) ) )}
END;
				$html .= Helper::writeAnnotationRelated( $term->annotation_annotation, $defIRI, $object['value'] );
				break;
			}
			break;
		}
	}
}

echo Helper::tidyHTML( $html );

?>




