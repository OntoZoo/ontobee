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
 * @file RDFQueryHelper.php
 * @author Edison Ong
 * @since Sep 4, 2015
 * @comment 
 */

namespace RDFStore;

use stdClass;

class RDFQueryHelper {
	
	public static function parseSPARQLResult( $json ) {
		$json = json_decode( $json, true );
		$results = array();
		if ( isset( $json['results']['bindings'] ) ) {
			foreach ( $json['results']['bindings'] as $binding ) {
				$result = array();
				foreach ( $binding as $key => $value ) {
					$result[$key] = $value['value'];
				}
				$results[] = $result;
			}
		}
		return $results;
	}
	
	public static function parseCountResult( $json ) {
		$json = json_decode( $json, true );
		$var = $json['head']['vars'][0];
		return $json['results']['bindings'][0][$var]['value'];
	}
	
	public static function parseSearchResult( $keywords, $searchResult, $graphs ) {
		$match = array();
		
		# Ontology Filter
		if ( sizeof( $graphs ) == 1 ) {
			$results = $searchResult;
		} else {
			$results = array();
			foreach ( $searchResult as $result ) {
				if ( array_key_exists( 'g', $result ) ) {
					if ( in_array( $result['g'], $graphs ) ) {
						$results[] = $result;
					}
				}
			}
		}
		
		# IRI Match
		$results1 = array();
		foreach ( $results as $result ) {
			if ( preg_match_all( '/([a-zA-Z]+)[:_]([a-zA-Z]*)[:_]?(\d+)/', $keywords, $matches, PREG_SET_ORDER ) ) {
				if ( $matches[0][2] == '' ) {
					$searchTermURL='http://purl.obolibrary.org/obo/' . $matches[0][1] . '_' . $matches[0][3];
				} else {
					$searchTermURL='http://purl.obolibrary.org/obo/' . $matches[0][2] . '_' . $matches[0][3];
				}
				$tokens = preg_split( '/[\/#]/', $result['s'] );
				$term = array_pop( $tokens );
				
				if ( $searchTermURL == $result['s'] ) {
					if ( array_key_exists( 'g', $result ) ) {
						$graphAbbr = array_search( $result['g'], $graphs );
					} else {
						$graphAbbr = key( $graphs );
					}
					
					$match[] = array(
							'id' => $graphAbbr . ':::' . $result['s'],
							'ontology' => $graphAbbr,
							'iri' => $result['s'],
							'value' => $result['o'],
							'label' => "{$result['o']} ($graphAbbr:$term)" ,
							'deprecate' => array_key_exists( 'd', $result ),
					);
				} else {
					$results1[] = $result;
				}
			} else {
				$results1[] = $result;
			}
		}
		
		# Exact Match
		$results2 = array();
		foreach ( $results1 as $result) {
			if ( strtolower( $result['o'] ) == strtolower( $keywords ) ) {
				$tokens = preg_split( '/[\/#]/', $result['s'] );
				$term = array_pop( $tokens );
	
				if ( array_key_exists( 'g', $result ) ) {
					$graphAbbr = array_search( $result['g'], $graphs );
				} else {
					$graphAbbr = key( $graphs );
				}
				$match[] = array(
						'id' => $graphAbbr . ':::' . $result['s'],
						'ontology' => $graphAbbr,
						'iri' => $result['s'],
						'value' => $result['o'],
						'label' => "{$result['o']} ($graphAbbr:$term)" ,
						'deprecate' =>  array_key_exists( 'd', $result ),
				);
			} else {
				$results2[] = $result;
			}
		}
		
		# Partial Match
		$results3=array();
		foreach ( $results2 as $result ) {
			if ( strpos( strtolower( $result['o'] ), strtolower( $keywords ) ) === 0 ) {
				$tokens = preg_split( '/[\/#]/', $result['s'] );
				$term = array_pop( $tokens );
	
				if ( array_key_exists( 'g', $result ) ) {
					$graphAbbr = array_search( $result['g'], $graphs );
				} else {
					$graphAbbr = key( $graphs );
				}
				$match[] = array(
						'id' => $graphAbbr . ':::' . $result['s'],
						'ontology' => $graphAbbr,
						'iri' => $result['s'],
						'value' => $result['o'],
						'label' => "{$result['o']} ($graphAbbr:$term)" ,
						'deprecate' =>  array_key_exists( 'd', $result ),
				);
			} else {
				$results3[]=$result;
			}
		}
		
		# Remaining Match (Regular Expression Match return by SPARQL)
		foreach ( $results3 as $result ) {
			$tokens = preg_split( '/[\/#]/', $result['s'] );
			$term = array_pop( $tokens );
	
			if ( array_key_exists( 'g', $result ) ) {
				$graphAbbr = array_search( $result['g'], $graphs );
			} else {
				$graphAbbr = key( $graphs );
			}
			$match[] = array(
					'id' => $graphAbbr . ':::' . $result['s'],
					'ontology' => $graphAbbr,
					'iri' => $result['s'],
					'value' => $result['o'],
					'label' => "{$result['o']} ($graphAbbr:$term)" ,
					'deprecate' =>  array_key_exists( 'd', $result ),
			);
		}
		
		return $match;
	}
	
