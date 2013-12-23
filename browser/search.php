<!--
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
-->
<!--
Author: Zuoshuang Xiang
The University Of Michigan
He Group
Date: June 2008 - March 2013
Purpose: Ontobee ontology browsing section search section index page.
-->

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html><!-- InstanceBegin template="/Templates/default.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<!-- InstanceBeginEditable name="doctitle" -->
<title>Ontobee Term Search</title>
<!-- InstanceEndEditable --><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="shortcut icon" href="/favicon.ico" />
<link href="../css/styleMain.css" rel="stylesheet" type="text/css">
<!-- InstanceBeginEditable name="head" -->
<meta charset="utf-8">
<link rel="stylesheet" href="../js/jquery/themes/base/jquery.ui.all.css">
<script src="../js/jquery/jquery-1.7.1.js"></script>
<script src="../js/jquery/ui/jquery.ui.core.js"></script>
<script src="../js/jquery/ui/jquery.ui.widget.js"></script>
<script src="../js/jquery/ui/jquery.ui.position.js"></script>
<script src="../js/jquery/ui/jquery.ui.autocomplete.js"></script>
<style>
.ui-autocomplete-loading { background: white url('../images/ui-anim_basic_16x16.gif') right center no-repeat; }
</style>
<script>
$(function() {
		function split( val ) {
			return val.split( /,\s*/ );
		}
		
		function extractLast( term ) {
			return split( term ).pop();
		}
		
		$( "#keywords" ).autocomplete({
		source: function( request, response ) {
					$.getJSON( "../getTerm.php?ontology=" + $( "#ontology" ).val(), {
						term: extractLast( request.term )
					}, response );
				},								  
		minLength: 3,
		select: function( event, ui ) {
			var params = ui.item.id.split( /:::/ );
			window.location = "/browser/rdf.php?o=" + params.shift() + "&iri=" + params.shift();
		}
	});
});
</script>
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
include_once('../inc/Classes.php');

$vali=new Validation($_REQUEST);
$keywords = $vali->getInput('keywords', 'Keywords', 1, 60, true);

$ontology = $vali->getInput('ontology', 'Ontology', 0, 60, true);

$jason=array('identifier'=> 'url', 'label'=>'name', 'items'=>array());

$array_property=array();
$array_property[]='http://www.w3.org/2000/01/rdf-schema#label';
$array_property[]='http://purl.obolibrary.org/obo/IAO_0000111';
$array_property[]='http://purl.obolibrary.org/obo/IAO_0000118';


$db = ADONewConnection($driver);
$db->Connect($host, $username, $password, $database);

$strSql="select * from ontology where loaded='y' order by ontology_fullname";
$rs=$db->Execute($strSql);


$array_ontology=array();
foreach ($rs as $row) {
	$array_ontology[$row['ontology_graph_url']]=$row['ontology_abbrv'];
}

?>
  <form id="form_term_search" action="search.php" method="get" style="padding-top:20px">
<select name="ontology" id="ontology">
<option value="" selected>Please select an ontology</option>
<?php 
foreach ($rs as $row){

?>
<option value="<?php echo $row['ontology_abbrv']?>" <?php if ($row['ontology_abbrv']==$ontology) {?> selected <?php }?>><?php echo $row['ontology_fullname']?> (<?php echo $row['ontology_abbrv']?>)</option>
<?php 
}
?>
</select>

<div class="ui-widget">
	<strong>
	<label for="keywords">Keywords: </label>
	</strong>
<input id="keywords" name="keywords" size="30" value="<?php print($keywords);?>" />
    <input type="submit" name="Submit2" value="Search terms" />
</div>
  </form>
<?php


