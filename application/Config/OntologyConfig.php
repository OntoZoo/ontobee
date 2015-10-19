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
 * @file OntologyConfig.php
 * @author Edison Ong
 * @since Sep 3, 2015
 * @comment 
 */

$GLOBALS['show_query'] = true;

$GLOBALS['obo_registry'] = 'https://obofoundry.github.io/registry/ontologies';

$GLOBALS['search']['property'] = array(
	'http://www.w3.org/2000/01/rdf-schema#label',
	'http://purl.obolibrary.org/obo/IAO_0000111',
	'http://purl.obolibrary.org/obo/IAO_0000118',
	'http://www.geneontology.org/formats/oboInOwl#hasExactSynonym',
	'http://www.geneontology.org/formats/oboInOwl#hasRelatedSynonym',
	'http://www.geneontology.org/formats/oboInOwl#hasNarrowSynonym',
	'http://www.geneontology.org/formats/oboInOwl#hasBroadSynonym',
);
$GLOBALS['endpoint'] = array(
	'search' => "http://sparql.hegroup.org/sparql",
	'default' => "http://sparql.hegroup.org/sparql",
);


$GLOBALS['ontology'] = array();

$GLOBALS['ontology']['term_max_per_page'] = array( 50, 100, 200, 500 );

$GLOBALS['ontology']['namespace'] = array(
	'obo' => 'http://purl.obolibrary.org/obo/',
	'rdf' => 'http://www.w3.org/1999/02/22-rdf-syntax-ns#',
	'rdfs' => 'http://www.w3.org/2000/01/rdf-schema#',
	'owl' => 'http://www.w3.org/2002/07/owl#',
	'oboInOwl' => 'http://www.geneontology.org/formats/oboInOwl#',
	'dc' => 'http://purl.org/dc/elements/1.1/',
);

$GLOBALS['ontology']['label']['priority'] = array(
	'http://purl.obolibrary.org/obo/IAO_0000589',
	'http://www.w3.org/2000/01/rdf-schema#label',
);

$GLOBALS['ontology']['definition']['priority'] = array(
	'http://www.geneontology.org/formats/oboInOwl#Definition',
	'http://purl.obolibrary.org/obo/IAO_0000115',
);

$GLOBALS['ontology']['hierarchy']['sibhasmax'] = 10;
$GLOBALS['ontology']['hierarchy']['sibnomax'] =10;
$GLOBALS['ontology']['hierarchy']['subhasmax'] = 10;
$GLOBALS['ontology']['hierarchy']['subnomax'] =10;

$GLOBALS['ontology']['annotation']['main']['text'] = array(
	'creator', 
	'contributor',
);
$GLOBALS['ontology']['annotation']['main']['list'] = array(
	'versionIRI',
	'title',
	'description',
	'subject',
	'format',
	'versionInfo',
	'date',
	'comment',
);
$GLOBALS['ontology']['annotation']['ignore'] = array(
	'http://www.w3.org/2002/07/owl#NamedIndividual',
);

$GLOBALS['ontology']['restriction'] = array();
$GLOBALS['ontology']['restriction']['operation'] = array(
	'and' => 'http://www.w3.org/2002/07/owl#intersectionOf',
	'or' => 'http://www.w3.org/2002/07/owl#unionOf',
	'not' => 'http://www.w3.org/2002/07/owl#complementOf',
);
$GLOBALS['ontology']['restriction']['type'] = array(
	'some' => 'http://www.w3.org/2002/07/owl#someValuesFrom',
	'only' => 'http://www.w3.org/2002/07/owl#allValuesFrom',
	'value' => 'http://www.w3.org/2002/07/owl#hasValue',
);
$GLOBALS['ontology']['restriction']['list'] = array(
	'first' =>'http://www.w3.org/1999/02/22-rdf-syntax-ns#first',
	'rest' =>'http://www.w3.org/1999/02/22-rdf-syntax-ns#rest',
);
$GLOBALS['ontology']['restriction']['onProperty'] = 'http://www.w3.org/2002/07/owl#onProperty';
$GLOBALS['ontology']['restriction']['nil'] = 'http://www.w3.org/1999/02/22-rdf-syntax-ns#nil';

$GLOBALS['ontology']['type'] = array(
	'Class' => 'http://www.w3.org/2002/07/owl#Class',
	'ObjectProperty' => 'http://www.w3.org/2002/07/owl#ObjectProperty',
	'DatatypeProperty' => 'http://www.w3.org/2002/07/owl#DatatypeProperty',
	'AnnotationProperty' => 'http://www.w3.org/2002/07/owl#AnnotationProperty',
	'Instance' => 'http://www.w3.org/2002/07/owl#NamedIndividual',
);


$GLOBALS['ontology']['top_level_term'] = array(
	'http://www.w3.org/2002/07/owl#Thing' => 'Class',
	'http://www.w3.org/2002/07/owl#topDataProperty' => 'DatatypeProperty',
	'http://www.w3.org/2002/07/owl#topObjectProperty' => 'ObjectProperty',
);

$GLOBALS['alias']['type'] = array(
	'http://www.w3.org/2002/07/owl#TransitiveProperty' => 'http://www.w3.org/2002/07/owl#ObjectProperty',
	'http://www.w3.org/2002/07/owl#SymmetricProperty' => 'http://www.w3.org/2002/07/owl#ObjectProperty',
	'http://www.w3.org/2002/07/owl#AsymmetricProperty' => 'http://www.w3.org/2002/07/owl#ObjectProperty',
	'http://www.w3.org/2002/07/owl#IrreflexiveProperty' => 'http://www.w3.org/2002/07/owl#ObjectProperty',
	'http://www.w3.org/2002/07/owl#ReflexiveProperty' => 'http://www.w3.org/2002/07/owl#ObjectProperty',
	'http://www.w3.org/2002/07/owl#InverseFunctionalProperty' => 'http://www.w3.org/2002/07/owl#ObjectProperty',
);

?>