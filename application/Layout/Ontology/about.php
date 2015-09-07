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
	<li>URI: <a href="$ontology->ontology_url">$ontology->ontology_url</a></li>
END;
	if ( $ontology->foundry != '' ) {
		$html .= "<li>OBO Foundry: $ontology->foundry</li>";
	}
 	if ( $ontology->download != '' ) {
 		$html .= "<li>Download: <a href=\"$ontology->download\">$ontology->download</a></li>";
 	}
 	if ( $ontology->alternative_download != '' ) {
 	 	$html .= "<li>Alternative Download: <a href=\"$ontology->alternative_download\">$ontology->alternative_download</a></li>";
 	}
 	if ( $ontology->source != '' ) {
		$html .= "<li>Source: <a href=\"$ontology->source\">$ontology->source</a></li>";
	}
 	if ( $ontology->home != '' ) {
		$tokens = preg_split( '/\|/', $ontology->home );
		$html .= '<li>Home: <a href="';
		if ( sizeof( $tokens ) == 2 ) {
			$html .= $tokens[1];
		} else {
			$html .= $tokens[0];
		}
		$html .= '">' . $tokens[0] . '</a></li>';
	}
 	if ( $ontology->documentation != '' ) {
		$tokens = preg_split( '/[|\t]/', $ontology->documentation );
		$html .= '<li>Documentation: <a href="';
		if ( sizeof( $tokens ) == 2 ) {
			$html .= $tokens[1];
		} else {
			$html .= $tokens[0];
		}
		$html .= '">' . $tokens[0] . '</a></li>';
	}
 	if ( $ontology->contact != '' ) {
		$tokens = preg_split( '/[|\t]/', $ontology->contact );
		$html .= '<li>Contact: <a href="mailto:' . $tokens[1] . '@' . $tokens[2] . '">' . $tokens[0] . '</a></li>';
 	}
 	if ( $ontology->help != '' ) {
		$tokens=preg_split( '/[|\t]/', $ontology->help );
		$html .= '<li>Help: <a href="mailto:' . $tokens[1] . '@' . $tokens[2] . '">' . $tokens[0] . '</a></li>';
 	}
 	if ( $ontology->description != '' ) {
		$html .= "<li>Description: $ontology->description</li>";
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
	'<li style=\"font-weight:bold; font-size:120%">Term IRI: <a href="' .
	Helper::encodeURL( $term->iri ) . 
	"\">$term->iri</a></li>";
	$defIRIs = array( 
		$GLOBALS['ontology']['namespace']['oboInOwl'] . 'Definition',
		$GLOBALS['ontology']['namespace']['obo'] . 'IAO_0000115',
	);
	foreach ( $defIRIs as $defIRI ) {
		if ( isset( $term->describe[$defIRI] ) ) {
			foreach ($term->describe[$defIRI] as $object ) {
				$html .=
					'<li><span style="color:#333333">definition</span>: <span style="color:#006600">' .
					Helper::convertUTFToUnicode( $object['value'] ) .
					'</span>';
				foreach ( $term->annotation_annotation as $annotationRelated ) {
					if ( 
						$annotationRelated['annotatedProperty'] == $defIRI && 
						$annotationRelated['annotatedTarget'] == $object['value']
					) {
						$html .= '<span style="color:#14275D"> [';
						if ( isset( $annotationRelated['aaPropertyLabel'] ) ) {
							$html .= $annotationRelated['aaPropertyLabel'];
						} else {
							$html .= Helper::getShortTerm( $annotationRelated['aaProperty']);
						}
						$html .= Helper::convertUTFToUnicode( $annotationRelated['aaPropertyTarget'] ) . ']</span>';
					}
				}
				$html .= '</li>';
			}
		}
	}
}

echo Helper::tidyHTML( $html );

?>




