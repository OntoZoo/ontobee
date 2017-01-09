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
 * @file DisplayHelper.php
 * @author Zuoshuang Allen Xiang
 * @author Edison Ong
 * @since Sep 4, 2015
 * @comment 
 */

class Helper {

	public static function tidyHTML( $html, $outputXML = false ) {
		if ( extension_loaded( 'tidy' ) ) {
			$tidy = new \tidy();
			$cleanHTML = $tidy->repairString( $html, array(
					'indent' => true,
					'indent-spaces' => 2,
					'show-body-only' => true,
					'merge-divs' => false,
					'output-xml' => $outputXML,
					'input-encoding' => 'utf8',
					'output-encoding' => 'utf8',
					'preserve-entities' => true,
			) );
			if ( $outputXML ) {
				preg_match( '/<body>(.*)<\/body>/s', $cleanHTML, $match );
				return $match[1];
			} else {
				return $cleanHTML;
			}
		} else {
			return $html;
		}
	}

	public static function getShortTerm( $term ) {
		if ( preg_match( '/^http/', $term ) || preg_match( '/^ftp:/', $term ) ) {
			$tmp_array = preg_split( '/[#\/]/', $term );
			return( self::convertUTFToUnicode( array_pop( $tmp_array ) ) );
		}
		else {
			return(self::convertUTFToUnicode( $term ) );
		}
	}
	
	public static function makeLink( $input ) {
		$link = preg_replace( '/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/', '<a href="$0">$0</a>', $input );
		return $link;
	}

	public static function convertToIRI( $ontology, $input ) {
		if ( preg_match( '/http:\/\/.+/', $input ) ) {
			$term = $input;
		} else if ( preg_match( '/([a-zA-Z]+)[:_]([a-zA-Z]*)[:_]?(\d+)/', $input, $match ) ) {
			if ( $match[2] == '' ) {
				$term = $match[1] . '_' . $match[3];
			} else {
				$term = $match[2] . '_' . $match[3];
			}
		} else {
			return null;
		}
		return $ontology->convertToIRI( $term );
	}

	public static function decodeURL( $URL ) {
		return( urldecode( $URL ) );
	}

	public static function encodeURL( $URL) {
		return( preg_replace( '/#/', '%23', $URL ) );

	}
	
	public static function getIRIPrefix( $iri ) {
		$prefix = null;
		if ( preg_match( '/\/([A-Za-z\.\-_]+)#[a-zA-Z_0-9]+/', $iri, $match ) ) {
			$prefix = $match[1];
		} else if ( preg_match( '/\/([A-Z][A-Za-z]+)_[-a-zA-Z_0-9]+/', $iri, $match ) ) {
			$prefix = $match[1];
		} else if ( preg_match( '/\/([a-z]+)_[0-9]+/', $iri, $match ) ) {
			$prefix = $match[1];
		}
		return $prefix;
	}

	#TODO: Reformat function
	public static function convertUTFToUnicode( $input, $array = False ) {
		$bit1  = pow( 64, 0 );
		$bit2  = pow( 64, 1 );
		$bit3  = pow( 64, 2 );
		$bit4  = pow( 64, 3 );
		$bit5  = pow( 64, 4 );
		$bit6  = pow( 64, 5 );
		$value = '';
		$val   = array();
		for( $i=0; $i< strlen( $input ); $i++){
			$ints = ord ( $input[$i] );
			$z = ord ( $input[$i] );
			if( $ints >= 0 && $ints <= 127 ){
				// 1 bit
				//$value .= '&#'.($z * $bit1).';';
				$value .= htmlentities($input[$i]);
				$val[]  = $value;
			}
			if( $ints >= 192 && $ints <= 223 ){
				$y = ord ( $input[$i+1] ) - 128;
				// 2 bit
				$value .= '&#'.(($z-192) * $bit2 + $y * $bit1).';';
				$val[]  = $value;
			}
			if( $ints >= 224 && $ints <= 239 ){
				$y = ord ( $input[$i+1] ) - 128;
				$x = ord ( $input[$i+2] ) - 128;
				// 3 bit
				$value .= '&#'.(($z-224) * $bit3 + $y * $bit2 + $x * $bit1).';';
				$val[]  = $value;
			}
			if( $ints >= 240 && $ints <= 247 ){
				$y = ord ( $input[$i+1] ) - 128;
				$x = ord ( $input[$i+2] ) - 128;
				$w = ord ( $input[$i+3] ) - 128;
				// 4 bit
				$value .= '&#'.(($z-240) * $bit4 + $y * $bit3 + $x * $bit2 + $w * $bit1).';';
				$val[]  = $value;
			}
			if( $ints >= 248 && $ints <= 251 ){
				$y = ord ( $input[$i+1] ) - 128;
				$x = ord ( $input[$i+2] ) - 128;
				$w = ord ( $input[$i+3] ) - 128;
				$v = ord ( $input[$i+4] ) - 128;
				// 5 bit
				$value .= '&#'.(($z-248) * $bit5 + $y * $bit4 + $x * $bit3 + $w * $bit2 + $v * $bit1).';';
				$val[]  = $value;
			}
			if( $ints == 252 && $ints == 253 ){
				$y = ord ( $input[$i+1] ) - 128;
				$x = ord ( $input[$i+2] ) - 128;
				$w = ord ( $input[$i+3] ) - 128;
				$v = ord ( $input[$i+4] ) - 128;
				$u = ord ( $input[$i+5] ) - 128;
				// 6 bit
				$value .= '&#'.(($z-252) * $bit6 + $y * $bit5 + $x * $bit4 + $w * $bit3 + $v * $bit2 + $u * $bit1).';';
				$val[]  = $value;
			}
			if( $ints == 254 || $ints == 255 ){
				echo 'Wrong Result!<br>';
			}
		}
		if( $array === False ){
			$value = str_replace('~', ';  ', $value);
			$unicode = $value;
			return $unicode;
		}
		if($array === True ){
			$val     = str_replace('&#', '', $value);
			$val     = explode('~', $val);
			$len = count($val);
			//unset($val[$len-1]);
			return $unicode = $val;
		}
	}
	
