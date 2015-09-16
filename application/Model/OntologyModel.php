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
 * @file OntologyModel.php
 * @author Edison Ong
 * @since Sep 3, 2015
 * @comment 
 */
 
namespace Model;

use stdClass;
use Exception;

use RDFStore\RDFStore;
use RDFStore\RDFQueryHelper;

class OntologyModel {
	
	private $db;
	
	private $rdf;
	
	private $prefixNS;
	
	private $ontAbbr;
	
	private $queries = array();
	
	private $ontology;
	
	private $class;
	
	/**
	 * Setter
	 *
	 * @param $ontAbbr
	 */
	public function setOntAbbr( $ontAbbr ) {
		$this->ontAbbr = $ontAbbr;
	}
	
	/**
	 * Getter
	 *
	 * @return $ontAbbr
	 */
	public function getOntAbbr() {
		return $this->ontAbbr;
	}
	
	/**
	 * Getter
	 *
	 * @return $queries
	 */
	public function getQueries() {
		return $this->queries;
	}
	
	/**
	 * Getter
	 *
	 * @return $ontology
	 */
	public function getOntology() {
		return $this->ontology;
	}
	
	/**
	 * Getter
	 *
	 * @return $class
	 */
	public function getClass() {
		return $this->class;
	}
	
	/**
	 * Getter
	 *
	 * @return $rdf
	 */
	public function getRDF() {
		return $this->rdf;
	}
	
	
	private function addQueries( $query ) {
		if ( is_array( $query ) ) {
			$this->queries = array_merge( $this->queries, $query );
		} else {
			$this->queries[] = $query;
		}
	}
	
	public function __construct( $db ) {
		$this->db = $db;
		$this->prefixNS = $GLOBALS['ontology']['namespace'];
	}
	
	public function getAllOntology( $load = 'y', $list = 'y', $order = true ) {
		$sql = "SELECT * FROM ontology WHERE ";
		
		if ( !is_null( $load ) && !is_null( $list ) ) {
			$sql .= "loaded = '$load' AND to_list = '$list' "; 
		} else if ( !is_null( $load ) ) {
			$sql .= "loaded = '$load' ";
		} else if ( !is_null( $list ) ) {
			$sql .= "to_list= '$list' ";
		}
		
		if ( $order ) {
			$sql .= 'ORDER BY ontology_abbrv';
		}
		
		$query = $this->db->prepare( $sql );
		$query->execute();
		$results = $query->fetchAll();
		foreach( $results as $result ) {
			$ontologies[$result->ontology_graph_url] = $result;
		}
		return $ontologies;
	}
	
	public function countAllOntologyType() {
		if ( !isset( $this->rdf ) ) {
			throw new Exception( "RDFStore is not setup. Please run OntologyModel->loadOntology first." );
		}
		$stats = array();
		foreach ( $GLOBALS['ontology']['type'] as $type => $typeIRI ) {
			list( $stat, $query ) = $this->rdf->countType( $this->ontology->ontology_graph_url, $typeIRI );
			$this->addQueries( $query );
			$stats[$type] = $stat;
		}
		return $stats;
	}
	
	public function countOntologyType() {
		if ( !isset( $this->rdf ) ) {
			throw new Exception( "RDFStore is not setup. Please run OntologyModel->loadOntology first." );
		}
	
		$stats = array();
		foreach ( $GLOBALS['ontology']['type'] as $type => $typeIRI ) {
			list( $terms, $query ) = $this->rdf->selectTermFromType( $this->ontology->ontology_graph_url, $typeIRI );
			$prefixArray = array();
			$classNoPrefixCount = 0;
			foreach ( $terms as $iri => $labels ){
				if ( preg_match( '/\/([A-Za-z\.\-_]+)#[a-zA-Z_0-9]+/', $iri, $match ) ) {
					$prefix = $match[1];
					if( array_key_exists( $prefix, $prefixArray ) ){
						$prefixArray[$prefix] += 1;
					} else {
						$prefixArray[$prefix] = 1;
					}
				} else if ( preg_match( '/\/([A-Z][A-Za-z]+)_[-a-zA-Z_0-9]+/', $iri, $match ) ) {
					$prefix = $match[1];
					if( array_key_exists( $prefix, $prefixArray ) ){
						$prefixArray[$prefix] += 1;
					} else {
						$prefixArray[$prefix] = 1;
					}
				} else if ( preg_match( '/\/([a-z]+)_[0-9]+/', $iri, $match ) ) {
					$prefix = $match[1];
					if ( array_key_exists( $prefix, $prefixArray ) ){
						$prefixArray[$prefix] += 1;
					} else {
						$prefixArray[$prefix] = 1;
					}
				} else {
					$classNoPrefixCount ++;
				}
			}
			foreach( $prefixArray as $prefix => $count ) {
				$stats[$prefix][$type] = $count;
			}
			$stats['no_prefix'][$type] = $classNoPrefixCount;
		}
		$noprefix = $stats['no_prefix'];
		unset( $stats['no_prefix'] );
		ksort( $stats );
		$stats['NoPrefix'] = $noprefix;
		return $stats;
	}
	
