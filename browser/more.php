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
Purpose: Ontobee browsing section ontology query function page.
*/

$mtime1=microtime(true);
include_once('../inc/Classes.php');

$vali=new Validation($_REQUEST);

$o = $vali->getInput('o', 'Ontology', 1, 60, true);
$iri = $vali->getInput('iri', 'Term IRI', 2, 100, true);

$main_label='';
$ontology_full_name='';
$term_short_id=str_replace('http://purl.obolibrary.org/obo/', 'obo:', $iri);

if ($vali->getErrorMsg()=='') {

	$term_type='';
	$num_queries = 0;
	$settings = getSettings($o);
	$ontology_full_name=$settings['ontology_fullname'];
	
	$all_graphs=array($settings['ns_main']=>1);
	
	$strQueryPrint='';
	$iri = myUrlDecode($iri);
	
	$querystring = "
DEFINE sql:describe-mode \"CBD\" 
describe <$iri>
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
	
	$query_results = curl_post_contents($settings['remote_store_endpoint'], $fields);

//virtuoso bug??
	$query_results=preg_replace('/\'\);\ndocument.writeln\(\'/', '', $query_results);
	$term_cbd = json_decode($query_results, true);

	if (isset($term_cbd[$iri])) {
		$main_label=getRDFjsonDetail($term_cbd, $iri, $settings['ns_rdfs']. 'label', array(), false);
		$a_term_type=preg_split('/, /', getRDFjsonDetail($term_cbd, $iri, $settings['ns_rdf']. 'type', array(), false));

		$term_type='Instance';
		
		for($i=0; $i<sizeof($a_term_type); $i++) {
			if (in_array($a_term_type[$i], array('Class', 'ObjectProperty', 'DatatypeProperty', 'AnnotationProperty'))) {
				$term_type=$a_term_type[$i];
				unset($a_term_type[$i]);
				break;
			}
		}
		
		
		if ($term_type=='') {
			for($i=0; $i<sizeof($a_term_type); $i++) {
				if (in_array($a_term_type[$i], array('TransitiveProperty', 'SymmetricProperty', 'AsymmetricProperty', 'IrreflexiveProperty', 'ReflexiveProperty', 'InverseFunctionalProperty'))) {
					$term_type='ObjectProperty';
					break;
				}
			}
			
		}
	}
	
	$db = ADONewConnection($driver);
	$db->Connect($host, $username, $password, $database);
	
	$strSql="select * from ontology where loaded='y'";
	$rs=$db->Execute($strSql);
	
	$terms=array();
	
	$array_ontology=array();
	foreach ($rs as $row) {
		$array_ontology[$row['ontology_graph_url']]=$row;
	}
	
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"><html><!-- InstanceBegin template="/Templates/default.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<!-- InstanceBeginEditable name="doctitle" -->
<title>Ontobee</title>
<!-- InstanceEndEditable --><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="shortcut icon" href="/favicon.ico" />
<link href="../css/styleMain.css" rel="stylesheet" type="text/css">
<!-- InstanceBeginEditable name="head" -->

<!-- InstanceEndEditable -->
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-4869243-9']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</head>

<body>
<div id="topbanner"><a href="/index.php" style="font-size:36px; color:#111144; text-decoration:none"><img src="../images/logo.gif" alt="Logo" width="280" height="49" border="0"></a></div>
<div id="topnav"><a href="../index.php" class="topnav">Home</a><a href="../introduction.php" class="topnav">Introduction</a><a href="../ontostat/index.php" class="topnav">Statistics</a><a href="../sparql/index.php" class="topnav">SPARQL</a><a href="../ontobeep/index.php" class="topnav">Ontobeep</a><a href="../tutorial/index.php" class="topnav">Tutorial</a><a href="../faqs.php" class="topnav">FAQs</a><a href="../references.php" class="topnav">References</a><a href="../links.php" class="topnav">Links</a><a href="../contactus.php" class="topnav">Contact</a><a href="../acknowledge.php" class="topnav">Acknowledge</a><a href="../news.php" class="topnav">News</a></div>
<div id="mainbody">
<!-- InstanceBeginEditable name="Main" -->
  <?php
if ($vali->getErrorMsg()=='') {

$a_fields = array();

$querystring = "
PREFIX rdf: <{$settings['ns_rdf']}>
PREFIX rdfs: <{$settings['ns_rdfs']}>

PREFIX owl: <{$settings['ns_owl']}>

SELECT DISTINCT ?s ?o ?sc
FROM <{$settings['ns_main']}>


WHERE { 
{
?s rdfs:subClassOf <$iri> .
FILTER (isIRI(?s)).
OPTIONAL {?s rdfs:label ?o} .
OPTIONAL {?sc rdfs:subClassOf ?s}
}
UNION
{
?s owl:equivalentClass ?s1 .
FILTER (isIRI(?s)).
?s1 owl:intersectionOf ?s2 .
?s2 rdf:first <$iri> .
OPTIONAL {?s rdfs:label ?o} .
OPTIONAL {?sc rdfs:subClassOf ?s}
}
UNION
{
?s rdfs:subClassOf <$iri> .
FILTER (isIRI(?s)).
OPTIONAL {?s rdfs:label ?o} .
OPTIONAL {?sc owl:equivalentClass ?s1 .
?s1 owl:intersectionOf ?s2 .
?s2 rdf:first ?s}
}
UNION
{
?s owl:equivalentClass ?s1 .
FILTER (isIRI(?s)).
?s1 owl:intersectionOf ?s2 .
?s2 rdf:first <$iri> .
OPTIONAL {?s rdfs:label ?o} .
OPTIONAL {?sc owl:equivalentClass ?s3 .
?s3 owl:intersectionOf ?s4 .
?s4 rdf:first ?s}
}
}";

$strQueryPrint.=$querystring. '
===================================================================		
';
$fields = array();
$fields['default-graph-uri'] = '';
$fields['format'] = 'application/sparql-results+json';
$fields['debug'] = 'on';
$fields['query'] = $querystring;


$a_fields['query_key_sub_class_results'] = $fields;


$a_query_results = curl_multi_post_contents($settings['remote_store_endpoint'], $a_fields);

$sub_class_results = parse_json_query($a_query_results['query_key_sub_class_results']);


if (isset($term_cbd[$iri])) {

?>
<p>
<b>Sub <?php print($term_type); ?> of <?php print($main_label); ?>:</b>
</p>



<?php
	$subClasses=array();
	$hasC=array();
	foreach ($sub_class_results as $result) {
		$subClasses[$result['s']]=isset($result['o']) ? $result['o'] : '';
		if (isset($result['sc'])) $hasC[$result['s']]=true;
	}
	

	if(!empty($subClasses)) {
		asort($subClasses);
?>
<div>
<ul style="list-style-type:none; padding-left:0; margin-left:20px;">
<?php
//				if(sizeof($subClasses)>50) $subClasses = array_slice($subClasses, 0, 50, true);
			foreach ($subClasses as $subClass_url => $subClass_label) {
				if ($subClass_label=='') $subClass_label=getShortTerm($subClass_url);
				if (isset($hasC[$subClass_url])) {
?>
<li>+<a oncontextmenu="return false;" href="/browser/rdf.php?o=<?php print($o); ?>&amp;iri=<?php print(myUrlEncode($subClass_url)); ?>"><?php print(UTF_to_Unicode($subClass_label)); ?></a></li>
<?php
				}
			}

			$term_count=0;
			foreach ($subClasses as $subClass_url => $subClass_label) {
				if ($subClass_label=='') $subClass_label=getShortTerm($subClass_url);
				if (!isset($hasC[$subClass_url]) ) {
					$term_count++;
?>
<li>-<a oncontextmenu="return false;" href="/browser/rdf.php?o=<?php print($o); ?>&amp;iri=<?php print(myUrlEncode($subClass_url)); ?>"><?php print(UTF_to_Unicode($subClass_label)); ?></a></li>
<?php
				}
				
			}
?>
</ul>
</div>
<?php
		}
?>

<?php
	}
}
?>

<!-- InstanceEndEditable -->
</div>
<div id="footer">
  <div id="footer_hl"></div>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><div id="footer_left"><a href="http://www.hegroup.org" target="_blank">He Group</a><br>
University of Michigan Medical School<br>
Ann Arbor, MI 48109</div></td>
		<td width="300"><div id="footer_right"><a href="http://www.umich.edu" target="_blank"><img src="../images/wordmark_m_web.jpg" alt="UM Logo" width="166" height="20" border="0"></a></div></td>
	</tr>
</table>
</div>
</body>
<!-- InstanceEnd --></html>
