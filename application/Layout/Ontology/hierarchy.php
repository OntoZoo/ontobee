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
 * @author Edison Ong
 * @since Sep 5, 2015
 * @comment 
 */
 
use View\Helper;

if ( !$this ) {
	exit(header('HTTP/1.0 403 Forbidden'));
}

class Hierarchy {

	public static function show( $ontology, $term, $hierarchy ) {
		$rootURL = SITEURL . "ontology/?ontology=$ontology->ontology_abbrv&iri=";
		
		$path = $hierarchy['path'];
		$subClasses = $hierarchy['subClasses'];
		$sibClasses = $hierarchy['sibClasses'];
		$hasChild = $hierarchy['hasChild'];
		if ( $term->type == 'Class' ) {
			$html = 
<<<END
<div class="heading">Class Hierarchy</div>
<div class="main">
END;
		} else if ( in_array( $term->type, array( 'ObjectProperty', 'AnnotationProperty', 'DataProperty' ) ) ) {
			$html =
<<<END
<div class="heading">Property Hierarchy</div>
<div class="main">
END;
		} else {
			$html =
<<<END
<div class="heading">Hierarchy</div>
<div class="main">
END;
		}
		
		$top = Helper::getShortTerm( array_search( $term->type, $GLOBALS['ontology']['top_level_term']) );
		
		if ( $top != '' ) {
			$html .= "<ul class=\"top\"><li class=\"top-term\">$top<ul>";
		} else {
			$html .= '<ul class="top"><li class="top-term">Top<ul>';
		}

		if ( !empty( $path ) || !empty( $subClasses ) ) {

			if ( !empty( $path ) ) {
				$html .= self::supClassHeader( $rootURL, $path );

				if( !empty( $sibClasses ) ) {
					$html .= self::sibClassSection( $rootURL, $sibClasses, $hasChild );
				}

				$html .= self::curClassHeader( $rootURL, $term, $hasChild );

				if ( !empty( $subClasses ) ) {
					$html .= self::subClassSection( $rootURL, $subClasses, $hasChild );
				}

				$html .= self::curClassBottom();

				$html .= self::supClassBottom( $path );
			} else {
				$html .= self::curClassHeader( $rootURL, $term, $hasChild );

				$html .= self::subClassSection( $rootURL, $subClasses, $hasChild );

				$html .= self::curClassBottom();
			}
		} else {
			$html .= self::curClassHeader( $rootURL, $term, $hasChild );
			
			$html .= self::curClassBottom();
		}
		
		$html .= '</ul></li></ul></div></div>';

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
		$html = '<a class="';
		$html .= $class;
		$html .= '" oncontextmenu="return false;" href="';
		$html .= $link;
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
	private static function more( $class ) {
		#TODO: Modify add more to HTML checkbox that trigger more terms to display or not
		$html = '<li>';
		$html .= '<a id="hierarchy-';
		$html .= $class;
		$html .= '-more" class="';
		$html .= $class;
		$html .= '-more">more...</a>';
		$html .= '<a id="hierarchy-';
		$html .= $class;
		$html .= '-less" class="';
		$html .= $class;
		$html .= '-less">less...</a>';
		$html .= '</li">';
		return $html;
	}

	/**
	 * Static function to generate Super-Class Header HTML
	 *
	 * @param $ontAbbr
	 * @param $rootURL
	 * @param $path
	 * @return $html
	 */
	private static function supClassHeader( $rootURL, $path ) {
		$html = '<!-- Hierarchy Super Class Opening -->';
		foreach ( $path as $supClassIRI => $supClassLabel ) {
			if ( $supClassLabel == '' ) {
				$supClassLabel = Helper::getShortTerm( $supClassIRI );
			}
			if ( $supClassIRI != 'http://www.w3.org/2002/07/owl#Thing' ) {
				$html .= '<li>+ ';
				$html .= self::entitiy(
						'sup-term',
						$rootURL . $supClassIRI,
						$supClassLabel
				);
				$html .= '<ul>';
			}
		}
		return $html;
	}

	/**
	 * Static function to generate Sibling-Class Section HTML
	 *
	 * @param $ontAbbr
	 * @param $rootURL
	 * @param $sibClasses
	 * @param $hasChild
	 * @return $html
	 */
	private static function sibClassSection( $rootURL, $sibClasses, $hasChild ) {
		$sibHasChildMax = $GLOBALS['ontology']['hierarchy']['sibhasmax'];
		$sibNoChildMax =$GLOBALS['ontology']['hierarchy']['sibnomax'];

		$html = '<!-- Hierarchy Sibling Class Opening -->';
		$noChildCount = 0;
		$hasChildCount = 0;
		$showMore = false;
		foreach ( $sibClasses as $sibClassIRI => $sibClassLabel ) {
			if ( ( $hasChildCount > $sibHasChildMax ) && ( $noChildCount > $sibNoChildMax ) ) {
				break;
			}
			if ( $sibClassLabel == '' ) {
				$sibClassLabel = Helper::getShortTerm( $sibClassIRI );
			}
			if ( $hasChild[$sibClassIRI] && ( $hasChildCount <= $sibHasChildMax) ) {
				$hasChildCount++;
				$html .= '<li>+ ';
				$html .= self::entitiy(
						'sup-term',
						$rootURL . $sibClassIRI,
						$sibClassLabel
				);
				$html .= '</li>';
			} elseif ( $hasChildCount > $sibHasChildMax ) {
				$showMore = true;
			}
			if ( !$hasChild[$sibClassIRI] && ( $noChildCount <= $sibNoChildMax ) ) {
				$noChildCount++;
				$html .= '<li>- ';
				$html .= self::entitiy(
						'sup-term',
						$rootURL . $sibClassIRI,
						$sibClassLabel
				);
				$html .= '</li>';
			} elseif ( $noChildCount > $sibNoChildMax ) {
				$showMore = true;
			}
		}
		if ( $showMore ) {
			#TODO: Modify add remaining terms as hiddenthat trigger base on display more checkbox
			$html .= self::more( 'sib' );
		}
		$html .= '<!-- Hierarchy Sibling Class Closing -->';
		return $html;
	}

	/**
	 * Static function to generate Current-Class Header HTML
	 *
	 * @param $ontAbbr
	 * @param $rootURL
	 * @param $term
	 * @return $html
	 */
	private static function curClassHeader( $rootURL, $term, $hasChild ) {
		$html = '<!-- Hierarchy Current Class Opening -->';
		$curClassIRI = $term->iri;
		$curClassLabel = $term->label;
		if ( isset( $hasChild[ $curClassIRI ] ) ) {
			$html .= '<li>+ ';
		} else {
			$html .= '<li>- ';
		}
		$html .= self::entitiy(
				'cur-term',
				$rootURL . $curClassIRI,
				$curClassLabel
		);
		return $html;
	}

	/**
	 * Static function to generate Sub-Class Section HTML
	 *
	 * @param $subClasses
	 * @param $hasChild
	 * @return $html
	 */
	private static function subClassSection( $rootURL, $subClasses, $hasChild ) {
		$subHasChildMax = $GLOBALS['ontology']['hierarchy']['subhasmax'];
		$subNoChildMax = $GLOBALS['ontology']['hierarchy']['subnomax'];

		$html = '<!-- Hierarchy Sub Class Opening -->';
		$html .= '<ul>';
		$noChildCount = 0;
		$hasChildCount = 0;
		$showMore = false;
		foreach ( $subClasses as $subClassIRI => $subClassLabel ) {
			if ( ( $hasChildCount > $subHasChildMax ) && ( $noChildCount > $subNoChildMax ) ) {
				break;
			}
			if ( $subClassLabel == '' ) {
				$subClassLabel = Helper::getShortTerm( $subClassIRI );
			}
			if ( $hasChild[$subClassIRI] && ( $hasChildCount <= $subHasChildMax ) ) {
				$hasChildCount++;
				$html .= '<li>+ ';
				$html .= self::entitiy(
						'sub-term',
						$rootURL . $subClassIRI,
						$subClassLabel
				);
				$html .= '</li>';
			} elseif ( $hasChildCount > $subHasChildMax ) {
				$showMore = true;
			}
			if ( !$hasChild[$subClassIRI] && ( $noChildCount <= $subNoChildMax ) ) {
				$noChildCount++;
				$html .= '<li>- ';
				$html .= self::entitiy(
						'sub-term',
						$rootURL . $subClassIRI,
						$subClassLabel
				);
				$html .= '</li>';
			} elseif ( $noChildCount > $subNoChildMax ) {
				$showMore = true;
			}
		}
		if ( $showMore ) {
			#TODO: Modify add remaining terms as hiddenthat trigger base on display more checkbox
			$html .= self::more( 'sub' );
		}
		$html .= '</ul>';
		$html .= '<!-- Hierarchy Sub Class Closing -->';
		return $html;
	}

	/**
	 * Static function to generate Current-Class Bottom HTML
	 *
	 * @return $html
	 */
	private static function curClassBottom() {
		return '</li><!-- Hierarchy Super Class Closing -->';
	}

	/**
	 * Static function to generate Sup-Class Bottom HTML
	 *
	 * @param $path
	 * @return $html
	 */
	private static function supClassBottom( $path ) {
		$html = '';
		foreach ( $path as $supClassIRI => $supClassLabel ) {
			if ( $supClassIRI != 'http://www.w3.org/2002/07/owl#Thing' ) {
				$html .= '</ul></li>';
			}
		}
		$html .= '<!-- Hierarchy Super Class Closing -->';
		return $html;
	}

}

?>

<!-- Ontobee Hierarchy Display Start -->
<div class="hierarchy">
<?php
echo Helper::tidyHTML( Hierarchy::show( $ontology, $term, $term->hierarchy[0] ) );
?>
</div>
<!-- Ontobee Hierarchy Display End -->