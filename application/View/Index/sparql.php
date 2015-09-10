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
 * @file sparql.php
 * @author Edison Ong
 * @since Sep 8, 2015
 * @comment 
 */
 
if (!$this) {
	exit(header('HTTP/1.0 403 Forbidden'));
}

?>

<?php require TEMPLATE . 'header.sparql.php'; ?>

<link href="<?php echo SITEURL; ?>public/css/sparql/default.css" rel="stylesheet" type="text/css">
<link href="<?php echo SITEURL; ?>public/css/sparql/SyntaxHighlighter.css" rel="stylesheet" type="text/css">



<script src="<?php echo SITEURL; ?>public/js/ontobee.sparql.js"></script>

<script src="<?php echo SITEURL; ?>public/js/sparql/sparql_ajax.js"></script>

<script type="text/javascript">
var go_to = 0;
</script>

<script src="<?php echo SITEURL; ?>public/js/sparql/syntax/shCore.js"></script>
<script src="<?php echo SITEURL; ?>public/js/sparql/syntax/shBrushXml.js"></script>
<script src="<?php echo SITEURL; ?>public/js/sparql/syntax/shBrushJScript.js"></script>


<script type="text/javascript">
<?php
if ($go!='') {
?>
window.onload=QueryExec;
<?php
}
?>
</script>


<div class="main_col_page" id="page_query">
<h2>Ontobee SPARQL Query</h2>
<p><strong>Notes:</strong> To obtain some tutorial on how to perform Ontobee SPARQL query, please see: <a href="http://www.ontobee.org/tutorial/tutorial_sparql.php">http://www.ontobee.org/tutorial/tutorial_sparql.php</a>. This Ontobee SPARQL tutorial page also includes detailed explanation on how the examples listed below work out. To programmatically query our SPARQL endpoint, please visit URL <a href="http://sparql.hegroup.org/sparql/">http://sparql.hegroup.org/sparql/</a>. </p>
<p>&nbsp;</p>

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
<a href="javascript:eg1();">Example 1</a>, <a href="javascript:eg2();">Ex 2</a>, <a href="javascript:eg3();">Ex 3</a>, <a href="javascript:eg4();">Ex 4</a>, <a href="javascript:eg5();">Ex 5</a>, <a href="javascript:eg6();">Ex 6</a>, <a href="javascript:eg7();">Ex 7</a>
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

<?php require TEMPLATE . 'footer.default.dwt.php'; ?>