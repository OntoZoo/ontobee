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
 * @file hierarchy.php
 * @author Yongqun Oliver He
 * @author Zuoshuang Allen Xiang
 * @author Edison Ong
 * @since Sep 5, 2015
 * @comment 
 */

if ( !$this ) {
	exit( header( 'HTTP/1.0 403 Forbidden' ) );
}

class Hierarchy {

	public static function show( $hierarchyTitle, $ontology, $term, $hierarchy ) {
		$rootURL = SITEURL . "ontology/$ontology->ontology_abbrv?iri=";
		
		$path = $hierarchy['path'];
		$subTerms = $hierarchy['subTerms'];
		$sibTerms = $hierarchy['sibTerms'];
		$hasChild = $hierarchy['hasChild'];
		
		$html = 
<<<END
<div class="hierarchy">
<div class="section-title">$hierarchyTitle</div>
<div class="section main">
END;
		
		$top = Helper::getShortTerm( array_search( $term->type, $GLOBALS['ontology']['top_level_term']) );
		
		if ( $top != '' ) {
			$html .= "<ul class=\"top\"><li class=\"top-term\">$top<ul>";
		} else {
			$html .= '<ul class="top"><li class="top-term">Top<ul>';
		}
		if ( !empty( $path ) ) {
			$html .= self::supTermHeader( $rootURL, $path );

			if( !empty( $sibTerms ) ) {
				end($path);
				$moreURL = 
					SITEURL .
					"ontology/catalog/$ontology->ontology_abbrv?iri={$GLOBALS['call_function']( key($path) )}";
				$html .= self::sibTermSection( $rootURL, $moreURL, $sibTerms, $hasChild );
			}

			$html .= self::curTermHeader( $rootURL, $term, $hasChild );

			if ( !empty( $subTerms ) ) {
				$moreURL = SITEURL . "ontology/catalog/$ontology->ontology_abbrv?iri=$term->iri";
				$html .= self::subTermSection( $rootURL, $moreURL, $subTerms, $hasChild );
			}

			$html .= self::curTermBottom();

			$html .= self::supTermBottom( $path );
		} else {
			$html .= self::curTermHeader( $rootURL, $term, $hasChild );
			
			if( !empty( $sibTerms ) ) {
				end($path);
				$moreURL =
				SITEURL .
				"ontology/catalog/$ontology->ontology_abbrv?iri={$GLOBALS['call_function']( key($path) )}";
				$html .= self::sibTermSection( $rootURL, $moreURL, $sibTerms, $hasChild );
			}
			
			if ( !empty( $subTerms ) ) {
				$moreURL = SITEURL . "ontology/catalog/$ontology->ontology_abbrv?iri=$term->iri";
				$html .= self::subTermSection( $rootURL, $moreURL, $subTerms, $hasChild );
			}

			$html .= self::curTermBottom();
		}
		
		$html .= '</ul></li></ul></div></div></div>';

		return $html;
	}

	/**
	 * Static function to generate Entity HTML with specified Class
	 *
	 * @param $class
	 * @param $link
	 * @param $label
	 * @return $html
	 */
	private static function entitiy( $class, $link, $label ) {
		$html = '<a class="term ';
		$html .= $class;
		$html .= '" oncontextmenu="return false;" href="';
		$html .= "{$GLOBALS['call_function']( Helper::encodeURL( $link ) )}";
		$html .= '">';
		$html .= Helper::convertUTFToUnicode( $label );
		$html .= '</a>';
		return $html;
	}

	/**
	 * Static function to generate More/Less HTML with specified class
	 *
	 * @param $class
	 * @return $html
	 */
	private static function more( $moreURL, $class ) {
		#TODO: Modify add more to HTML checkbox that trigger more terms to display or not
		$html = '<li>' .
			'<a id="hierarchy-' .
			$class .
			'-more" class="' .
			$class .
			'-more" href="' .
			$moreURL .
			'">more...</a>' .
			'</li">';
		return $html;
	}

	/**
	 * Static function to generate Super-Term Header HTML
	 *
	 * @param $ontAbbr
	 * @param $rootURL
	 * @param $path
	 * @return $html
	 */
	private static function supTermHeader( $rootURL, $path ) {
		$html = '<!-- Hierarchy Super Term Opening -->';
		foreach ( $path as $supTermIRI => $supTermLabel ) {
			if ( $supTermLabel == '' ) {
				$supTermLabel = Helper::getShortTerm( $supTermIRI );
			}
			if ( $supTermIRI != 'http://www.w3.org/2002/07/owl#Thing' ) {
				$html .= '<li>+ ';
				$html .= self::entitiy(
						'sup-term',
						$rootURL . $supTermIRI,
						$supTermLabel
				);
				$html .= '<ul>';
			}
		}
		return $html;
	}

