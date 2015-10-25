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
 * @file annotation.php
 * @author Yongqun Oliver He
 * @author Zuoshuang Allen Xiang
 * @author Edison Ong
 * @since Sep 4, 2015
 * @comment 
 */

if ( !$this ) {
	exit( header( 'HTTP/1.0 403 Forbidden' ) );
}

$html = '';

if ( is_null( $termIRI ) ) {
	$annotations = $ontology->annotation;
} else {
	$annotations = $term->annotation;
}

if ( !empty ( $annotations ) ) {
	# Deprecated
	if ( !is_null( $termIRI ) && $term->deprecate ) {
		$html .=
<<<END
<li><span class="label">deprecated</span></li>
END;
	}
	
	$before = array();
	# Hook: BeforeAnnotationHTML
	Hook::run( 'BeforeAnnotationHTML', array( &$annotations, &$before ) );
	
	$after = array();
	# Hook: AfterAnnotationHTML
	Hook::run( 'AfterAnnotationHTML', array( &$annotations, &$after ) );
	
	$buffer = array();
	foreach ( $annotations as $iri => $annotation ) {
		$label = $annotation['label'];
		$values = $annotation['value'];
		
		if ( !in_array( $iri, array(
				$GLOBALS['ontology']['namespace']['rdfs'] . 'label',
				$GLOBALS['ontology']['namespace']['rdf'] . 'type',
				$GLOBALS['ontology']['namespace']['oboInOwl'] . 'Definition',
				$GLOBALS['ontology']['namespace']['owl'] . 'disjointWith',
				$GLOBALS['ontology']['namespace']['rdfs'] . 'subClassOf',
				$GLOBALS['ontology']['namespace']['owl'] . 'equivalentClass',
				$GLOBALS['ontology']['namespace']['obo']. 'IAO_0000115',
				$GLOBALS['ontology']['namespace']['obo']. 'IAO_0000111',
		) ) ) {
			$show = array();
			foreach ( $values as $value ) {
				if ( preg_match( '/^(mailto:)?([A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4})$/', $value, $match ) ) {
					$show[] = "<a href=\"mailto:{$match[2]}\">{$match[2]}";
				} else {
					$show[] = Helper::makeLink( Helper::convertUTFToUnicode( $value ) );
				}
			}
			$text = join( '; ', $show );
			$buffer[$label] = 
<<<END
<li><span class="label">$label:
</span> <span class="value">$text</span></li>
END;
			unset( $annotations[$iri] );
			continue;
		}
	}
	
	# Render HTML from buffer
	ksort( $before );
	foreach ( $before as $label => $value ) {
		$html .= $value;
	}
	ksort( $buffer );
	foreach ( $buffer as $label => $value ) {
		$html .= $value;
	}
	ksort( $after );
	foreach ( $after as $label => $value ) {
		$html .= $value;
	}
}

if ( $html != '' ) {
	$html =
<<<END
<div class="section-title">Annotations</div>
<div class="section">
$html
</div>
END;
}

?>

<!-- Ontobee Annotation Display Start -->
<?php echo Helper::tidyHTML( $html, true ); ?>
<!-- Ontobee Annotation Display End -->