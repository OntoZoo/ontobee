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
Purpose: Ontobee ontology browsing section ontology information page.
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
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $term_short_id?>, <?php echo $main_label?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link rel="alternate" type="application/rdf+xml" title="RDF Version" href="/browser/rdf.php?o=<?php echo $o?>&amp;iri=<?php echo myUrlEncode($iri)?>" />
<link rel="canonical" href="<?php echo $iri?>"/>
<link href="/css/styleMain.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="../js/jquery/themes/base/jquery.ui.all.css"/>
<script src="../js/jquery/jquery-1.7.1.js" type="text/javascript"></script>
<script src="../js/jquery/ui/jquery.ui.core.js" type="text/javascript"></script>
<script src="../js/jquery/ui/jquery.ui.widget.js" type="text/javascript"></script>
<script src="../js/jquery/ui/jquery.ui.position.js" type="text/javascript"></script>
<script src="../js/jquery/ui/jquery.ui.autocomplete.js" type="text/javascript"></script>
<script src="../js/about.js" type="text/javascript"></script>
</head>

<body>
<div id="topbanner"><a href="/index.php" style="font-size:36px; color:#111144; text-decoration:none"><img src="../images/logo.gif" alt="Logo" width="280" height="49" border="0"/></a></div>
<div id="topnav"><a href="../index.php" class="topnav">Home</a><a href="../introduction.php" class="topnav">Introduction</a><a href="../ontostat/index.php" class="topnav">Statistics</a><a href="../ontobeep/index.php" class="topnav">Ontobeep</a><a href="../sparql/index.php" class="topnav">SPARQL</a><a href="../tutorial/index.php" class="topnav">Tutorial</a><a href="../faqs.php" class="topnav">FAQs</a><a href="../references.php" class="topnav">References</a><a href="../links.php" class="topnav">Links</a><a href="../contactus.php" class="topnav">Contact</a><a href="../acknowledge.php" class="topnav">Acknowledge</a><a href="../news.php" class="topnav">News</a></div>
<div id="mainbody">
<?php
if ($vali->getErrorMsg()=='') {
?>
<h3 align="center"><a href="/browser/index.php?o=<?php echo $o?>"> <?php print($settings['ontology_fullname']); ?></a></h3>
  <form id="form_term_search" action="index.php" method="get" style="padding-top:20px">
<div class="ui-widget">
	<strong>
	<label for="keywords">Keywords: </label>
	</strong>
<input id="keywords" name="keywords" size="30" value="" />
    <input type="submit" name="Submit2" value="Search terms" />
    <input name="o" type="hidden" id="ontology" value="<?php print($o); ?>" />
</div>
  </form>


<?php
	if (isset($term_cbd[$iri])) {
		if ($term_type=='Instance' ) {
			include('xslt_instance.php');
		}
		elseif ($term_type=='Class') {
			include('xslt_class.php');
		}
		else {
			include('xslt_property.php');
		}
	}
?>
    <div><a href="javascript:switch_sparql();" id="href_switch_sparql">Show SPARQL queries used in this page</a></div>
    <div id="div_sparql" style="display:none">
    <pre>
<?php if(!isset($show_html)) print (htmlspecialchars($strQueryPrint));?>
    </pre>
    </div>

	<div style="color:#CCCCCC; margin-top:20px">Page generated in <?php printf("%.2f", microtime(true)-$mtime1); ?>s. SPARQL endpoint: <?php print($settings['remote_store_endpoint']); ?>.
	</div>

<?php
}
?>
</div>
<div id="footer">
  <div id="footer_hl"></div>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><div id="footer_left"><a href="http://www.hegroup.org" target="_blank">He Group</a><br/>
University of Michigan Medical School<br/>
Ann Arbor, MI 48109</div></td>
		<td width="300"><div id="footer_right"><a href="http://www.umich.edu" target="_blank"><img src="../images/wordmark_m_web.jpg" alt="UM Logo" width="166" height="20" border="0"/></a></div></td>
	</tr>
</table>
</div>

</body>
</html>
