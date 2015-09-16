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
<div class="section-title">Annotations</div>
<div class="section">
<ul>
END;
			
			# Deprecated
			$deprecateIRI = $GLOBALS['ontology']['namespace']['owl'] . 'deprecated';
			if ( array_key_exists( $deprecateIRI, $annotations ) ) {
				$html .=
<<<END
<li><span class="label">deprecated</span></li>
END;
				unset( $annotations[$deprecateIRI] );
			}
			
			# Main Annotation
			$buffer = array();
			foreach ( $annotations as $iri => $annotation ) {
				$label = $annotation['label'];
				$values = $annotation['value'];
				if ( in_array( $iri, $GLOBALS['ontology']['annotation']['ignore'] ) ) {
					unset( $annotations[$iri] );
					continue;
				}
				if ( in_array( $label, $GLOBALS['ontology']['annotation']['main']['text'] ) ) {
					$text = join(', ', $values );
					$text = Helper::convertUTFToUnicode( $text );
					$buffer[$label] =
<<<END
<li><span class="label">$label:</span> <span class="value">
$text</span></li>
END;
					unset( $annotations[$iri] );
					continue;
				}
				if ( in_array( $label, $GLOBALS['ontology']['annotation']['main']['list'] ) ) {
					if ( $iri == 'http://www.w3.org/2000/01/rdf-schema#comment' || $label == 'comment' ) {
						$text = '';
						foreach ( $values as $value ) {
							$text .= Helper::writeMoreContent( $label, $value );
							if ( isset( $term ) ) {
								$text .= Helper::writeAnnotationRelated(
									$term->annotation_annotation,
									$GLOBALS['ontology']['namespace']['rdfs'] . 'comment',
									$value
								);
							}
						}
						$buffer[$label] = $text;
					} else {
						$text = '';
						foreach ( $values as $value ) {
							$text .= 
<<<END
<li><span class="label">$label:</span> <span class="value">
{$GLOBALS['call_function']( Helper::makeLink( $value ) )}</span></li>
END;
						}
						$buffer[$label] = $text;

					}
					unset( $annotations[$iri] );
					continue;
				}
			}
			ksort( $buffer );
			foreach ( $buffer as $label => $value ) {
				$html .= $value;
			}
			
			
			# Picture annotation
			$picture = array();
			foreach ( $annotations as $iri => $annotation ) {
				$label = $annotation['label'];
				$values = $annotation['value'];
				# depicted_by
				if ( $iri == '' || $label == 'depicted_by') {
					$text = '';
					foreach ( $values as $value) {
						$text .=
						<<<END
<li><span class="label">$label:
</span><br/>
END;
						foreach ( Helper::convertUTFToUnicode( $value, true ) as $item ) {
							$text .=
							<<<END
<span class="value" style="margin-right:10px"><a href="$item"><img src="$item" height="150" /></img></a></span>
END;
						}
						$text .=
						<<<END
</li>
END;
					}
					$picture[$label] = $text;
					unset( $annotations[$iri] );
					continue;
				}
			}
			
			$buffer = array();
			# Special annotation & Other Text
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
<<<END
<li><span class="label">definition editor:</span> <span class="value">
{$GLOBALS['call_function']( Helper::convertUTFToUnicode( join(', ', $defEditors ) ) )}
</span></li>
END;
					}
					unset( $annotations[$iri] );
					continue;
				}
				
				# editor note
				if ( $iri == 'http://purl.obolibrary.org/obo/IAO_0000116' || $label == 'editor note' ) {
					$text = '';
					foreach ( $values as $value ) {
						foreach ( Helper::convertUTFToUnicode( $value, true ) as $item ) {
							$text .= Helper::writeMoreContent( $label, $item );
						}
					}
					$buffer[$label] = $text;
					unset( $annotations[$iri] );
					continue;
				}
				
				# has PubMed association
				if ( $iri == '' || $label == 'has PubMed association') {
					$text = '';
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
						$text .= 
<<<END
<li><span class="label">$label:
</span> <span class="value">
{$GLOBALS['call_function']( Helper::makeLink( Helper::convertUTFToUnicode( join( '; ', $pmidArray ) ) ) )}
END;
						if ( $printFlag == 1 ) {
							$text .=
<<<END
; ... (Note: Only 50 PMIDs shown. See more from  web page source or RDF output.)
END;
						}
						$text .=
<<<END
</span></li>
END;
					}
					$buffer[$label] = $text;
					unset( $annotations[$iri] );
					continue;
				}
				
				# has GO association
				if ( $iri == '' || $label == 'has GO association') {
					$text = '';
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
						$text .=
<<<END
<li><span class="label">$label:
</span> <span class="value">
{$GLOBALS['call_function']( Helper::makeLink( Helper::convertUTFToUnicode( join( '; ', $pmidArray ) ) ) )}
END;
						if ( $printFlag == 1 ) {
							$text .= 
<<<END
; ... (Note: Only 20 GO IDs shown. See more from  web page source or RDF output.)"
END;
						}
						$text .=
<<<END
</span></li>
END;
					}
					$buffer[$label] = $text;
					unset( $annotations[$iri] );
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
							if ( preg_match( '/^(mailto:)?([A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4})$/', $value, $match ) ) {
								$show[] = "<a href=\"mailto:{$match[2]}\">{$match[2]}";
							} else {
								$show[] = Helper::makeLink( Helper::convertUTFToUnicode( $value ) );
							}
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
			
			# Compute textual annotation and tidy HTML
			ksort( $buffer );
			foreach ( $buffer as $label => $values ) {
				$html .= $values;
			}
			
			# Compute textual annotation and tidy HTML
			ksort( $picture );
			foreach ( $picture as $label => $values ) {
				$html .= $values;
			}
			
			$html .=
<<<END
</ul>
</div>
END;
			
			$html = Helper::tidyHTML( $html, true );
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