	public static function writeAnnotationRelated( $annotationRelateds, $property, $target ) {
		$html = '';
		foreach ( $annotationRelateds as $annotationRelated ) {
			if (
					$annotationRelated['annotatedProperty'] == $property &&
					$annotationRelated['annotatedTarget'] == $target
			) {
				$html .=
				<<<END
<span class="value"> [
END;
				if ( isset( $annotationRelated['aaPropertyLabel'] ) ) {
					$html .= $annotationRelated['aaPropertyLabel'];
				} else {
					$html .= self::getShortTerm( $annotationRelated['aaProperty'] );
				}
				$html .=
				<<<END
: {$GLOBALS['call_function']( self::convertUTFToUnicode( $annotationRelated['aaPropertyTarget'] ) )}]</span>
END;
			}
		}
		return $html;
	}
	
	public static function writeMoreContent( $label, $value ) {
		#$tmp = preg_replace( '/[\n\r]/', ' ', $value );
		#$tmp = wordwrap( $tmp, 200, "\n" );
		#$tokens = preg_split( '/\n/', $tmp );
		$tokens = array( $value );
		if ( sizeof( $tokens ) == 1 ) {
			$text =
			<<<END
<li><span class="label">$label:</span> <span class="value more">
{$GLOBALS['call_function']( Helper::makeLink( $value ) )}</span></li>
END;
		} else {
			$text =
			<<<END
<li><span class="label">$label:</span> <span class="value more">
{$GLOBALS['call_function']( Helper::makeLink( array_shift( $tokens ) ) )}
<span class="more-skip"> ... </span>
<span class="more-content" style="display:none">{$GLOBALS['call_function']( self::makeLink( join( ' ', $tokens ) ) )}</span>
<span class="more-link" style="display:inline-block;white-space:normal;cursor:hand"> Read More </span>
</span></li>
END;
		}
		return $text;
	}
	
	public static function trimBracket( $text ) {
		if ( preg_match( '/^\s*\(/', $text ) ) {
			$text = substr( $text, 1 );
			$text = substr( $text, 0, -1 );
		}
		return $text;
	}
	
	private static function makeManchesterLink( $rootURL, $term, $mapping ) {
		if ( array_key_exists( $term, $mapping ) && $mapping[$term]->label != '' ) {
			$label = $mapping[$term]->label;
		} else {
			$label =  Helper::getShortTerm( $term );
		}
		$html = '<a class="term" oncontextmenu="return false;" href="';
		$html .= $rootURL . self::encodeURL( $term );
		$html .= '">';
		$html .= self::convertUTFToUnicode( $label );
		$html .= '</a>';
		return $html;
	}
	
	public static function writeRecursiveManchester( $rootURL, $data, $mapping = array() ) {
		if ( !is_array( $data ) ) {
			$manchester = self::makeManchesterLink( $rootURL, $data, $mapping );
		} else if ( array_key_exists( 'restrictionValue', $data ) ) {
			$manchester = '';
			$operations = $GLOBALS['ontology']['restriction']['operation'];
			$types = $GLOBALS['ontology']['restriction']['type'];
			$value = $data['restrictionValue'];
			if ( array_key_exists( 'restrictionType', $data ) ) {
				$type = $data['restrictionType'];
				
				if ( in_array( $type, array_keys( $types ) ) ) {
					$property = self::makeManchesterLink( $rootURL, $value[0], $mapping );
					$manchester .= "$property $type ";
					if ( !is_array( $value[1] ) ) {
						$manchester .= self::makeManchesterLink( $rootURL, $value[1], $mapping );
					} else {
						$manchester .= self::writeRecursiveManchester( $rootURL, $value[1], $mapping );
					}
				} else if ( in_array( $type, array_keys( $operations ) ) ) {
					if ( sizeof( $value ) > 1 ) {
						$manchester .= '(';
					}
					foreach ( $value as $index => $node ) {
						if ( $type == 'not' ) {
							if ( $index != 0 ) {
								$manchester .= " $type ";
							} else {
								$manchester .= "$type ";
							}
						} else {
							if ( $index != 0 ) {
								$manchester .= " $type ";
							}
						}
						if ( !is_array( $node ) ) {
							$manchester .= self::makeManchesterLink( $rootURL, $node, $mapping );
						} else {
							$manchester .= '(' . self::writeRecursiveManchester( $rootURL, $node, $mapping ) . ')';
						}
					}
				
				if ( sizeof( $value ) > 1 ) {
						$manchester .= ')';
					}
				}
			} else if ( sizeof( $value ) > 1 ){
				$type = 'o';
				$manchester .= '(';
				foreach ( $value as $index => $node ) {
					if ( $index != 0 ) {
						$manchester .= " $type ";
					}
					if ( !is_array( $node ) ) {
						$manchester .= self::makeManchesterLink( $rootURL, $node, $mapping );
					} else {
						$manchester .= '(' . self::writeRecursiveManchester( $rootURL, $node, $mapping ) . ')';
					}
				}
				$manchester .= ')';
			}
		} else {
			$manchester = '';
		}
		
		return $manchester;
	}
}



?>