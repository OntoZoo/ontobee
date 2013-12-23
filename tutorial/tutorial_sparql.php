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
Author: Yongqun 'Oliver' He, Zuoshuang Xiang
The University Of Michigan
He Group
Date: June 2008 - December 2013
Purpose: Ontobee tutorial section sparql page.
-->

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html><!-- InstanceBegin template="/Templates/default.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<!-- InstanceBeginEditable name="doctitle" -->
<title>Ontobee</title>
<!-- InstanceEndEditable --><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="shortcut icon" href="/favicon.ico" />
<link href="../css/styleMain.css" rel="stylesheet" type="text/css">
<!-- InstanceBeginEditable name="head" -->
<style type="text/css">
<!--
.style1 {
	font-size: 14px;
	font-weight: bold;
}
-->

pre.data {
border: thin solid #88AA88;
background-color: #E8F0E8;
margin: 1em 4em 1em 0em;
}
.style3 {
	font-size: 13px;
	font-weight: bold;
	font-style: italic;
}
</style>
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
<div id="topnav"><a href="../index.php" class="topnav">Home</a><a href="../introduction.php" class="topnav">Introduction</a><a href="../ontostat/index.php" class="topnav">Statistics</a><a href="../sparql/index.php" class="topnav">SPARQL</a><a href="../ontobeep/index.php" class="topnav">Ontobeep</a><a href="index.php" class="topnav">Tutorial</a><a href="../faqs.php" class="topnav">FAQs</a><a href="../references.php" class="topnav">References</a><a href="../links.php" class="topnav">Links</a><a href="../contactus.php" class="topnav">Contact</a><a href="../acknowledge.php" class="topnav">Acknowledge</a><a href="../news.php" class="topnav">News</a></div>
<div id="mainbody">
<!-- InstanceBeginEditable name="Main" -->
<h3 class="head3_darkred">Tutorial: How to Use Ontobee SPARQL to Query RDF triple store? </h3>

<p>The Ontobee program is developed based on the SPARQL technology. In this web page, we provide an introduction on the technology, examples of how to use SPARQL to query ontology data stored in our RDF triple store, and some relevent references and web links. </p>
<p>&nbsp;</p>
<p><strong>Table of Contents </strong> </p>
<ol>
  <li><a href="#intro">Introduction of SPARQL and RDF Triple Store </a>    </li>
  <li><a href="#examples">SPARQL Examples to Query Ontobee RDF Triple Store</a>
    <ol type="i">
      <li><a href="#ex1">Find all class-containing ontology graphs </a></li>
        <li><a href="#ex2">Find subclasses of an ontology term</a></li>
        <li><a href="#ex3">Find all class (or object, annotation, or datatype property) terms of an ontology </a></li>
        <li><a href="#ex4">Retrieve definition and authors of all classes in an ontology</a></li>
      </ol>
  </li>
  <li><a href="#refs">References and Web Links</a></li>
</ol>
<br/>
<p class="style1" id="intro">1. Introduction of SPARQL and RDF Triple Store : </p>
<p>RDF is .... </p>
<p>RDF triple store is ...</p>
<p>SPARQL (pronounced &quot;sparkle&quot;) is a recursive acronym. It stands for SPARQL Protocol and RDF Query Language. Current version of SPARQL is 1.1. The early version was 1.0. </p>
<p>Ontobee uses Hegroup RDF Triple store which is generated using the  <a href="http://virtuoso.openlinksw.com/dataspace/doc/dav/wiki/Main/">Virtuoso Open-Source Edition</a> software. </p>
<p>-- <strong>Note</strong>: this section is still under construction. </p>
<p>&nbsp;</p>
<p id="examples"><span class="style1">2. SPARQL Examples to Query Ontobee RDF Triple Store: </span></p>
<p>  This section provides many examples on how to query the Ontobee RDF triple store:  </p>
<p class="style3" id="ex1">(i). Example #1: Find all class-containing ontology graphs: </p>
<p>This example is aimed to find all ontologies in our RDF triple store. Typically every single ontology includes at least one class. This SPARQL script searches those ontology graphs in the RDF triple store that contains at least one class. </p>
<p>Below is the SPARQL script: </p>
<div><pre class="data">

  PREFIX rdf: &lt;http://www.w3.org/1999/02/22-rdf-syntax-ns#&gt; 
  PREFIX owl: &lt;http://www.w3.org/2002/07/owl#&gt; 
  SELECT distinct ?graph_uri 
  WHERE 
  {
    GRAPH ?graph_uri { ?s rdf:type owl:Class } .  }
  
