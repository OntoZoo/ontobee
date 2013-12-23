<?php
/*
Copyright © 2013 The Regents of the University of Michigan
 
Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at
 
http://www.apache.org/licenses/LICENSE-2.0
 
Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.
 
For more information, questions, or permission requests, please contact:
Yongqun “Oliver” He - yongqunh@med.umich.edu
Unit for Laboratory Animal Medicine, Center for Computational Medicine & Bioinformatics
University of Michigan, Ann Arbor, MI 48109, USA
He Group:  http://www.hegroup.org
*/
/*
Author: Zuoshuang Xiang
The University Of Michigan
He Group
Date: June 2008 - March 2013
Purpose: Ontobee ontology browsing section xml for ontology term instance.
*/
$a_fields = array();
$querystring = "
prefix rdfs: <http://www.w3.org/2000/01/rdf-schema#>
prefix rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
prefix owl: <http://www.w3.org/2002/07/owl#>

select * 
from <{$settings['ns_main']}>

where {
?nodeID owl:annotatedSource <$iri>.
#?nodeID rdf:type owl:Annotation.
?nodeID owl:annotatedProperty ?annotatedProperty.
?nodeID owl:annotatedTarget ?annotatedTarget.
?nodeID ?aaProperty ?aaPropertyTarget.
OPTIONAL {?annotatedProperty rdfs:label ?annotatedPropertyLabel}.
OPTIONAL {?aaProperty rdfs:label ?aaPropertyLabel}.
FILTER (isLiteral(?annotatedTarget)).
FILTER (not (?aaProperty in(owl:annotatedSource, rdf:type, owl:annotatedProperty, owl:annotatedTarget)))
}";


$strQueryPrint.=$querystring. '
===================================================================		
';
$fields = array();
$fields['default-graph-uri'] = '';
$fields['format'] = 'application/sparql-results+json';
$fields['debug'] = 'on';
$fields['query'] = $querystring;

$a_fields['query_annotation_of_annotation'] = $fields;


$querystring = "
prefix rdfs: <http://www.w3.org/2000/01/rdf-schema#>
prefix rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
prefix owl: <http://www.w3.org/2002/07/owl#>

SELECT DISTINCT ?ref ?refp ?label  ?o
FROM <{$settings['ns_main']}>
WHERE {
	?ref ?refp ?o.
	FILTER (?refp IN (owl:equivalentClass, rdfs:subClassOf)).
	OPTIONAL {?ref rdfs:label ?label}.
	{
		{
			SELECT ?s ?o 
			FROM <{$settings['ns_main']}>

			WHERE {
				?o ?p ?s .
				FILTER (?p IN (rdf:first, rdf:rest, owl:intersectionOf, owl:unionOf, owl:someValuesFrom, owl:hasValue, owl:allValuesFrom, owl:complementOf, owl:inverseOf, owl:onClass, owl:onProperty)) 
			}
		}
		OPTION (TRANSITIVE, t_in(?s), t_out(?o), t_step(?s) as ?link).
		FILTER (?s= <$iri>)
	}
}

ORDER BY ?label";

$strQueryPrint.=$querystring. '
===================================================================		
';
$fields = array();
$fields['default-graph-uri'] = '';
$fields['format'] = 'application/sparql-results+json';
$fields['debug'] = 'on';
$fields['query'] = $querystring;

$a_fields['query_key_usage_results'] = $fields;


$querystring="
SELECT distinct ?g
WHERE{
graph ?g 
{
<$iri> ?p ?o
}
}
";

$strQueryPrint.=$querystring. '
===================================================================		
';
$fields = array();
$fields['default-graph-uri'] = '';
$fields['format'] = 'application/sparql-results+json';
$fields['debug'] = 'on';
$fields['query'] = $querystring;


//		print("<!--".$_SERVER['QUERY_STRING']."-->");
$a_fields['query_key_other_ontologies'] = $fields;

$other_ontologies_results = parse_json_query($a_query_results['query_key_other_ontologies']);