	public function getTermList( $termIRI, $prefix, $letter, $page, $max ) {
		if ( !isset( $this->rdf ) ) {
			throw new Exception( "RDFStore is not setup. Please run OntologyModel->loadOntology first." );
		}
		$termResult = array();
		if ( !is_null( $termIRI ) ) {
			if ( !in_array( $termIRI, $GLOBALS['ontology']['type'] ) ) {
				list( $subClasses, $query ) = $this->rdf->selectSubClass( $this->ontology->ontology_graph_url, $termIRI );
				$this->addQueries( $query );
				$subClasses = OntologyModelHelper::parseClassResult( $subClasses );
				foreach( $subClasses as $subClass ) {
					$termResult[$subClass->iri][] = $subClass->label;
				}
			} else {
				list( $termResult, $query ) = $this->rdf->selectTermFromType( $this->ontology->ontology_graph_url, $termIRI );
				$this->addQueries( $query );
			}
		} else {
			foreach ( $GLOBALS['ontology']['type'] as $typeIRI ) {
				list( $tmpResult, $query ) = $this->rdf->selectTermFromType( $this->ontology->ontology_graph_url, $typeIRI );
				$this->addQueries( $query );
				$termResult = array_merge( $termResult, $tmpResult );
			}
		}
	
		if ( strlen( $letter ) != 1 && preg_match( '/[a-z1-9]/i', substr( $letter, 0, 1 ) )) {
			$letter = substr( $letter, 0, 1 );
		} else if ( strlen( $letter ) != 1 ) {
			$letter = '*';
		}
	
		list( $terms, $letters ) = OntologyModelHelper::parseTermList( $termResult, $prefix, $letter );
	
		$pageCount = ceil( sizeof( $terms ) / $max );
	
		if ( $page == '' || intval( $page ) < 1 || intval( $page ) > $pageCount ) {
			$page = 1;
		}
	
		return array( $terms, $letters, $page, $pageCount );
	}
	
	public function searchKeyword( $keyword, $ontAbbr = null, $limit = 10000 ) {
		$ontologies = array();
		if ( is_null( $ontAbbr ) || $ontAbbr == '' ) {
			foreach ( $this->getAllOntology( 'y', null, false ) as $ontology ) {
				$ontologies[$ontology->ontology_abbrv] = $ontology->ontology_graph_url;
			}
		} else {
			$this->loadOntology( $ontAbbr );
			$ontologies[$this->ontology->ontology_abbrv] = $this->ontology->ontology_graph_url;
		}
		
		$rdf = new RDFStore( $GLOBALS['search']['endpoint'] );
		
		list( $match, $query ) = $rdf->search( $ontologies, $keyword, $limit );
		$this->addQueries( $query );
		return $match;
	}
	
	public function askTermType( $termIRI ) {
		if ( !isset( $this->rdf ) ) {
			throw new Exception( "RDFStore is not setup. Please run OntologyModel->loadOntology first." );
		}
		list( $typeIRI, $query ) = $this->rdf->selectTermType( $this->ontology->ontology_graph_url, $termIRI );
		$this->addQueries( $query );
		$type = array_search( $typeIRI, $GLOBALS['ontology']['type'] );
		return $type;
	}
	
