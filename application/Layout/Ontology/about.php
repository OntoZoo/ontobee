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
<p><b>Ontology:</b> $ontology->ontology_abbrv</p>
<div style="background-color:#EAF1F2; border:#99CCFF 1px solid; margin-top:4px; margin-bottom:12px">
<ul>	
<li>IRI: <a href="$ontology->ontology_url">$ontology->ontology_url</a></li>
END;
	if ( $ontology->foundry != '' ) {
		$html .= 
<<<END
<li>OBO Foundry: $ontology->foundry</li>
END;
	}
 	if ( $ontology->download != '' ) {
 		$html .= 
<<<END
<li>Download: <a href=\"$ontology->download\">$ontology->download</a></li>
END;
 	}
 	if ( $ontology->alternative_download != '' ) {
 	 	$html .= 
<<<END
 	<li>Alternative Download: <a href=\"$ontology->alternative_download\">$ontology->alternative_download</a></li>
END;
 	}
 	if ( $ontology->source != '' ) {
		$html .= 
<<<END
 	<li>Source: <a href=\"$ontology->source\">$ontology->source</a></li>
END;
	}
 	if ( $ontology->home != '' ) {
		$tokens = preg_split( '/\|/', $ontology->home );
		$html .= 
<<<END
<li>Home: <a href="
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
<li>Documentation: <a href="
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
<li>Contact: <a href="mailto:{$tokens[1]}@{$tokens[2]}">{$tokens[0]}</a></li>
END;
 	}
 	if ( $ontology->help != '' ) {
		$tokens = preg_split( '/[|\t]/', $ontology->help );
		$html .=
<<<END
<li>Help: <a href="mailto:{$tokens[1]}@{$tokens[2]}">{$tokens[0]}</a></li>
END;
 	}
 	if ( $ontology->description != '' ) {
		$html .=
<<<END
<li>Description: $ontology->description</li>
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
<p>
<b>$term->type: $term->label</b>
</p>
<ul>
END;
	$html .=
<<<END
<li style="font-weight:bold; font-size:120%">Term IRI: <a href="$term->iri">$term->iri</a></li>
END;
	foreach ( $GLOBALS['ontology']['definition']['priority'] as $defIRI ) {
		if ( isset( $term->describe[$defIRI] ) ) {
			foreach ($term->describe[$defIRI] as $object ) {
				$html .=
<<<END
<li><span style="color:#333333">definition</span>: <span style="color:#006600">
{$GLOBALS['call_function']( Helper::convertUTFToUnicode( $object['value'] ) ) }
</span>
END;
				foreach ( $term->annotation_annotation as $annotationRelated ) {
					if ( 
						$annotationRelated['annotatedProperty'] == $defIRI && 
						$annotationRelated['annotatedTarget'] == $object['value']
					) {
						$html .=
<<<END
<span style="color:#14275D"> [
END;
						if ( isset( $annotationRelated['aaPropertyLabel'] ) ) {
							$html .= $annotationRelated['aaPropertyLabel'];
						} else {
							$html .= Helper::getShortTerm( $annotationRelated['aaProperty'] );
						}
						$html .= 
<<<END
{$GLOBALS['call_function']( Helper::convertUTFToUnicode( $annotationRelated['aaPropertyTarget'] ) )}]</span>
END;
					}
				}
				$html .= 
<<<END
</li>
END;
			}
		}
	}
}

echo Helper::tidyHTML( $html );

?>




