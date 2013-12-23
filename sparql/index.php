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
Purpose: Ontobee Sparql query index page.
-->

<?php
include_once('../inc/Classes.php');
$vali=new Validation($_REQUEST);

$query = $vali->getInput('query', 'query', 0, 1024*8, true);
$format = $vali->getInput('format', 'format', 0, 128, true);
$maxrows = $vali->getInput('maxrows', 'maxrows', 0, 128, true);
$go = $vali->getInput('go', 'go', 0, 128, true);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html><!-- InstanceBegin template="/Templates/default.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<!-- InstanceBeginEditable name="doctitle" -->
<title>Ontobee SPARQL</title>
<!-- InstanceEndEditable --><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="shortcut icon" href="/favicon.ico" />
<link href="../css/styleMain.css" rel="stylesheet" type="text/css">
<!-- InstanceBeginEditable name="head" -->

<link rel="stylesheet" href="default.css" type="text/css"/>
<link type="text/css" rel="stylesheet" href="SyntaxHighlighter.css">
</link>
<script type="text/javascript">
      var toolkitPath="toolkit"; 
      var featureList=["tab","ajax2","combolist","window","tree","grid","dav","xml"];

function eg1() {
	document.getElementById("query").value="#This is the first example:\n#Aim: To find all class-containing ontology graphs\n#Max Rows by default: 10 (this can be changed)\n\nprefix rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>\nprefix owl: <http://www.w3.org/2002/07/owl#>\nSELECT distinct ?graph_uri\nWHERE {\nGRAPH ?graph_uri\n{ ?s rdf:type owl:Class }\n}";
}

function eg2() {
	document.getElementById("query").value="#Example 2: \n#To find all subclasses of an ontology term\n\nPREFIX obo-term: <http://purl.obolibrary.org/obo/>\nSELECT DISTINCT ?x ?label\nfrom <http://purl.obolibrary.org/obo/merged/OAE>\nWHERE\n{\n?x rdfs:subClassOf obo-term:OAE_0000001.\n?x rdfs:label  ?label.\n}\n";
}

function eg3() {
	document.getElementById("query").value="#Example 3: \n#To find the number of all class terms of an ontology\n#To find all object properties, use owl:ObjectProperty instead of owl:Class\n#To find all Datatype properties, use owl:AnnotationProperty instead of owl:Class\n#To find all Annotation properties, use owl:DatatypeProperty instead of owl:Class\n\nSELECT count(?s) as ?VO_class_count\nFROM <http://purl.obolibrary.org/obo/merged/VO>\nWHERE\n{\n?s a owl:Class .\n?s rdfs:label ?label .\n\nFILTER regex( ?s, \"VO_\" )\n}\n";
}

function eg4() {
	document.getElementById("query").value="#Example 4: \n#To retrieve the definitions of all classes that have definitions in an ontology\n#The OBO IAO ontology annotation terms IAO_0000115 (\"definition\") and IAO_0000117 (\"author\") are used. \n\nPREFIX obo-term: <http://purl.obolibrary.org/obo/>\nSELECT ?s ?label ?definition ?author\nFROM <http://purl.obolibrary.org/obo/merged/VO>\n{\n?s a owl:Class .\n?s rdfs:label ?label .\n?s obo-term:IAO_0000115 ?definition .\n?s obo-term:IAO_0000117 ?author .\n}\n";
}


</script>
<script type="text/javascript" src="toolkit/loader.js"></script>
<script type="text/javascript" src="sparql_ajax.js"></script>
<script type="text/javascript">
      var go_to = 0;
</script>
<script type="text/javascript" src="syntax/shCore.js"></script>
<script type="text/javascript" src="syntax/shBrushXml.js"></script>
<script type="text/javascript" src="syntax/shBrushJScript.js"></script>