	/**
	 * Static function to generate Sibling-Term Section HTML
	 *
	 * @param $ontAbbr
	 * @param $rootURL
	 * @param $sibTerms
	 * @param $hasChild
	 * @return $html
	 */
	private static function sibTermSection( $rootURL, $moreURL, $sibTerms, $hasChild ) {
		$sibHasChildMax = $GLOBALS['ontology']['hierarchy']['sibhasmax'];
		$sibNoChildMax =$GLOBALS['ontology']['hierarchy']['sibnomax'];

		$html = '<!-- Hierarchy Sibling Term Opening -->';
		$noChildCount = 0;
		$hasChildCount = 0;
		$showMore = false;
		foreach ( $sibTerms as $sibTermIRI => $sibTermLabel ) {
			if ( ( $hasChildCount > $sibHasChildMax ) && ( $noChildCount > $sibNoChildMax ) ) {
				break;
			}
			if ( $sibTermLabel == '' ) {
				$sibTermLabel = Helper::getShortTerm( $sibTermIRI );
			}
			if ( $hasChild[$sibTermIRI] && ( $hasChildCount <= $sibHasChildMax) ) {
				$hasChildCount++;
				$html .= '<li>+ ';
				$html .= self::entitiy(
						'sup-term',
						$rootURL . $sibTermIRI,
						$sibTermLabel
				);
				$html .= '</li>';
			} elseif ( $hasChildCount > $sibHasChildMax ) {
				$showMore = true;
			}
			if ( !$hasChild[$sibTermIRI] && ( $noChildCount <= $sibNoChildMax ) ) {
				$noChildCount++;
				$html .= '<li>- ';
				$html .= self::entitiy(
						'sup-term',
						$rootURL . $sibTermIRI,
						$sibTermLabel
				);
				$html .= '</li>';
			} elseif ( $noChildCount > $sibNoChildMax ) {
				$showMore = true;
			}
		}
		if ( $showMore ) {
			$html .= self::more( $moreURL, 'sib' );
		}
		$html .= '<!-- Hierarchy Sibling Term Closing -->';
		return $html;
	}

	/**
	 * Static function to generate Current-Term Header HTML
	 *
	 * @param $ontAbbr
	 * @param $rootURL
	 * @param $term
	 * @return $html
	 */
	private static function curTermHeader( $rootURL, $term, $hasChild ) {
		$html = '<!-- Hierarchy Current Term Opening -->';
		$curTermIRI = $term->iri;
		$curTermLabel = $term->label;
		if ( isset( $hasChild[ $curTermIRI ] ) ) {
			$html .= '<li>+ ';
		} else {
			$html .= '<li>- ';
		}
		$html .= self::entitiy(
				'cur-term',
				$rootURL . $curTermIRI,
				$curTermLabel
		);
		return $html;
	}

	/**
	 * Static function to generate Sub-Term Section HTML
	 *
	 * @param $subTerms
	 * @param $hasChild
	 * @return $html
	 */
	private static function subTermSection( $rootURL, $moreURL, $subTerms, $hasChild ) {
		$subHasChildMax = $GLOBALS['ontology']['hierarchy']['subhasmax'];
		$subNoChildMax = $GLOBALS['ontology']['hierarchy']['subnomax'];

		$html = '<!-- Hierarchy Sub Term Opening -->';
		$html .= '<ul>';
		$noChildCount = 0;
		$hasChildCount = 0;
		$showMore = false;
		foreach ( $subTerms as $subTermIRI => $subTermLabel ) {
			if ( ( $hasChildCount > $subHasChildMax ) && ( $noChildCount > $subNoChildMax ) ) {
				break;
			}
			if ( $subTermLabel == '' ) {
				$subTermLabel = Helper::getShortTerm( $subTermIRI );
			}
			if ( $hasChild[$subTermIRI] && ( $hasChildCount <= $subHasChildMax ) ) {
				$hasChildCount++;
				$html .= '<li>+ ';
				$html .= self::entitiy(
						'sub-term',
						$rootURL . $subTermIRI,
						$subTermLabel
				);
				$html .= '</li>';
			} elseif ( $hasChildCount > $subHasChildMax ) {
				$showMore = true;
			}
			if ( !$hasChild[$subTermIRI] && ( $noChildCount <= $subNoChildMax ) ) {
				$noChildCount++;
				$html .= '<li>- ';
				$html .= self::entitiy(
						'sub-term',
						$rootURL . $subTermIRI,
						$subTermLabel
				);
				$html .= '</li>';
			} elseif ( $noChildCount > $subNoChildMax ) {
				$showMore = true;
			}
		}
		if ( $showMore ) {
			$html .= self::more( $moreURL, 'sub' );
		}
		$html .= '</ul>';
		$html .= '<!-- Hierarchy Sub Term Closing -->';
		return $html;
	}

	/**
	 * Static function to generate Current-Class Bottom HTML
	 *
	 * @return $html
	 */
	private static function curTermBottom() {
		return '</li><!-- Hierarchy Current Term Closing -->';
	}

	/**
	 * Static function to generate Sup-Term Bottom HTML
	 *
	 * @param $path
	 * @return $html
	 */
	private static function supTermBottom( $path ) {
		$html = '';
		foreach ( $path as $supTermIRI => $supTermLabel ) {
			if ( $supTermIRI != 'http://www.w3.org/2002/07/owl#Thing' ) {
				$html .= '</ul></li>';
			}
		}
		$html .= '<!-- Hierarchy Super Term Closing -->';
		return $html;
	}

}

$html = '';

$html = Hierarchy::show( $hierarchyTitle, $ontology, $term, $term->hierarchy[0] );

?>

<!-- Start Ontobee Layout: Hierarchy -->
<?php echo Helper::tidyHTML( $html ); ?>
<!-- End Ontobee Layout: Hierarchy -->