	public static function parseEntity( $entityResult, $first, $second = '' ) {
		$entity = array();
		foreach ( $entityResult as $result ) {
			if ( array_key_exists( $first, $result ) ) {
				if ( $second === '' ) {
					$entity[] = $result[$first];
				} else {
					if ( array_key_exists( $second, $result ) ) {
						$entity[$result[$first]][] = $result[$second];
					} else {
						if ( !isset( $entity[$result[$first]] ) ) {
							$entity[$result[$first]] = array();
						}
					}
				}
			}
		}
		return $entity;
	}
	
	public static function parseTransitivePath( $transitiveResult ) {
		$tmpPath = array();
		foreach ( $transitiveResult as $result ) {
			$tmpPath[$result['path']][] = $result;
		}
		if ( !empty( $tmpPath ) ) {
			$pathQuery = array();
			$pathSize = array();
			
			foreach( $tmpPath as $index => $pathArray ) {
				if ( count( $pathArray ) == 1 ) {
					continue;
				}
				
				# Remove the first element in the path, which is always the term being queried
				array_shift( $pathArray );

				$pathQuery[$index] = self::extractTransitivePathLabel( $pathArray );
				$pathSize[$index] = count( $pathArray ) ;
			}
			
			arsort( $pathSize );
			
			$path = array();
			foreach( $pathSize as $id => $size ) {
				$pathTest = $pathQuery[$id];
				if ( empty( $path ) ) {
					$path[] = $pathTest;
					continue;
				}
				$duplicate = true;
				foreach( $path as $pathCheck ) {
					$pathDiff = array_diff( $pathTest, $pathCheck );
					if ( sizeof( $pathDiff ) > 0 ) {
						$duplicate = false;
					}
				}
				if ( !$duplicate ) {
					$path[] = $pathTest;
				}
			}
			return $path;
		}
	}
	
	private static function extractTransitivePathLabel( $transitiveResult ) {
		$path = array();
		foreach ( $transitiveResult as $result ) {
			$path[$result['link']] = '';
			if ( isset( $result['label'] ) ) {
				$path[$result['link']] = $result['label'];
			}
		}
		return array_reverse( $path, true );
	}
	
	public static function parseRDF ( $json, $term ) {
		$json = json_decode( $json, true );
		if ( !is_null( $json ) ) {
			if ( preg_match( '/nodeID:\/\//', $term ) ) {
				$term = preg_replace( '/nodeID:\/\//', '_:v', $term );
				$isNode = true;
			} else {
				$isNode = false;
			}
			if ( array_key_exists( $term, $json ) ) {
				$results = $json[$term];
				if ( $isNode ) {
					$results = self::parseRecursiveRDFNode( $json, $term );
				} else {
					foreach ( $results as $propertyIRI => $properties ) {
						foreach ( $properties as $index => $property ) {
							if ( $property['type'] == 'bnode' ) {
								$results[$propertyIRI][$index] = self::parseRecursiveRDFNode( $json, $property['value'] );
							}
						}
					}
				}
				return $results;
			} else {
				return array();
			}
		} else {
			return array();
		}
	}