	public function loadOntology( $ontAbbr, $endpoint = null, $detail = true ) {
		$sql = "SELECT * FROM ontology WHERE ontology_abbrv = '$ontAbbr'";
		$query = $this->db->prepare( $sql );
		$query->execute();
		$this->ontology = $query->fetch();
		
		if ( !empty($this->ontology) && $detail ) {
			$sql = "SELECT * FROM key_terms WHERE ontology_abbrv='$ontAbbr' ORDER BY term_label";
			$query = $this->db->prepare( $sql );
			$query->execute();
			$this->ontology->key_term = $query->fetchall();
		}
		
		if ( !is_null( $endpoint ) ) {
			$this->rdf = new RDFStore( $endpoint );
		} else {
			$this->rdf = new RDFStore( $this->ontology->end_point );
		}
		$count = 0;
		$connection = false;
		while ( $count < 10 && !$connection ) {
			$count += 1;
			if ( $this->rdf->ping() ) {
				$connection = true;
			}
		}
		if ( !$connection ) {
			throw new Exception( "Unable to connect RDFStore endpoint: {$this->ontology->end_point}" );
		}
			
		if ( $detail ) {
			$ontIRI = $this->prefixNS['obo'] . strtolower( $ontAbbr ) . '.owl';
			list( $annotationResult, $query ) = $this->rdf->selectOntologyAnnotation(
				$this->ontology->ontology_graph_url,
				$ontIRI 
			);
			$this->addQueries( $query );
			list( $annotationLabels, $query ) = $this->rdf->selectAllTermLabel(
				$this->ontology->ontology_graph_url,
				array_keys( $annotationResult )
			);
			$this->addQueries( $query );
			
			$annotations = array();
			foreach ( $annotationResult as $annotationIRI => $annotationValue ) {
				if ( array_key_exists( $annotationIRI, $annotationLabels ) ) {
					$annotationLabel = array_shift( $annotationLabels[$annotationIRI] );
				} else {
					$annotationLabel = OntologyModelHelper::getShortTerm( $annotationIRI );
				}
				$annotations[] = array(
					'label' => $annotationLabel,
					'value' => $annotationValue,
				);
			}
			$this->ontology->annotation = $annotations;
			
			$this->queries[] = $query;
			
			foreach ( $GLOBALS['ontology']['type'] as $type => $typeIRI ) {
				list( $this->ontology->$type, $query ) = $this->rdf->selectOntologyProperty(
					$this->ontology->ontology_graph_url,
					$typeIRI
				);
				$this->addQueries( $query );
			}
		}
	}
	