<script type="text/javascript">
<?php
if ($go!='') {
?>
window.onload=QueryExec;
<?php
}
?>
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
<div id="topnav"><a href="../index.php" class="topnav">Home</a><a href="../introduction.php" class="topnav">Introduction</a><a href="../ontostat/index.php" class="topnav">Statistics</a><a href="index.php" class="topnav">SPARQL</a><a href="../ontobeep/index.php" class="topnav">Ontobeep</a><a href="../tutorial/index.php" class="topnav">Tutorial</a><a href="../faqs.php" class="topnav">FAQs</a><a href="../references.php" class="topnav">References</a><a href="../links.php" class="topnav">Links</a><a href="../contactus.php" class="topnav">Contact</a><a href="../acknowledge.php" class="topnav">Acknowledge</a><a href="../news.php" class="topnav">News</a></div>
<div id="mainbody">
<!-- InstanceBeginEditable name="Main" -->
<div class="main_col_page" id="page_query">
	<h2>Ontobee SPARQL Query</h2>
<p><strong>To programmatically query our SPARQL endpoint, please visit URL <a href="http://sparql.hegroup.org/sparql/">http://sparql.hegroup.org/sparql/</a>.</strong></p>

	<form>
	  <input id="remote" type="hidden" name="remote" value="y"/>
		<input id="service" type="hidden" name="service" value="http://sparql.hegroup.org/sparql"/>
		<input id="default-graph-uri" type="hidden" name="default-graph-uri" value=""/>
		<div id="topbox_ctl">
			<select name="prefix" id="prefix" onchange="prefix_insert()">
				<option value="">-- Prefixes --</option>
				<option value="PREFIX cc: &lt;http://web.resource.org/cc/&gt;">CC</option>
				<option value="PREFIX dataview: &lt;http://www.w3.org/2003/g/data-view#&gt;">DATAVIEW</option>
				<option value="PREFIX dc: &lt;http://purl.org/dc/elements/1.1/&gt;">DC</option>
				<option value="PREFIX dcterms: &lt;http://purl.org/dc/terms/&gt;">DCTERMS</option>
				<option value="PREFIX foaf: &lt;http://xmlns.com/foaf/0.1/&gt;">FOAF</option>
				<option value="PREFIX owl: &lt;http://www.w3.org/2002/07/owl#&gt;">OWL</option>
				<option value="PREFIX rdf: &lt;http://www.w3.org/1999/02/22-rdf-syntax-ns#&gt;">RDF</option>
				<option value="PREFIX rdfs: &lt;http://www.w3.org/2000/01/rdf-schema#&gt;">RDFS</option>
				<option value="PREFIX rss: &lt;http://purl.org/rss/1.0/&gt;">RSS</option>
				<option value="PREFIX sioc: &lt;http://rdfs.org/sioc/ns#&gt;">SIOC</option>
				<option value="PREFIX sioct: &lt;http://rdfs.org/sioc/types#&gt;">SIOCT</option>
				<option value="PREFIX skos: &lt;http://www.w3.org/2004/02/skos/core#&gt;">SKOS</option>
				<option value="PREFIX vs: &lt;http://www.w3.org/2003/06/sw-vocab-status/ns#&gt;">VS</option>
				<option value="PREFIX wot: &lt;http://xmlns.com/wot/0.1/&gt;">WOT</option>
				<option value="PREFIX xhtml: &lt;http://www.w3.org/1999/xhtml&gt;">XHTML</option>
				<option value="PREFIX xsd: &lt;http://www.w3.org/2001/XMLSchema#&gt;">XSD</option>
			</select>
			<select name="template" id="template" onchange="template_insert()">
				<option value="">-- Template --</option>
				<option value="SELECT DISTINCT ?s ?p ?o
WHERE
{
   ?s ?p ?o .
}">Select</option>
				<option value="CONSTRUCT
{
   ?s ?p ?o .
}
WHERE
{
   ?s ?p ?o .
}">Construct</option>
				<option value="ASK