</pre>
</div>

<p>This script is shown up in the default screen  in the <a href="../sparql/index.php">Ontobee SPARQL query website</a>. This is also listd as the first example in the same website. </p>


<p class="style3" id="ex2">(ii). Example #2: Find subclasses of an ontology term: </p>
<p>This example is aimed to find all subclasses of an ontology term. </p>
<p>Below is the SPARQL script: </p>
<div>
<pre class="data">

  PREFIX obo-term: &lt;http://purl.obolibrary.org/obo/&gt;
  SELECT DISTINCT ?x ?label
  FROM &lt;http://purl.obolibrary.org/obo/merged/OAE&gt;
  WHERE
  {
    ?x rdfs:subClassOf obo-term:OAE_0000001.
    ?x rdfs:label  ?label. }

</pre>
</div>
<p>This script is listd as the second example provided on the <a href="../sparql/index.php">Ontobee SPARQL query website</a>.  </p>
<p class="style3" id="ex3">(iii). Example #3: Find the number of all class (or object, annotation, or datatype property) terms of an ontology: </p>
<p>This example is aimed to find the number of all class terms of an ontology. </p>
<p>Below is the SPARQL script: </p>
<div><pre class="data">

  SELECT count(?s) as ?VO_class_count
  FROM &lt;http://purl.obolibrary.org/obo/merged/VO&gt;
  WHERE
  {
    ?s a owl:Class .
    ?s rdfs:label ?label .
  
    FILTER regex( ?s, "VO_" ) }
  
</pre>
</div>
<p>This script is listd as the third example provided on the <a href="../sparql/index.php">Ontobee SPARQL query website</a>. </p>
<p>This script can be easily modified to find other types of ontology terms. Here is some instruction: </p>
<ul>
  <li>To find all object properties, use owl:ObjectProperty instead of owl:Class. </li>
  <li>To find all Datatype properties, use owl:AnnotationProperty instead of owl:Class. </li>
  <li>To find all Annotation properties, use owl:DatatypeProperty instead of owl:Class</li>
</ul>
<p class="style3" id="ex4">(iv). Example #4: Retrieve definition and authors of all classes in an ontology: </p>
<p>This example is aimed to  retrieve the definitions of all classes that have definitions in an ontology. In this script, the OBO IAO ontology annotation terms IAO_0000115 (&quot;definition&quot;) and IAO_0000117 (&quot;author&quot;) are used.</p>
<p>Below is the SPARQL script: </p>
<div><pre class="data">

  PREFIX obo-term: &lt;http://purl.obolibrary.org/obo/&gt;
  SELECT ?s ?label ?definition ?author
  FROM &lt;http://purl.obolibrary.org/obo/merged/VO&gt;
  {
    ?s a owl:Class .
    ?s rdfs:label ?label .
    ?s obo-term:IAO_0000115 ?definition .
    ?s obo-term:IAO_0000117 ?author . }
  
</pre>
</div>
<p>This script is listd as the fourth example provided on the <a href="../sparql/index.php">Ontobee SPARQL query website</a>. </p>
<p>&nbsp;</p>
<p class="style1" id="refs">3. References and Weblinks: </p>
<ul>
  <li> SPARQL Query Language for RDF, W3C Recommendation 15 January 2008: <a href="http://www.w3.org/TR/rdf-sparql-query/">http://www.w3.org/TR/rdf-sparql-query/</a></li>
  <li>W3C RDF: <a href="http://www.w3.org/RDF/">http://www.w3.org/RDF/</a></li>
  <li>Virtuoso Open-Source Edition: <a href="http://virtuoso.openlinksw.com/dataspace/doc/dav/wiki/Main/">http://virtuoso.openlinksw.com/dataspace/doc/dav/wiki/Main/</a> </li>
  <li>NCBO SPARQL BioProtal: <a href="http://www.bioontology.org/wiki/index.php/SPARQL_BioPortal">http://www.bioontology.org/wiki/index.php/SPARQL_BioPortal</a> </li>
</ul>
<p>&nbsp;</p>
<p>-- Prepared by Bin Zhao and Oliver He, 8/21/2013. </p>
<p>&nbsp;</p>
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