	public static function parseRecursiveRDFNode( $rdfResult, $nodeIRI ) {
		$objEquivalent = array();
		
		$operations = $GLOBALS['ontology']['restriction']['operation'];
		$types = $GLOBALS['ontology']['restriction']['type'];
		$lists = $GLOBALS['ontology']['restriction']['list'];
		
		$onPropertyIRI = $GLOBALS['ontology']['restriction']['onProperty'];
		$nilIRI = $GLOBALS['ontology']['restriction']['nil'];
		
		if ( isset($rdfResult[$nodeIRI] ) ) {
			$curResult = $rdfResult[$nodeIRI];
			
			if ( isset( $curResult[$onPropertyIRI] ) ) {
				$objEquivalent['restrictionValue'][] = $curResult[$onPropertyIRI][0]['value'];
			}
			
			if ( isset( $curResult[$lists['first']] ) ) {
				$curNode = $curResult;
				while ( $curNode[$lists['rest']][0]['value'] != $nilIRI ) {
					if ( $curNode[$lists['first']][0]['type'] == 'uri' ) {
						$objEquivalent['restrictionValue'][] = $curNode[$lists['first']][0]['value'];
					} else {
						$objEquivalent['restrictionValue'][] = self::parseRecursiveRDFNode( $rdfResult, $curNode[$lists['first']][0]['value'] );
					}
					$curNodeID = $curNode[$lists['rest']][0]['value'];
					$curNode = $rdfResult[$curNodeID];
				}
				if ( $curNode[$lists['first']][0]['type'] == 'uri' ) {
					$objEquivalent['restrictionValue'][] = $curNode[$lists['first']][0]['value'];
				} else {
					$objEquivalent['restrictionValue'][] = self::parseRecursiveRDFNode( $rdfResult, $curNode[$lists['first']][0]['value'] );
				}
			}
			
			foreach ( $types as $type => $typeIRI ) {
				if ( isset( $curResult[$typeIRI] ) ) {
					$objEquivalent['restrictionType'] = $type;
					
					if ( $curResult[$typeIRI][0]['type'] == 'uri' ) {
						$objEquivalent['restrictionValue'][] = $curResult[$typeIRI][0]['value'];
					} else {
						$objEquivalent['restrictionValue'][] = self::parseRecursiveRDFNode( $rdfResult, $curResult[$typeIRI][0]['value'] );
					}
				}
			}
			
			foreach ( $operations as $operation => $operationIRI ) {
				if ( isset( $curResult[$operationIRI] ) ) {
					$curNodeID = $curResult[$operationIRI][0]['value'];
					$curNode = $rdfResult[$curNodeID];
					if ( array_key_exists( $lists['first'], $curNode ) && array_key_exists( $lists['rest'], $curNode ) ) {
						$objEquivalent['restrictionType'] = $operation;
						$objEquivalent['restrictionValue'] = array();
						
						while ( $curNode[$lists['rest']][0]['value'] != $nilIRI ) {
							if ( $curNode[$lists['first']][0]['type'] == 'uri' ) {
								$objEquivalent['restrictionValue'][] = $curNode[$lists['first']][0]['value'];
							} else {
								$objEquivalent['restrictionValue'][] = self::parseRecursiveRDFNode( $rdfResult, $curNode[$lists['first']][0]['value'] );
							}
						
							$curNodeID = $curNode[$lists['rest']][0]['value'];
							$curNode = $rdfResult[$curNodeID];
						}
						
						if ( $curNode[$lists['first']][0]['type'] == 'uri' ) {
							if ( sizeof( $objEquivalent['restrictionValue'] ) != 0 ) {
								$objEquivalent['restrictionValue'][] = $curNode[$lists['first']][0]['value'];
							} else {
								$objEquivalent = $curNode[$lists['first']][0]['value'];
							}
						} else {
							$objEquivalent['restrictionValue'][] = self::parseRecursiveRDFNode( $rdfResult, $curNode[$lists['first']][0]['value'] );
						}

					} else {
						$objEquivalent['restrictionType'] = $operation;
						$objEquivalent['restrictionValue'] = array();
						$objEquivalent['restrictionValue'][] = self::parseRecursiveRDFNode( $rdfResult, $curNodeID );
					}
				}
			}
		}
			
		return $objEquivalent;
	}

}

?>