	public function loadClass( $termIRI ) {
		if ( !isset( $this->rdf ) ) {
			throw new Exception( "RDFStore is not setup. Please run OntologyModel->loadOntology first." );
		}
		
		$class = OntologyModelHelper::makeClass( array( 
			'id' => OntologyModelHelper::getShortTerm( $termIRI ),
			'iri' => $termIRI,
		) );
		
		list( $describeResult, $query ) = $this->rdf->describeClass( $this->ontology->ontology_graph_url, $termIRI );
		$this->addQueries( $query );
		
		$describes = $describeResult['describe'];
		$class->describe = $describeResult['describe'];
		
		$preferLabel = array();
		foreach( $GLOBALS['ontology']['label']['priority'] as $labelIRI ) {
			if ( array_key_exists( $labelIRI, $class->describe ) ) {
				$tmpLabel = $class->describe[$labelIRI];
				$preferLabel = $tmpLabel[0]['value'];
				break;
			}
		}
		if ( !empty( $preferLabel ) ) {
			$preferLabel = $preferLabel;
		} else {
			$preferLabel = OntologyModelHelper::getShortTerm( $termIRI );
		}
		$class->label = $preferLabel;
		
		$typeResults = $class->describe[$GLOBALS['ontology']['namespace']['rdf'] . 'type'];
		$typeIRIs = array();
		foreach ( $typeResults as $typeResult ) {
			if ( $typeResult['type'] == 'uri' ) {
				$typeIRIs[] = $typeResult['value'];
			}
		}
		foreach ( $typeIRIs as $index => $typeIRI ) {
			if ( array_key_exists( $typeIRI, $GLOBALS['alias']['type'] ) ) {
				$typeIRIs[$index] = $GLOBALS['alias']['type'][$typeIRI];
			}
		}
		$typeIRIs = array_unique( $typeIRIs );
		$class->type =array_search( array_shift( $typeIRIs ), $GLOBALS['ontology']['type'] );
		
		$hierarchyResult = $describeResult['transitiveSupClass'];
		$hierarchy = $this->queryHierarchy(
			$termIRI,
			$hierarchyResult
		);
		$class->hierarchy = $hierarchy;
		
		$class->axiom = $describeResult['axiom'];
		
		$class->annotation_annotation = $describeResult['annotation_annotation'];
		
		$nodes = array();
		$usage = array();
		foreach ( $describeResult['usage']['term'] as $use ) {
			$nodes[$use['o']] = $use['ref'];
			$usage[$use['ref']] = array(
					'label' => $use['label'],
					'type' => $use['refp'],
			);
		}
		list( $nodeResults, $query ) = $this->rdf->describeAll($this->ontology->ontology_graph_url, array_keys( $nodes ) );
		$this->addQueries( $query );
		foreach ( $nodeResults as $nodeIRI => $nodeResult ) {
			$ref = $nodes[$nodeIRI];
			$refp = $usage[$ref]['type'];
			$usage[$nodes[$nodeIRI]]['axiom'] = $nodeResult;
			$describes[$refp][] = $nodeResult;
		}
		$class->usage = $usage;
		
		$other = array();
		$validOntology = $this->getAllOntology();
		$validGraph = array_keys( $validOntology );
		foreach ( $describeResult['usage']['ontology'] as $graph ) {
			if ( in_array( $graph['g'], $validGraph ) ) {
				$other[] = $graph['g'];
			}
		}
		$class->other = $other;
		
		$related = $this->queryRelated( $describes );
		$related[$termIRI] = $class;
		$class->related = $related;
		
		$annotations = array();
		foreach ( array_unique( array_keys( $class->describe ) ) as $property ) {
			if ( $related[$property]->type == $GLOBALS['ontology']['type']['AnnotationProperty'] ) {
				$values = array();
				foreach ( $class->describe[$property] as $token ) {
					if ( array_key_exists( 'value', $token ) ) {
						$values[] = $token['value'];
					}
				}
				if ( empty( $values ) ) {
					continue;
				}
				$label = $related[$property]->label;
				if ( $label == '' ) {
					$label = OntologyModelHelper::getShortTerm( $property );
				}
				$annotations[$property] = array(
						'label' => $label,
						'value' => $values,
				);
			}
		}
		$class->annotation = $annotations;
		
		
		$this->class = $class;
	}
	
	public function loadRDF( $termIRI ) {
		if ( !isset( $this->rdf ) ) {
			throw new Exception( "RDFStore is not setup. Please run OntologyModel->loadOntology first." );
		}
		list( $rdf, $query ) = $this->rdf->exportTermRDF(
			$this->ontology->ontology_graph_url,
			$termIRI,
			$this->ontology->ontology_url
		);
		
		$tmpFile = tmpfile();
		fwrite( $tmpFile, $rdf );
		$tmpMeta = stream_get_meta_data( $tmpFile );
		$tmpName = $tmpMeta['uri'];
		exec( 'java -cp "' . SCRIPTPATH . "library/java/*\" org.hegroup.rdfconvert.Reformat $tmpName" );
		$rdf = file_get_contents( $tmpName );
		$this->rdf = $rdf;
		fclose( $tmpFile );
	}
	
