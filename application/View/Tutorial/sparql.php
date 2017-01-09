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
 * @author Yongqun Oliver He
 * @author Zuoshuang Allen Xiang
 * @since Sep 8, 2015
 * @comment 
 */
 
if ( !$this ) {
	exit( header( 'HTTP/1.0 403 Forbidden' ) );
}

?>

<?php require TEMPLATE . 'header.default.dwt.php'; ?>

<style type="text/css">
<!--
.style1 {
	font-size: 14px;
	font-weight: bold;
}
.style2 {
	font-size: 13px;
	font-weight: bold;
}
.style3 {
	font-size: 13px;
	font-style: italic;
}
-->
</style>

<h3 class="head3_darkred">Tutorial: How to Use Ontobee SPARQL to Query RDF triple store? </h3>

<p>The Ontobee program is developed based on the SPARQL technology. In this web page, we provide an introduction on the technology, examples of how to use SPARQL to query ontology data stored in our RDF triple store, and some relevent references and web links. </p>
<p>&nbsp;</p>
<p><strong>Table of Contents </strong> </p>
<ol>
  <li><a href="<?php echo SITEURL; ?>tutorial/sparql/#intro">Introduction of SPARQL and RDF Triple Store </a>    </li>
  <li><a href="<?php echo SITEURL; ?>tutorial/sparql/#examples">SPARQL Examples to Query Ontobee RDF Triple Store</a>
    <ol type="i">
      <li><a href="<?php echo SITEURL; ?>tutorial/sparql/#ex1">Find all class-containing ontology graphs </a></li>
        <li><a href="<?php echo SITEURL; ?>tutorial/sparql/#ex2">Find subclasses of an ontology term</a></li>
        <li><a href="<?php echo SITEURL; ?>tutorial/sparql/#ex3">Find all class (or object, annotation, or datatype property) terms of an ontology </a></li>
        <li><a href="<?php echo SITEURL; ?>tutorial/sparql/#ex4">Retrieve definition and authors of all classes in an ontology</a></li>
		<li><a href="<?php echo SITEURL; ?>tutorial/sparql/#ex5">Retrieve general annotations of an ontology</a></li>
		<li><a href="<?php echo SITEURL; ?>tutorial/sparql/#ex6">Query the number of human tRNA genes</a></li>
		<li><a href="<?php echo SITEURL; ?>tutorial/sparql/#ex7">Find the number of mouse genes associated with  mitochondrial DNA repair</a></li>		
      </ol>
  </li>
  <li><a href="<?php echo SITEURL; ?>tutorial/sparql/#refs">References and Web Links</a></li>
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

<p>This script is shown up in the default screen  in the <a href="<?php echo SITEURL; ?>sparql">Ontobee SPARQL query website</a>. This is also listd as the first example in the same website. </p>


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
<p>This script is listed as the second example provided on the <a href="<?php echo SITEURL; ?>sparql">Ontobee SPARQL query website</a>. Note: to make recursive search, we can add the string &quot;option (transitive)&quot; beyond  &quot;<span class="data">?x rdfs:subClassOf obo-term:OAE_0000001</span>&quot;. This addition will allow the search of the whole branch (not only the direct children) of the term <span class="data">OAE_0000001</span>. To count how many subclasses there are, please refer several of the follow examples. </p>
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
<p>This script is listd as the third example provided on the <a href="<?php echo SITEURL; ?>sparql">Ontobee SPARQL query website</a>. </p>
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
  WHERE
  {
    ?s a owl:Class .
    ?s rdfs:label ?label .
    ?s obo-term:IAO_0000115 ?definition .
    ?s obo-term:IAO_0000117 ?author . }
  
</pre>
</div>
<p>This script is listd as the fourth example provided on the <a href="<?php echo SITEURL; ?>sparql">Ontobee SPARQL query website</a>. </p>
<p>&nbsp;</p>

<p class="style3" id="ex5">(v). Example #5: Retrieve general annotations of an ontology: </p>
<p>This example is aimed to retrieve general annotation descriptions of the ontology. The result will return different types of annotations such as "creator", "description"s, etc.</p>
<p>Below is the SPARQL script: </p>
<div><pre class="data">

  PREFIX owl: &lt;http://www.w3.org/2002/07/owl#&gt;
  SELECT DISTINCT ?p, ?o
  FROM &lt;http://purl.obolibrary.org/obo/merged/OAE&gt;
  WHERE{
    ?s a owl:Ontology .
	?s ?p ?o .}
  
</pre>
</div>
<p>This script is listd as the fifth example provided on the <a href="<?php echo SITEURL; ?>sparql">Ontobee SPARQL query website</a>. </p>
<p>&nbsp;</p>

<p class="style3" id="ex6">(vi). Example #6: Query the number of human tRNA genes (OGG_2010009606): </p>
<p>This example is aimed to find the number of all class terms under human tRNA gene type (OGG_2010009606) in OGG ontology.</p>
<p>Below is the SPARQL script: </p>
<div><pre class="data">

  PREFIX obo-term: &lt;http://purl.obolibrary.org/obo/&gt;
  SELECT count(DISTINCT ?x) as ?count
  FROM &lt;http://purl.obolibrary.org/obo/merged/OGG&gt;
  WHERE {
    ?x rdfs:subClassOf obo-term:OGG_2010009606 .
	?x a owl:Class .}
  
</pre>
</div>
<p>This script is listd as the sixth example provided on the <a href="<?php echo SITEURL; ?>sparql">Ontobee SPARQL query website</a>. </p>
<p>&nbsp;</p>

<p class="style3" id="ex7">(vii). Example #7: Count the number of mouse genes associated with mitochondrial DNA repair: </p>
<p>This example is aimed to find the number of mouse genes associated with GO 'mitochondrial DNA repair' (GO_0043504). The query fetches the text of the "has GO association" (OGG_0000000029) annotation for each gene from OGG, and then retrieves those genes that have the GO ID "GO_0043504" in their annotation.</p>
<p>Below is the SPARQL script: </p>
<div><pre class="data">

  PREFIX obo-term: &lt;http://purl.obolibrary.org/obo/&gt;
  SELECT count(DISTINCT ?s)
  FROM &lt;http://purl.obolibrary.org/obo/merged/OGG-Mm&gt;
  FROM &lt;http://purl.obolibrary.org/obo/merged/GO&gt;
  WHERE {
     #Note: Get OGG-Mm genes associated with GO_0043504
      ?s a owl:Class .
      ?s rdfs:label ?labelogg .
      ?s obo-term:OGG_0000000029 ?annotation .
      FILTER regex(?annotation, "GO_0043504") .}
  
</pre>
</div>
<p>This script is listd as the seventh example provided on the <a href="<?php echo SITEURL; ?>sparql">Ontobee SPARQL query website</a>. </p>
<p>The answer of the query is 3. To know exactly what the three genes are, you can change the above code "SELECT count(DISTINCT ?s)" to: "SELECT DISTINCT ?s ?labelogg". </p>
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
<p>-- Updated with new example #5 by Bin, 3/31/2013. </p>
<p>&nbsp;</p>


<?php require TEMPLATE . 'footer.default.dwt.php'; ?>