$a_query_results = curl_multi_post_contents($settings['remote_store_endpoint'], $a_fields);

$usage_results = parse_json_query($a_query_results['query_key_usage_results']);
$annotation_of_annotation_results = parse_json_query($a_query_results['query_annotation_of_annotation']);



//		print_r($json_term_cbd);

$a_label = array();
$a_type = array();


foreach ($term_cbd as $s => $a_po) {
	if (strpos($s, 'http://')===0) {
		$a_label[$s]=NULL;
	}
	//print_r($a_po);
	foreach ($a_po as $p => $a_obj) {
		$a_label[$p]=NULL;
		//print_r($a_o);
		foreach ($a_obj as $obj) {
			//print_r($o);
			if ($obj['type']=='uri') $a_label[$obj['value']]=NULL;
		}
	}
	//print("$key:\n");
	//print_r($values);
}



$a_fields = array();

if (!empty($a_label)) {

	$querystring = "
SELECT *

FROM <{$settings['ns_main']}>

WHERE { ?s <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> ?o.
FILTER (?s in(<http://null>, <".join('>, <', array_keys($a_label)).">))
}
";

	$strQueryPrint.=$querystring. '
===================================================================		
';
	$fields = array();
	$fields['default-graph-uri'] = '';
	$fields['format'] = 'application/sparql-results+json';
	$fields['debug'] = 'on';
	$fields['query'] = $querystring;
	
	$a_fields['query_key_cbd_types'] = $fields;


	$querystring = "
SELECT *

FROM <{$settings['ns_main']}>

WHERE { ?s <http://www.w3.org/2000/01/rdf-schema#label> ?o.
FILTER (?s in(<http://null>, <".join('>, <', array_keys($a_label)).">))
}
";

	$strQueryPrint.=$querystring. '
===================================================================		
';
	$fields = array();
	$fields['default-graph-uri'] = '';
	$fields['format'] = 'application/sparql-results+json';
	$fields['debug'] = 'on';
	$fields['query'] = $querystring;
	
	$a_fields['query_key_cbd_labels'] = $fields;
}



$usage_root_nodes=array();

if (!empty($usage_results)) {
	
	foreach ($usage_results as $usage_result) {
		$usage_root_nodes[] = $usage_result['o'];
	}
	
	
	if (!empty($usage_root_nodes)) {
//print_r($usage_root_nodes);
		
		$querystring = "
DEFINE sql:describe-mode \"CBD\" 
describe 
<".join('> <', $usage_root_nodes).">
FROM <{$settings['ns_main']}>
";


		$strQueryPrint.=$querystring. '
===================================================================		
';
		$fields = array();
		$fields['default-graph-uri'] = '';
		$fields['format'] = 'application/rdf+json';
		$fields['debug'] = 'on';
		$fields['query'] = $querystring;
		
		$a_fields['query_key_usage_cbd'] = $fields;
	}
}

$a_query_results = empty($a_fields) ? array() : curl_multi_post_contents($settings['remote_store_endpoint'], $a_fields);

$label_results = isset($a_query_results['query_key_cbd_labels']) ? parse_json_query($a_query_results['query_key_cbd_labels']) : array();
$type_results = isset($a_query_results['query_key_cbd_types']) ? parse_json_query($a_query_results['query_key_cbd_types']) : array();
$usage_cbd_results = isset($a_query_results['query_key_usage_cbd']) ? json_decode($a_query_results['query_key_usage_cbd'], true) : array();

foreach($label_results as $result) {
	$a_label[$result['s']] = $result['o'];
}

foreach($type_results as $result) {
	$a_type[$result['s']] = $result['o'];
}

foreach ($a_label as $term_uri => $label) {
	if ($label==NULL) $a_label[$term_uri]=getShortTerm($term_uri);
}

//		print_r($a_type);


if (isset($term_cbd[$iri])) {
	$result_main=$term_cbd[$iri];
	$main_label=getRDFjsonDetail($term_cbd, $iri, $preferred_label_url, $a_label, false);
	if ($main_label=='') $main_label=getRDFjsonDetail($term_cbd, $iri, $settings['ns_rdfs']. 'label', $a_label, false);
//			print("<pre>");
//			print_r($term_cbd);
//			print("</pre>");

	$a_term_type=preg_split('/, /', getRDFjsonDetail($term_cbd, $iri, $settings['ns_rdf']. 'type', $a_label, true));
	
//			print_r($a_label);
	
	
	$term_type='Instance';
	for($i=0; $i<sizeof($a_term_type); $i++) {
		if (strpos($a_term_type[$i], 'NamedIndividual')) {
			unset($a_term_type[$i]);
		}
	}
?>
<p>
<b><?php print($term_type); ?>: <?php print($main_label); ?></b>
</p>
<ul>
<li style="font-weight:bold; font-size:120%">Term IRI: <a href="<?php print(myUrlEncode($iri)); ?>"><?php print($iri); ?></a></li>
<?php 
	$tmp_iri=$settings['base_oboInOwl'] . 'Definition';
	if(isset($result_main[$tmp_iri])) {
		foreach ($result_main[$tmp_iri] as $obj) {
?>
<li><span style="color:#333333">definition</span>: <span style="color:#006600"><?php print(UTF_to_Unicode($obj['value'])); ?></span>
<?php
			foreach ($annotation_of_annotation_results as $aa_result) {
				if ($aa_result['annotatedProperty'] == $tmp_iri && $aa_result['annotatedTarget']==$obj['value']) {
?>
<span style="color:#14275D"> [<?php if (isset($aa_result['aaPropertyLabel'])) {echo $aa_result['aaPropertyLabel'];} else {echo getShortTerm($aa_result['aaProperty']);}?>: <?php echo UTF_to_Unicode($aa_result['aaPropertyTarget'])?>]</span>
<?php
				}
			}
?>


</li>
<?php
		}
	}

	$tmp_iri='http://purl.obolibrary.org/obo/IAO_0000115';
	if(isset($result_main[$tmp_iri])) {
		foreach ($result_main[$tmp_iri] as $obj) {
?>
<li><span style="color:#333333">definition</span>: <span style="color:#006600"><?php print(UTF_to_Unicode($obj['value'])); ?></span>
<?php
			foreach ($annotation_of_annotation_results as $aa_result) {
				if ($aa_result['annotatedProperty'] == $tmp_iri && $aa_result['annotatedTarget']==$obj['value']) {
?>
<span style="color:#14275D"> [<?php if (isset($aa_result['aaPropertyLabel'])) {echo $aa_result['aaPropertyLabel'];} else {echo getShortTerm($aa_result['aaProperty']);}?>: <?php echo UTF_to_Unicode($aa_result['aaPropertyTarget'])?>]</span>
<?php
				}
			}
?>
</li>
<?php
		}
	}
?>
</ul>


<?php
	if (isset($_SESSION['GALAXY_URL']) && $_SESSION['GALAXY_URL']!='') {
?>    
<div id="galaxy_form">
<form method="post" action="<?php echo $_SESSION['GALAXY_URL']?>" enctype="multipart/form-data" name="galaxyform">
<input type="hidden" name="id" value="<?php echo getShortTerm($iri)?>"></input>
<input type="hidden" name="tool_id" value="get_obo"></input>
<input type="hidden" name="URL" value="http://www.ontobee.org/browser//browser/rdf.php?o=<?php echo $o?>&amp;iri=<?php print(myUrlEncode($iri)); ?>"></input>

<b>OWL2 format</b>
<input name="submit" type="submit" value="Export to Galaxy"></input></form>
</div>    
<?php
	}

	$hasAnnotations=false;
	foreach ($a_type as $tmp_url => $tmp_type) {
		if ($tmp_type='http://www.w3.org/2002/07/owl#AnnotationProperty' && isset($result_main[$tmp_url])) {
			$hasAnnotations=true;
		}
	}
	
	if ($hasAnnotations) {
?>
<div style="font-weight:bold">Annotations</div>

<div style="background-color:#EAF1F2; border:#99CCFF 1px solid; margin-top:4px; margin-bottom:12px">
<ul>
<?php
		$def_editors=array();
		$tmp_url='http://purl.obolibrary.org/obo/IAO_0000117';
		if (isset($result_main[$tmp_url])) {
			foreach ($result_main[$tmp_url] as $annotation) {
				$def_editors[] = preg_replace('/^PERSON:/i', '', $annotation['value']);
			}
		}

		if (!empty($def_editors)) {
?>
<li><span style="color:#333333">definition editor</span>: <span style="color:#006600"><?php print(UTF_to_Unicode(join(', ', $def_editors))); ?></span></li>
<?php
		}
		
		
		$tmp_value=getRDFjsonDetail($term_cbd, $iri, $settings['base_oboInOwl'] . 'Synonym', $a_label, false);
		if ($tmp_value!='') {
?>
<li><span style="color:#333333">synonym</span>: <span style="color:#006600"><?php print($tmp_value); ?></span>
<?php
			foreach ($annotation_of_annotation_results as $aa_result) {
				if ($aa_result['annotatedProperty'] == $settings['base_oboInOwl'] . 'Synonym' && UTF_to_Unicode($aa_result['annotatedTarget'])==$tmp_value) {
?>
<span style="color:#14275D"> [<?php if (isset($aa_result['aaPropertyLabel'])) {echo $aa_result['aaPropertyLabel'];} else {echo getShortTerm($aa_result['aaProperty']);}?>: <?php echo UTF_to_Unicode($aa_result['aaPropertyTarget'])?>]</span>
<?php
				}
			}
?>
</li>
<?php
 		}

		$tmp_value=getRDFjsonDetail($term_cbd, $iri, 'http://purl.obolibrary.org/obo/IAO_0000118', $a_label, false);
		if ($tmp_value!='') {
?>
<li><span style="color:#333333">alternative term</span>: <span style="color:#006600"><?php print($tmp_value); ?></span>
<?php
			foreach ($annotation_of_annotation_results as $aa_result) {
				if ($aa_result['annotatedProperty'] == 'http://purl.obolibrary.org/obo/IAO_0000118' && UTF_to_Unicode($aa_result['annotatedTarget'])==$tmp_value) {
?>
<span style="color:#14275D"> [<?php if (isset($aa_result['aaPropertyLabel'])) {echo $aa_result['aaPropertyLabel'];} else {echo getShortTerm($aa_result['aaProperty']);}?>: <?php echo UTF_to_Unicode($aa_result['aaPropertyTarget'])?>]</span>
<?php
				}
			}
?>

</li>
<?php
 		}

		foreach ($a_label as $tmp_url =>$tmp_label) {
			if (isset($a_type[$tmp_url])
							  && $a_type[$tmp_url]=='http://www.w3.org/2002/07/owl#AnnotationProperty'
							  && !in_array($tmp_url, array($settings['ns_rdfs'] . 'label'
																	 , $settings['ns_rdf'] . 'type'
																	 , $settings['base_oboInOwl'] . 'Definition'
																	 , $settings['base_oboInOwl'] . 'Synonym'
																	 , $settings['ns_rdfs'] . 'comment'
																	 , $settings['ns_owl'].'disjointWith'
																	 , $settings['ns_rdfs'].'subClassOf'
																	 , $settings['ns_rdfs'].'seeAlso'
																	 , $settings['ns_owl'].'equivalentClass'
																	 , 'http://purl.obolibrary.org/obo/IAO_0000115'
																	 , 'http://purl.obolibrary.org/obo/IAO_0000111'
																	 , 'http://purl.obolibrary.org/obo/IAO_0000117'
																	 , 'http://purl.obolibrary.org/obo/IAO_0000118'))) {
				$tmp_value=getRDFjsonDetail($term_cbd, $iri, $tmp_url, $a_label, false);
				if ($tmp_value!='') {
?>
<li>
<span style="color:#333333"><?php print($tmp_label); ?></span>: 
<span style="color:#006600"><?php print(UTF_to_Unicode($tmp_value)); ?></span>
<?php
			foreach ($annotation_of_annotation_results as $aa_result) {
				if ($aa_result['annotatedProperty'] == $tmp_url && $aa_result['annotatedTarget']==$tmp_value) {
?>
<span style="color:#14275D"> [<?php if (isset($aa_result['aaPropertyLabel'])) {echo $aa_result['aaPropertyLabel'];} else {echo getShortTerm($aa_result['aaProperty']);}?>: <?php echo UTF_to_Unicode($aa_result['aaPropertyTarget'])?>]</span>
<?php
				}
			}
?>

</li>
<?php
				}
			}
		}
		
		
		$tmp_value=getRDFjsonDetail($term_cbd, $iri, $settings['ns_rdfs'] . 'seeAlso', $a_label, false);
		if ($tmp_value!='') {
?>
<li><span style="color:#333333">seeAlso</span>: <span style="color:#006600"><?php print($tmp_value); ?></span>
<?php
			foreach ($annotation_of_annotation_results as $aa_result) {
				if ($aa_result['annotatedProperty'] == $settings['ns_rdfs'] . 'seeAlso' && $aa_result['annotatedTarget']==$tmp_value) {
?>
<span style="color:#14275D"> [<?php if (isset($aa_result['aaPropertyLabel'])) {echo $aa_result['aaPropertyLabel'];} else {echo getShortTerm($aa_result['aaProperty']);}?>: <?php echo UTF_to_Unicode($aa_result['aaPropertyTarget'])?>]</span>
<?php
				}
			}
?>

</li>
<?php
		}


		$tmp_value=getRDFjsonDetail($term_cbd, $iri, $settings['ns_rdfs'] . 'comment', $a_label, false);
		if ($tmp_value!='') {
?>
<li><span style="color:#333333">comment</span>: <span style="color:#006600"><?php print($tmp_value); ?></span>
<?php
			foreach ($annotation_of_annotation_results as $aa_result) {
				if ($aa_result['annotatedProperty'] == $settings['ns_rdfs'] . 'comment' && $aa_result['annotatedTarget']==$tmp_value) {
?>
<span style="color:#14275D"> [<?php if (isset($aa_result['aaPropertyLabel'])) {echo $aa_result['aaPropertyLabel'];} else {echo getShortTerm($aa_result['aaProperty']);}?>: <?php echo UTF_to_Unicode($aa_result['aaPropertyTarget'])?>]</span>
<?php
				}
			}
?>

</li>
<?php
		}
?>
</ul>
</div>
<?php 			
	}


	$hasUsage = false;

//print_r($usage_nodes);
	$num_queries++;
	
	if (!empty($usage_cbd_results)) {
		
		$a_label_usage = array();

		foreach ($usage_cbd_results as $s => $a_po) {
			if (strpos($s, 'http://')===0) {
				if (!isset($a_label[$s])) {
					$a_label[$s]=NULL;
					$a_label_usage[$s]=NULL;
				}
			}
			//print_r($a_po);
			foreach ($a_po as $p => $a_obj) {
				if (!isset($a_label[$p])) {
					$a_label[$p]=NULL;
					$a_label_usage[$p]=NULL;
				}
				//print_r($a_o);
				foreach ($a_obj as $obj) {
					if ($obj['type']=='uri' && !isset($a_label[$obj['value']])) {
						$a_label[$obj['value']]=NULL;
						$a_label_usage[$obj['value']]=NULL;
					}
				}
			}
		}


		$a_fields = array();

		if (!empty($a_label_usage)) {

			$querystring = "
SELECT *

FROM <{$settings['ns_main']}>

WHERE { ?s <http://www.w3.org/2000/01/rdf-schema#label> ?o.
FILTER (?s in(<http://null>, <".join('>, <', array_keys($a_label_usage)).">))
}
";

			$strQueryPrint.=$querystring. '
===================================================================		
';
			$fields = array();
			$fields['default-graph-uri'] = '';
			$fields['format'] = 'application/sparql-results+json';
			$fields['debug'] = 'on';
			$fields['query'] = $querystring;
			
			$a_fields['query_key_usage_labels'] = $fields;
		}

		$a_query_results = curl_multi_post_contents($settings['remote_store_endpoint'], $a_fields);

		$usage_labels = isset($a_query_results['query_key_usage_labels']) ? parse_json_query($a_query_results['query_key_usage_labels']) : array();

		foreach($usage_labels as $result) {
			$a_label[$result['s']] = $result['o'];
		}

		foreach ($a_label as $term_uri => $label) {
			if ($label==NULL) $a_label[$term_uri]=getShortTerm($term_uri);
		}
		
		if (!empty($usage_results)) {
?>
<div style="font-weight:bold">Uses in this ontology</div>
<div style="background-color:#EAF1F2; border:#99CCFF 1px solid; margin-top:4px; margin-bottom:12px">
<ul>
<?php
			foreach ($usage_results as $usage_result) {
				$nodeid=$usage_result['o'];
				$label=isset($usage_result['label']) ? UTF_to_Unicode($usage_result['label']) : getShortTerm($usage_result['ref']);
				
				$obj_equiv=getRDFjsonEquivalent($usage_cbd_results, str_replace('nodeID://', '_:v', $nodeid), $a_label, $link = true);
?>
<li><a oncontextmenu="return false;" href="/browser/rdf.php?o=<?php print($o); ?>&amp;iri=<?php print(myUrlEncode($usage_result['ref'])); ?>"><?php print($label); ?></a>  <?php echo getShortTerm($usage_result['refp'])?>: <?php print(printRDFjsonEquivalent($obj_equiv, $a_label)); ?></li>
<?php
			}
?>
</ul>
</div>
<?php
		}
	}

	if (!empty($a_term_type)) {
?>
<div style="font-weight:bold">Instance of: </div>
<div style="background-color:#EAF1F2; border:#99CCFF 1px solid; margin-top:4px; margin-bottom:12px; padding:4px">
<span>
<?php print(join(', ', $a_term_type));?>
</span>
</div>
<?php
	}




	for($i=sizeof($other_ontologies_results)-1; $i>-1; $i--) {
		if (!isset($array_ontology[$other_ontologies_results[$i]['g']])) unset($other_ontologies_results[$i]);
		elseif  ($array_ontology[$other_ontologies_results[$i]['g']]['ontology_abbrv']==$o)  unset($other_ontologies_results[$i]);
	}
	
	$original_o='';
	if (preg_match('/\/([a-zA-Z]+)_(\d+)$/', $iri, $match)) {
		if ($match[1]!=$o) {
			$original_o=$match[1];
			
			if (preg_match('/\/IDO_01(\d+)$/', $iri, $match)) $original_o='IDOBRU';
			
			foreach ($array_ontology as $tmp_ontology_g => $tmp_ontology) {
				if ($tmp_ontology['ontology_abbrv']==$original_o) {
					$owl_file_url=$tmp_ontology['ontology_url'];
					if ($tmp_ontology['download']!='') $owl_file_url=$tmp_ontology['download'];
					$owl_file_name=getShortTerm($owl_file_url);
?>
<div style="font-weight:bold"> Ontology in which the Instance is published</div>
<div style="background-color:#EAF1F2; border:#99CCFF 1px solid; margin-top:4px; margin-bottom:12px">

<table cellpadding="4" cellspacing="1" bgcolor="#888888">
  <tr>
    <td bgcolor="#EAF1F2" style="font-weight:bold">Ontology listed in Ontobee</td>
    <td bgcolor="#EAF1F2" style="font-weight:bold">Ontology OWL file</td>
    <td bgcolor="#EAF1F2" style="font-weight:bold">View instance in context</td>
    <td bgcolor="#EAF1F2" style="font-weight:bold">Project home page</td>
    </tr>
  <tr>
    <td bgcolor="#EAF1F2"><a href="/browser/index.php?o=<?php print($original_o); ?>"><?php print($tmp_ontology['ontology_fullname']); ?></a></td>
    <td bgcolor="#EAF1F2"><a href="<?php echo $owl_file_url?>"><?php echo $owl_file_name?></a> </td>
    <td bgcolor="#EAF1F2"><a oncontextmenu="return false;" href="/browser/rdf.php?o=<?php print($original_o); ?>&amp;iri=<?php print(myUrlEncode($iri)); ?>">'<?php print($main_label); ?>' in <?php echo $owl_file_name?></a></td>
    <td bgcolor="#EAF1F2">
  <?php						
					if ($tmp_ontology['home']!='') {
						$tokens=preg_split('/\|/', $tmp_ontology['home']);
						if (sizeof($tokens)==2) {
?>
  <a href="<?php print($tokens[1]);?>"><?php print($tokens[0]);?></a>
  <?php
				 		}
						else {
?>
  <a href="<?php print($tokens[0]);?>">Project home page</a>
  <?php
				 		}
					}

 ?>
    </td>
    </tr>
</table>


</div>
<?php			
					
					break;
				}
			}
		}
	}
	
	if (!empty($other_ontologies_results)) {
?>
<div style="font-weight:bold">Ontologies that use the Instance</div>
<div style="background-color:#EAF1F2; border:#99CCFF 1px solid; margin-top:4px; margin-bottom:12px">

<table cellpadding="4" cellspacing="1" bgcolor="#888888">
  <tr>
    <td bgcolor="#EAF1F2" style="font-weight:bold">Ontology listed in Ontobee</td>
    <td bgcolor="#EAF1F2" style="font-weight:bold">Ontology OWL file</td>
    <td bgcolor="#EAF1F2" style="font-weight:bold">View instance in context</td>
    <td bgcolor="#EAF1F2" style="font-weight:bold">Project home page</td>
    </tr>
<?php
		foreach ($other_ontologies_results as $other_ontology) {
			$other_ontology=$array_ontology[$other_ontology['g']];
			if ($other_ontology['ontology_abbrv']!=$original_o) {
				$owl_file_url=$other_ontology['ontology_url'];
				if ($other_ontology['download']!='') $owl_file_url=$other_ontology['download'];
				$owl_file_name=getShortTerm($owl_file_url);
?>
  <tr>
    <td bgcolor="#EAF1F2"><a href="/browser/index.php?o=<?php print($other_ontology['ontology_abbrv']); ?>"><?php print($other_ontology['ontology_fullname']); ?></a></td>
    <td bgcolor="#EAF1F2"><a href="<?php echo $owl_file_url?>"><?php echo $owl_file_name?></a></td>
    <td bgcolor="#EAF1F2"><a oncontextmenu="return false;" href="/browser/rdf.php?o=<?php print($other_ontology['ontology_abbrv']); ?>&amp;iri=<?php print(myUrlEncode($iri)); ?>">'<?php print($main_label); ?>' in <?php echo $owl_file_name?></a></td>
    <td bgcolor="#EAF1F2">
  <?php						
					if ($other_ontology['home']!='') {
						$tokens=preg_split('/\|/', $other_ontology['home']);
						if (sizeof($tokens)==2) {
?>
  <a href="<?php print($tokens[1]);?>"><?php print($tokens[0]);?></a>
  <?php
				 		}
						else {
?>
  <a href="<?php print($tokens[0]);?>">Project home page</a>
  <?php
				 		}
					}

 ?>
    </td>
    </tr>
<?php
			}
		}
?>
</table>

</div>
<?php
	}

	
}
?>