	private function queryRelated( $describe ) {
		foreach ( $describe as $property => $propertyObjects ) {
			$related[$property] = OntologyModelHelper::makeClass( array(
					'id' => OntologyModelHelper::getShortTerm( $property ),
					'iri' => $property,
					'label' => null,
					'type' => null,
					'hasChild' => null,
			) );
				
			foreach ( $propertyObjects as $object ) {
				if ( array_key_exists( 'type', $object ) && $object['type'] == 'uri' ) {
					$related[$object['value']] = OntologyModelHelper::makeClass( array(
							'id' => OntologyModelHelper::getShortTerm( $object['value'] ),
							'iri' => $object['value'],
							'label' => null,
							'type' => null,
							'hasChild' => null,
					) );
				} else if ( array_key_exists( 'restrictionValue', $object ) ) {
					$recursiveClasses = OntologyModelHelper::parseRecursiveRelated( $object );
					$recursiveClasses = array_unique( $recursiveClasses );
					foreach ( $recursiveClasses as $class ) {
						$related[$class] = OntologyModelHelper::makeClass( array(
								'id' => OntologyModelHelper::getShortTerm( $class ),
								'iri' => $class,
								'label' => null,
								'type' => null,
								'hasChild' => null,
						) );
					}
				}
			}
		}
	
		list( $types, $query ) = $this->rdf->selectAllTermType( $this->ontology->ontology_graph_url, array_keys( $related ) );
		$this->addQueries( $query );
		foreach ( $related as $termIRI => $termClass ) {
			if ( isset( $types[$termIRI] ) ) {
				$termClass->type = $types[$termIRI];
			}
		}
	
		list( $labels, $query ) = $this->rdf->selectAllTermLabel( $this->ontology->ontology_graph_url, array_keys( $related ) );
		$this->addQueries( $query );
		foreach ( $related as $termIRI => $termClass ) {
			if ( isset( $labels[$termIRI] ) ) {
				$termClass->label = array_shift( $labels[$termIRI] );
			}
		}
		return $related;
	}
	
	private function queryHierarchy( $termIRI, $supClassResults ) {
		$hierarchy = array();
		$supClasses = array();
		$sibClasses = array();
		$subClasses = array();
		$hasChild = array();
		if ( !empty( $supClassResults ) ) {
			foreach ( $supClassResults as $supClassResult ) {
				foreach ( $supClassResult as $supClassIRI => $supClassLabel) {
					if ( $supClassLabel != '' ) {
						$supClasses[$supClassIRI] = $supClassLabel;
					} else {
						$supClasses[$supClassIRI] = OntologyModelHelper::getShortTerm( $supClassIRI );
					}
				}
	
				if ( !empty( $supClasses ) ) {
					$tmpClasses = $supClasses;
					end( $tmpClasses );
					$supClass = key( $tmpClasses );
					list( $sibClassResult, $query ) = $this->rdf->selectSubClass(
						$this->ontology->ontology_graph_url,
						$supClass,
						$limit = 10
					);
					$this->addQueries( $query );
					$sibClassResult = OntologyModelHelper::parseClassResult( $sibClassResult );
					unset( $sibClassResult[$termIRI] );
					foreach ( $sibClassResult as $sibClassIRI => $sibClassObject ) {
						$sibClasses[$sibClassIRI] = $sibClassObject->label;
						if ( $sibClassObject->hasChild ) {
							$hasChild[$sibClassIRI] = true;
						} else {
							$hasChild[$sibClassIRI] = false;
						}
					}
				}
					
				list( $subClassResult, $query ) = $this->rdf->selectSubClass(
					$this->ontology->ontology_graph_url,
					$termIRI,
					$limit = 10
				);
				$this->addQueries( $query );
				$subClassResult = OntologyModelHelper::parseClassResult( $subClassResult );
				foreach ( $subClassResult as $subClassIRI => $subClassObject ) {
					$subClasses[$subClassIRI] = $subClassObject->label;
					if ( $subClassObject->hasChild ) {
						$hasChild[$subClassIRI] = true;
					} else {
						$hasChild[$subClassIRI] = false;
					}
				}
					
				$hierarchy[] = array(
						'path' => $supClasses,
						'supClass' => $supClass,
						'sibClasses' => $sibClasses,
						'subClasses' => $subClasses,
						'hasChild' => $hasChild,
				);
			}
		} else {
			list( $subClassResult, $query ) = $this->rdf->selectSubClass(
				$this->ontology->ontology_graph_url,
				$termIRI,
				$limit = 10
			);
			$this->addQueries( $query );
			$subClassResult = OntologyModelHelper::parseClassResult( $subClassResult );
			foreach ( $subClassResult as $subClassIRI => $subClassObject ) {
				$subClasses[$subClassIRI] = $subClassObject->label;
				if ( $subClassObject->hasChild ) {
					$hasChild[$subClassIRI] = true;
				} else {
					$hasChild[$subClassIRI] = false;
				}
			}
	
			$hierarchy[] = array(
					'path' => null,
					'supClass' => null,
					'sibClasses' => null,
					'subClasses' => $subClasses,
					'hasChild' => $hasChild,
			);
		}
		
		return $hierarchy;
	}
	
}