if ($vali->getErrorMsg()=='') {
	if ($ontology!='') {
		$settings = getSettings($ontology);
		
		if (preg_match('/[a-zA-Z]+[:_]\d{7,}/', $keywords)) {
			$search_term_url='http://purl.obolibrary.org/obo/'.preg_replace('/:/', '_', $keywords);
			$querystring="SELECT   distinct ?s ?o
from <{$settings['ns_main']}>
WHERE{
?s ?p ?o .
filter (?p in (<".join('>,<', $array_property).">)).
filter (?s in (<$search_term_url>)).
}
limit 5000
";
		}
		else {
			$querystring="SELECT  distinct ?s ?o
from <{$settings['ns_main']}>
WHERE{
?s ?p ?o .
filter (?p in (<".join('>,<', $array_property).">)).
FILTER (isIRI(?s)).
?o bif:contains \"'$keywords*'\".
}
limit 5000
";
		}
	}
	else {
		if (preg_match('/[a-zA-Z]+[:_]\d{7,}/', $keywords)) {
			$search_term_url='http://purl.obolibrary.org/obo/'.preg_replace('/:/', '_', $keywords);
			$querystring="SELECT  distinct ?g ?s ?o
WHERE{
graph ?g 
{
?s ?p ?o .
filter (?p in (<".join('>,<', $array_property).">)).
filter (?s in (<$search_term_url>)).
}
}
limit 5000
";
		}
		else {
			$querystring="SELECT  distinct ?g ?s ?o
WHERE{
graph ?g 
{
?s ?p ?o .
filter (?p in (<".join('>,<', $array_property).">)).
FILTER (isIRI(?s)).
?o bif:contains \"'$keywords*'\".
}
}
limit 5000
";
		}
	}
	
//	print ($querystring);
	
	$results = json_query($querystring, $default_end_point);



	if (!empty($results)) {
		$tkeywords=preg_replace('/\W/', ' ', $keywords);

?>
<p>

Terms with '<?php echo $keywords?>' included in their label:
<ol>
<?php
		$results2=array();
		foreach($results as $result) {
			if (strtolower($result['o'])==strtolower($keywords)) {
				if (isset($result['g'])) $ontology= isset($array_ontology[$result['g']]) ? $array_ontology[$result['g']] : '';
				if ($ontology!='') {
?>
	<li><?php print(preg_replace('/('.$tkeywords.')/i', '<strong>$1</strong>', $result['o']))?> <strong>(<?php print($ontology); ?>)</strong>: <a href="/browser/rdf.php?o=<?php print($ontology); ?>&amp;iri=<?php print(myUrlEncode($result['s'])); ?>">
		<?php print($result['s'])?>
	</a></li>
<?php
				}
			}
			else {
				$results2[]=$result;
			}
		}



		$results3=array();
		foreach($results2 as $result) {
			if (strpos(strtolower($result['o']), strtolower($keywords))===0) {
				if (isset($result['g'])) $ontology= isset($array_ontology[$result['g']]) ? $array_ontology[$result['g']] : '';
				if ($ontology!='') {
?>
	<li><?php print(preg_replace('/('.$tkeywords.')/i', '<strong>$1</strong>', $result['o']))?> <strong>(<?php print($ontology); ?>)</strong>: <a href="/browser/rdf.php?o=<?php print($ontology); ?>&amp;iri=<?php print(myUrlEncode($result['s'])); ?>">
		<?php print($result['s'])?>
	</a></li>
<?php
				}
			}
			else {
				$results3[]=$result;
			}
		}


		foreach($results3 as $result) {
			if (isset($result['g'])) $ontology= isset($array_ontology[$result['g']]) ? $array_ontology[$result['g']] : '';
			if ($ontology!='') {
?>
	<li><?php print(preg_replace('/('.$tkeywords.')/i', '<strong>$1</strong>', $result['o']))?> <strong>(<?php print($ontology); ?>)</strong>: <a href="/browser/rdf.php?o=<?php print($ontology); ?>&amp;iri=<?php print(myUrlEncode($result['s'])); ?>">
		<?php print($result['s'])?>
	</a></li>
<?php
			}

		}
?>
</ol>
</p>
<?php
	}
	else {
?>
<p>No terms returned, please try different keywords.</p>
<?php
	}
}
else {
	include('../inc/input_error.php');
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