WHERE
{
   ?s ?p ?o .
}">Ask</option>
			</select>
			<select name="tool" id="tool" onchange="tool_invoke()">
				<option value="">-- Statement Help --</option>
				<option value="tool_put_line_start('#')">Comment Selection</option>
				<option value="tool_rem_line_start('#')">Uncomment Selection</option>
				<option value="tool_put_line_start('    ')">Indent Selection</option>
				<option value="tool_rem_line_start('    ')">Remove Indent Selection</option>
				<option value="tool_put_around('OPTIONAL\n{\n','\n}\n')">Make Optional </option>
				<option value="tool_put('BASE <http://example.org/base>')">put BASE</option>
				<option value="tool_put('FROM <http://example.org/from>')">put FROM</option>
				<option value="tool_put('FROM NAMED <http://example.org/named>')">put FROM NAMED</option>
				<option value="tool_put('UNION\n')">put UNION</option>
				<option value="tool_put('GRAPH')">put GRAPH</option>
				<option value="tool_put('ORDER BY')">put ORDER BY</option>
				<option value="tool_put('ORDER BY ASC(?x)')">put ORDER BY ASC()</option>
				<option value="tool_put('ORDER BY DESC(?x)')">put ORDER BY DESC()</option>
				<option value="tool_put('LIMIT 10')">put LIMIT</option>
				<option value="tool_put('OFFSET 10')">put OFFSET</option>
				<option value="tool_put('FILTER ( ?x < 3 ) .')">put Simple Filter</option>
				<option value="tool_put('FILTER regex( str(?name), &quot;Jane&quot;, &quot;i&quot; ) .')">put Regexp Filter</option>
				<option value="tool_put('FILTER ( bound(?x) ) .')">put Bound Filter</option>
				<option value="tool_put('FILTER ( ?date > &quot;2005-01-01T00:00:00Z&quot;^^xsd:dateTime ) .')">put Date Filter</option>
			</select>
<a href="javascript:eg1();">Example 1</a>, <a href="javascript:eg2();">Example 2</a>, <a href="javascript:eg3();">Example 3</a>, <a href="javascript:eg4();">Example 4</a>
		<br/>
	    <textarea id="query" name="query" onChange="format_select(this)" onKeyUp="format_select(this)" wrap="off">
<?php 
if ($query!='') {
	print($query);
}
else{
?>
#To find all class-containing ontology graphs 
#Max Rows by default: 10 (this can be changed)

prefix rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#> 
prefix owl: <http://www.w3.org/2002/07/owl#> 
SELECT distinct ?graph_uri 
WHERE { 
GRAPH ?graph_uri 
{ ?s rdf:type owl:Class } 
}
<?php 
}
?>
        </textarea>
      <br/>
		<label for="format">Output format</label>
		<select id="format" onchange="format_change()">
			<option value="auto" <?php if ($format=='auto') {?> selected<?php }?>>Auto</option>
			<option value="" <?php if ($format=='') {?> selected<?php }?>>Table</option>
			<option value="application/sparql-results+xml" <?php if ($format=='application/sparql-results+xml') {?> selected<?php }?>>XML</option>
			<option value="application/sparql-results+json" <?php if ($format=='application/sparql-results+json') {?> selected<?php }?>>JSON</option>
			<option value="application/javascript" <?php if ($format=='application/javascript') {?> selected<?php }?>>Javascript</option>
			<option value="text/html" <?php if ($format=='text/html') {?> selected<?php }?>>HTML</option>
		</select>
		<label for="maxrows">Max Rows</label>
		<select id="maxrows">
			<option value="10" <?php if ($maxrows=='10') {?> selected<?php }?>>10</option>
			<option value="20" <?php if ($maxrows=='20') {?> selected<?php }?>>20</option>
			<option value="50" <?php if ($maxrows=='50') {?> selected<?php }?>>50</option>
			<option value="100" <?php if ($maxrows=='100') {?> selected<?php }?>>100</option>
			<option value="200" <?php if ($maxrows=='200') {?> selected<?php }?>>200</option>
		</select>
		<br/>
		<div style="height:1px;"></div>
		<!-- IE hack -->
		<input type="button" value="Run Query" onclick="QueryExec()">
		<input type="reset" value="Reset" onclick="reset_click()">
		<br/>
		<br/>
	</form>
	<div id="res_area"></div>
	<div id="etalon"></div>
</div>
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