class OntologyModelHelper {
	public static function getShortTerm( $term ) {
		if ( preg_match( '/^http/', $term ) ) {
			$tmp_array = preg_split( '/[#\/]/', $term );
			return( array_pop( $tmp_array ) );
		}
		else {
			return( $term );
		}
	}
	
	public static function makeClass( $input ) {
		$class = new stdClass;
		foreach ( $input as $name => $value ) {
			$class->$name = $value;
		}
		return $class;
	}

	public static function parseRecursiveRelated( $input ) {
		$result = array();
		$objects = $input['restrictionValue'];
		foreach ( $objects as $object ) {
			if ( !is_array( $object ) && ( strpos( $object, 'http://' ) === 0 ) ) {
				$result[] = $object;
			} else {
				if ( array_key_exists( 'restrictionValue', $object ) ) {
					$result = array_merge( $result, self::parseRecursiveRelated( $object ) );
				}
			}
		}
		return $result;
	}
	
	public static function parseClassResult( $classResult ) {
		$classes = array();
		foreach ( $classResult as $result ) {
			if ( isset( $classes[$result['class']] ) ) {
				if ( isset( $result['subClass'] ) ) {
					$classes[$result['class']]->hasChild = true;
				}
			} else {
				$class = self::makeClass( array(
						'iri' => $result['class'],
						'label' => null,
						'type' => null,
						'hasChild' => null,
				) );
				$class->label = '';
				if (isset( $result['label'] ) ) {
					$class->label = $result['label'];
				}
				$class->hasChild = false;
				if ( isset( $result['subClass'] ) ) {
					$class->hasChild = true;
				}
				$classes[$result['class']] = $class;
			}
				
		}
		asort( $classes );
		return $classes;
	}
	
	public static function parseTermList( $termResult, $prefix, $letter ) {
		$terms = array();
		$letters = array();
		foreach( $termResult as $termIRI => $termLabels ) {
			if ( !empty( $termLabels ) ) {
				$termLabel = array_shift( $termLabels );
			} else {
				$termLabel = self::getShortTerm( $termIRI );
			}
			$termLabel = trim( $termLabel, '"\'\\\/' );
			$tmpLabel = trim( $termLabel, '\(\)\[\]\{\}' );
			$first = substr( $tmpLabel, 0, 1 );
			if ( preg_match( '/[a-z]/i', $first ) ) {
				$first =  strtoupper( $first );
			}
			
			if ( is_null( $prefix ) || $prefix == '' ) {
				$letters[$first] = null;
				if ( $first == $letter || $letter == '*' ) {
					$terms[$termIRI] = $termLabel;
				}
			} else {
				if ( preg_match( '/\/([A-Za-z\.\-_]+)#[a-zA-Z_0-9]+/', $termIRI, $match ) ) {
					if ( $prefix == $match[1] ) {
						$letters[$first] = null;
						if ( $first == $letter || $letter == '*' ) {
							$terms[$termIRI] = $termLabel;
						}
					}
				} else if ( preg_match( '/\/([A-Z][A-Za-z]+)_[-a-zA-Z_0-9]+/', $termIRI, $match ) ) {
					if ( $prefix == $match[1] ) {
						$letters[$first] = null;
						if ( $first == $letter || $letter == '*' ) {
							$terms[$termIRI] = $termLabel;
						}
					}
				} else if ( preg_match( '/\/([a-z]+)_[0-9]+/', $termIRI, $match ) ) {
					if ( $prefix == $match[1] ) {
						$letters[$first] = null;
						if ( $first == $letter || $letter == '*' ) {
							$terms[$termIRI] = $termLabel;
						}
					}
				} else {
					if ( strtolower( $prefix ) == 'noprefix' ) {
						$letters[$first] = null;
						if ( $first == $letter || $letter == '*' ) {
							$terms[$termIRI] = $termLabel;
						}
					}
				}
			}
		}
		asort( $terms );
		ksort( $letters );
		$letters = array( '*' => null ) + $letters;
		
		return array( $terms, $letters );
	}
}

?>