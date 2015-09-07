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
 * @author Edison Ong
 * @since Sep 4, 2015
 * @comment 
 */

use View\Helper;

if ( !$this ) {
	exit(header('HTTP/1.0 403 Forbidden'));
}

class Annotation {
	public static function show( $annotations ) {
		
		if ( !empty ( $annotations ) ) {
			$html =
<<<END
<div style="font-weight:bold">Annotations</div>
<div style="background-color:#EAF1F2; border:#99CCFF 1px solid; margin-top:4px; margin-bottom:12px">
<ul>
END;
			
			# Deprecated
			$deprecateIRI = $GLOBALS['ontology']['namespace']['owl'] . 'deprecated';
			if ( array_key_exists( $deprecateIRI, $annotations ) ) {
				$html .= '<li><span style="color:#333333">deprecated</span></li>';
				unset( $annotations[$deprecateIRI] );
			}
			
			$buffer = array();
			
			# Main Annotation
			foreach ( $annotations as $iri => $annotation ) {
				$label = $annotation['label'];
				$values = $annotation['value'];
				if ( in_array( $iri, $GLOBALS['ontology']['annotation']['ignore'] ) ) {
					unset( $annotations[$iri] );
					continue;
				}
				if ( in_array( $label, $GLOBALS['ontology']['annotation']['main']['list'] ) ) {
					$text = join(', ', $values );
					$text = Helper::convertUTFToUnicode( $text );
					$buffer[$label] = "<li><span style=\"color:#333333\">$label</span>: <span style=\"color:#006600\">$text</span></li>";
					unset( $annotations[$iri] );
					continue;
				} else if ( in_array( $label, $GLOBALS['ontology']['annotation']['main']['text'] ) ) {
					if ( $label == 'comment' ) {
						foreach ( $values as $value ) {
							$text =
								'<li><span style="color:#333333">comment</span>: <span style="color:#006600">' .
								Helper::makeLink( $value ) .
								'</span>'
							;
							if ( isset( $term ) ) {
								foreach ( $term->annotation_annotation as $annotationRelated ) {
									if (
											$annotationRelated['annotatedProperty'] == $GLOBALS['ontology']['namespace']['rdfs'] . 'comment' &&
											$annotationRelated['annotatedTarget'] == $value
									) {
										$text .= '<span style="color:#14275D"> [';
										if ( isset( $annotationRelated['aaPropertyLabel'] ) ) {
											$text .= $annotationRelated['aaPropertyLabel'];
										} else {
											$text .= Helper::getShortTerm( $annotationRelated['aaProperty'] );
										}
										$text .= ': ' . Helper::convertUTFToUnicode( $annotationRelated['aaPropertyTarget']) . ']</span>';
									}
								}
							}
						}
						$buffer[$label] = $text;
					} else {
						foreach ( $values as $value ) {
							$text = Helper::convertUTFToUnicode( $value );
							$buffer[$label] = "<li><span style=\"color:#333333\">$label</span>: <span style=\"color:#006600\">$text</span></li>";
						}
					}
					unset( $annotations[$iri] );
					continue;
				}
			}
			
			# Annotation with other information
			if ( isset( $term->annotation_annotation ) ) {
				$annotationRelateds = array();
				foreach ( $term->annotation_annotation as $annotationRelated ) {
					$iri = $annotationRelated['annotatedProperty'];
					$value = $annotationRelated['annotatedTarget'];
					if ( array_key_exists( $iri, $annotations ) ) {
						$annotationRelateds[$iri][$value][] = $annotationRelated;
					}
				}
				foreach ( $annotationRelateds as $iri => $annotationRelated ) {
					$annotation = $annotations[iri];
					$label = $annotation['label'];
					$values = $annotation['value'];
					$show = array();
					foreach ( $values as $value ) {
						if ( !in_array( $value, $GLOBALS['ontology']['annotation']['ignore'] ) ) {
							$related = $annotationRelated[$value];
							$tmp = '<span style="color:#14275D"> [';
							if ( isset( $related['aaPropertyLabel'] ) ) {
								$tmp .= $related['aaPropertyLabel'];
							} else {
								$tmp .= Helper::getShortTerm( $related['aaProperty'] );
							}
							$tmp .=
								': ' .
								Helper::convertUTFToUnicode( $related['aaPropertyTarget'] ) .
								']</span>'
							;
							$show[] = Helper::convertUTFToUnicode( $value ) . $tmp;
						}
					}
					$text = join( '; ', $show );
					$buffer[$label] = "<li><span style=\"color:#333333\">$label</span>: <span style=\"color:#006600\">$text</span></li>";
					unset( $annotations[$iri] );
				}
			}
			
			# Special annotation & Rest
			foreach ( $annotations as $iri => $annotation ) {
				$label = $annotation['label'];
				$values = $annotation['value'];
				
				# term editor
				if ( $iri == 'http://purl.obolibrary.org/obo/IAO_0000117' || $label == 'term editor' ) {
					$defEditors = array();
					foreach ( $values as $value ) {
						$defEditors[] = preg_replace( '/^PERSON:/i', '', $value );
					}
					if ( !empty( $defEditors ) ) {
						$buffer[$label] =
							'<li><span style="color:#333333">definition editor</span>: <span style="color:#006600">' .
							Helper::convertUTFToUnicode( join(', ', $defEditors ) ) .
							'</span></li>'
						;
					}
					continue;
				}
				
				# editor note
				if ( $iri == 'http://purl.obolibrary.org/obo/IAO_0000116' || $label == 'editor note' ) {
					$show = array();
					foreach ( $values as $value ) {
						foreach ( Helper::convertUTFToUnicode( $value, true ) as $item ) {
							$show[] =
								'<li><span style="color:#333333">' .
								$label .
								'</span>: <span style="color:#006600">' .
								Helper::makeLink( Helper::convertUTFToUnicode( $item ) ) .
								'</span></li>'
							;
						}
					}
					$buffer[$label] = $show;
					continue;
				}
				
				# has PubMed association
				if ( $iri == '' || $label == 'has PubMed association') {
					$show = array();
					foreach ( $values as $value ) {
						$pmidArray = array();
						$pmidCount = 0;
						$printFlag = 0;
						$pmidArray = explode( ';', Helper::convertUTFToUnicode( $value ) );
						$pmidCount = sizeof( $pmidArray );
						$pmidArray = array_slice( $pmidArray, 0, 50 );
						if ($pmidCount > 50) {
							$printFlag = 1;
						}
						$tmp = "<li><span style=\"color:#333333\">$label</span>: <span style=\"color:#006600\">";
						$tmp .= join( '; ', $pmidArray );
						if ( $printFlag == 1 ) {
							$tmp .= "; ... (Note: Only 50 PMIDs shown. See more from  web page source or RDF output.)</span></li>";
						}
						$show[] = $tmp;
					}
					$buffer[$label] = $show;
					continue;
				}
				
				# has GO association
				if ( $iri == '' || $label == 'has GO association') {
					$show = array();
					foreach ( $values as $value ) {
						$pmidArray = array();
						$pmidCount = 0;
						$printFlag = 0;
						$pmidArray = explode( ';', Helper::convertUTFToUnicode( $value ) );
						$pmidCount = sizeof( $pmidArray );
						$pmidArray = array_slice($pmidArray, 0, 20);
						if ($pmidCount > 20) {
							$printFlag = 1;
						}
						$tmp = "<li><span style=\"color:#333333\">$label</span>: <span style=\"color:#006600\">";
						$tmp .= join( '; ', $pmidArray );
						if ( $printFlag == 1 ) {
							$tmp .= "; ... (Note: Only 20 GO IDs shown. See more from  web page source or RDF output.)</span></li>";
						}
						$show[] = $tmp;
					}
					$buffer[$label] = $show;
					continue;
				}
				
				# depicted_by
				if ( $iri == '' || $label == 'depicted_by') {
					$show = array();
					foreach ( $values as $value) {
						$tmp = "<li><span style=\"color:#333333\">$label:</span><br/>";
						foreach ( Helper::convertUTFToUnicode( $value, true ) as $item ) {
							$tmp .= "<span style=\"margin-right:10px\"><a href=\"$item\"><img src=\"$item\" height=\"150\"/></a></span>";
						}
						$tmp .= '</li>';
					}
					$buffer[$label] = $show;
					continue;
				}
				
				# Rest
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
						if ( !in_array( $value, $GLOBALS['ontology']['annotation']['ignore'] ) ) {
							$show[] = Helper::convertUTFToUnicode( $value );
						}
					}
					$text = join( '; ', $show );
					$buffer[$label] = "<li><span style=\"color:#333333\">$label</span>: <span style=\"color:#006600\">$text</span></li>";
					continue;
				}
			}
			
			ksort( $buffer );
			foreach ( $buffer as $label => $values ) {
				if ( is_array( $values ) ) {
					$html .= join( PHP_EOL, array_values( $values ) );
				} else {
					$html .= $values;
				}
			}
			
			
			$html .=
<<<END
</ul>
</div>
END;
			
			$html = Helper::tidyHTML( $html );
		} else {
			$html = '';
		}
		
		return $html;
	}
}

?>

<!-- Ontobee Annotation Display Start -->
<?php echo Annotation::show( $annotations ); ?>
<!-- Ontobee Annotation Display Start -->

