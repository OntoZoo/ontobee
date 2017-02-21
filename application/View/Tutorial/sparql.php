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
  <li><a href="<?php echo SITEURL; ?>tutorial/sparql/#intro">Introduction of SPARQL and RDF triple store </a>    </li>
  <li><a href="<?php echo SITEURL; ?>tutorial/sparql/#basic">Basic SPARQL query programming skills</a>
      <ol type="i">
      <li><a href="<?php echo SITEURL; ?>tutorial/sparql/#structure">Query structure</a></li>  
      <li><a href="<?php echo SITEURL; ?>tutorial/sparql/#prefixes">Common prefixes</a></li>  
      <li><a href="<?php echo SITEURL; ?>tutorial/sparql/#select">How to <em>select</em>?</a></li> 
      <li><a href="<?php echo SITEURL; ?>tutorial/sparql/#from">How to define <em>from <...></em>?</a></li> 
      <li><a href="<?php echo SITEURL; ?>tutorial/sparql/#where">How to program inside <em>where</em>?</a></li>   
      <li><a href="<?php echo SITEURL; ?>tutorial/sparql/#modifiers">How to use modifers</a>?</li>    
      <li><a href="<?php echo SITEURL; ?>tutorial/sparql/#learningresources">Web resources for learning SPARQL programming</a></li>    
    </ol>
  </li>
  
  <li><a href="<?php echo SITEURL; ?>tutorial/sparql/#examples">SPARQL Examples to Query Ontobee RDF Triple Store</a>
    <ol type="i">
      <li><a href="<?php echo SITEURL; ?>tutorial/sparql/#ex1">Find all class-containing ontology graphs </a></li>
        <li><a href="<?php echo SITEURL; ?>tutorial/sparql/#ex2">Find subclasses of an ontology term</a></li>
        <li><a href="<?php echo SITEURL; ?>tutorial/sparql/#ex3">Find all class (or object, annotation, or datatype property) terms of an ontology </a></li>
        <li><a href="<?php echo SITEURL; ?>tutorial/sparql/#ex4">Retrieve definition and authors of all classes in an ontology</a></li>
		<li><a href="<?php echo SITEURL; ?>tutorial/sparql/#ex5">Retrieve general annotations of an ontology</a></li>
		<li><a href="<?php echo SITEURL; ?>tutorial/sparql/#ex6">Query the number of human tRNA genes</a></li>
		<li><a href="<?php echo SITEURL; ?>tutorial/sparql/#ex7">Find the number of mouse genes associated with  mitochondrial DNA repair</a></li>		
        <li><a href="<?php echo SITEURL; ?>tutorial/sparql/#ex8">Query on axiom: Find vaccines containing egg protein allergen</a></li>	
        <li><a href="<?php echo SITEURL; ?>tutorial/sparql/#papers">Selected papers citing Ontobee SPARQL.</a></li>	
    </ol>
  </li>
  <li><a href="<?php echo SITEURL; ?>tutorial/sparql/#faqs">Frequently Asked Questions (FAQs)</a>      
  <li><a href="<?php echo SITEURL; ?>tutorial/sparql/#refs">References and Web Links</a></li>
</ol>
<br/>
<p class="style1" id="intro">1. Introduction of SPARQL and RDF triple store: </p>
<p><a href="https://en.wikipedia.org/wiki/Resource_Description_Framework">RDF</a> represents Resource Description Framework. RDF is a family of World Wide Web Consortium (W3C) specifications originally designed as a metadata data model (<a href="https://www.w3.org/RDF/">https://www.w3.org/RDF/</a>). The RDF data model makes  statements about resources (in particular web resources) expressions, known as triples. RDF triples  follow a subject–predicate–object structure. The <em>subject</em> denotes the resource, and the <em>predicate</em> denotes traits or aspects of the resource, and expresses a relationship between the subject and the <em>object</em>. </p>
<p><a href="https://en.wikipedia.org/wiki/SPARQL">SPARQL</a> (pronounced &quot;sparkle&quot;) is a recursive acronym. It stands for SPARQL Protocol and RDF Query Language. Current version of SPARQL is 1.1 (<a href="https://www.w3.org/TR/sparql11-query/">https://www.w3.org/TR/sparql11-query/</a>). The early version was 1.0 (<a href="https://www.w3.org/TR/rdf-sparql-protocol/">https://www.w3.org/TR/rdf-sparql-protocol/</a>). </p>
<p>Ontobee uses Hegroup RDF Triple store which is generated using the  <a href="http://virtuoso.openlinksw.com/dataspace/doc/dav/wiki/Main/">Virtuoso Open-Source Edition</a> software. </p>
<p>&nbsp;</p>

<p id="basic"><span class="style1">2. Basic SPARQL query programming skills: </span></p>
<p id="structure"><strong>2.1. Query structure:</strong></p>
<p>A typical query includes the following structure, some parts are optional: </p>
<ul>
  <li>Declear prefix shortcuts (optional)
    <ul style="list-style: none;">
      <li>PREFIX foo: &lt;...&gt;</li>
    </ul>
  </li>
  <li>Query result clause</li>
  <ul style="list-style: none;">
    <li>SELECT ...</li>
  </ul>
  <li>Define the dataset (optional)</li>
  <ul style="list-style: none;">
    <li>FROM &lt;...&gt;</li>
  </ul>
  <li>Query pattern</li>
  <ul style="list-style: none;">
    <li>WHERE { </li>
    <li>...</li>
    <li> }</li>
  </ul>
  <li>Query modifiers (optional): 
    <ul style="list-style: none;">
      <li>Group BY ...</li>
      <li>HAVING</li>
      <li>ORDER BY</li>
      <li>LIMIT</li>
      <li>OFFSET</li>
      <li>VALUES</li>
    </ul>
  </li>
</ul>
<p id="prefixes"><strong>2.2. Common Prefixes:</strong></p>
<ul>
  <li><strong>rdf</strong>:	http://xmlns.com/foaf/0.1/ <br />
  </li>
  <li><strong>rdfs</strong>:	http://www.w3.org/2000/01/rdf-schema# <br />
  </li>
  <li><strong>owl</strong>:	http://www.w3.org/2002/07/owl# <br />
  </li>
  <li><strong>xsd</strong>:	http://www.w3.org/2001/XMLSchema# <br />
  </li>
  <li><strong>dc</strong>:	http://purl.org/dc/elements/1.1/<br />
    </li>
  <li><strong>foaf</strong>:	http://xmlns.com/foaf/0.1/ </li>
  <li><span class="data"><strong>obo</strong>: &lt;http://purl.obolibrary.org/obo/&gt; </span></li>
</ul>
    
<p id="select"><strong>2.3. How to <em>select</em>?</strong></p>
<p>Similar to its use in SQL, SELECT in SPARQL allows you to define which variables you want values returned and output. Like SQL you can list these individually or use an asterisk (*) to specify  values for each variable. E.g.</p>
<ul>
  <li>SELECT ?a ?text</li>
  <li>SELECT *</li>
</ul>
<p> If you don't want duplicates you can append <strong>DISTINCT</strong> after SELECT. e.g., </p>
<ul>
  <li>  SELECT DISTINCT ?country</li>
</ul>
<p> According to SPARQL 1.1, it is possible to apply mathematical functions to selected variables. The most straightforward of these is <strong>COUNT</strong>.</p>
<ul>
  <li>SELECT (COUNT(?vaccine) AS ?no_vaccines)</li>
</ul>
<p>Other mathematical functions include SUM, AVG, MAX, MIN. </p>
<p>Note that tThese mathematical functions in the SELECT clause are quite basic and return just a single row of results. The GROUP BY function in the modifier section allows aggregation on a particular subject. </p>
<p><strong>References</strong>: </p>
<ul>
  <li><a href="http://rdf.myexperiment.org/howtosparql?page=SELECT">http://rdf.myexperiment.org/howtosparql?page=SELECT</a> </li>
  <li><a href="https://www.w3.org/TR/sparql11-query/#select">https://www.w3.org/TR/sparql11-query/#select</a></li>
</ul>
<p id="from"><strong>2.4. How to define<em> from &lt;...&gt;</em>?</strong></p>
<p>For Ontobee query, the question is: h<em>ow to find the ontology graph URI in Ontobee RDF triple store (i.e., Hegroup triple store)? </em><br/>
  <br/>
The general naming pattern for the graph URI is to transform a PURL http://purl.obolibrary.org/obo/$foo.owl (note foo must be all lowercase by OBO conventions) to http://purl.obolibrary.org/obo/merged/uppercase($foo). However, this may not be consistent. Before a formal rule is set up and if the default naming pattern does not work, it would be good to run the Example 1 in <a href="http://www.ontobee.org/sparql">http://www.ontobee.org/sparql</a> and then find it out. See more in the <a href="https://groups.google.com/forum/#!profile/ontobee-discuss/APn2wQcmnFIj-vS3zy7XbQYoyJ8pA8w7lxkjLPuR79jZCvZ8s7iqsjFfePfGLakB-bZXs3Hzk_3k/ontobee-discuss/ucd3kWssyOQ/BAU9HtoTAQAJ">related Ontobee-discuss item</a>. </p>

<p id="where"><strong>2.5. How to program inside <em>where</em>?</strong></p>
<p>The <em>WHERE</em> clause defines where you want to find values for the variables  defined in the SELECT clause. </p>
<p><strong><em>2.5.1. Triple representations.</em></strong></p>
<p>The basic unit is a triple, which is made up of three components, a subject, a predicate and an object. Each of the three components can take one of two forms:</p>
<ul>
  <li> put  a ? prior to the variable name, e.g., ?a.  </li>
  <li> a Universal Resource Identifier (URI).</li>
  <li>Note: A prefix for the namespace of the URI can be utilized. </li>
</ul>
<p>  These triples can then be concatenated together using a<strong> full-stop (.)</strong>. Eventually, an interconnected graph of nodes joined by relationships is built up. </p>
<p>A  <strong>semi-colon (;) </strong>can be used after each triple to replace the subject for the next triple if it is the same. In this way, you  only need to define the predicate and object. e.g., </p>
<blockquote>
  <p>?x rdf:type mebase:User ;<br />
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;foaf:homepage ?homepage ;<br />
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;foaf:mbox ?mbox</p>
</blockquote>
<p>When you  have the same predicate. you can use a <strong>comma (,)</strong> to separate each object, e.g.,</p>
<blockquote>
  <p>. ore:aggregates &lt;workflow181&gt;, &lt;workflow246&gt;</p>
</blockquote>
<p>You can use 'a' rather than rdf:type to specify the type of an entity.</p>
<p><strong><em>2.5.2. Clauses </em></strong></p>
<p>The <em><strong>UNION</strong></em> clause:  return results to match at least one of multiple patterns. Note that the OGG paper (<a href="http://ceur-ws.org/Vol-1327/icbo2014_paper_23.pdf">http://ceur-ws.org/Vol-1327/icbo2014_paper_23.pdf</a>) provides a good example (Fig. 7) of using UNION. </p>
<p>The <strong>OPTIONAL</strong> clause is also often used.</p>
<p>The <strong>FILTER</strong> clause  filters the results based on certain conditions. </p>
<ul>
  <li>Filter on text: 
    <ul>
      <li>E.g., <strong>FILTER regex(?title,'^sometitle','i')</strong></li>
      <li>The <em><strong>regex</strong></em> operand  compares two text strings. In the example,  this compares the value of ?title with the text string 'sometitle'. The caret (^) sign  indicates that the string for ?title must start with 'sometitle'. The 'i' as the third parameter means that the regular expression is case insentive. The default is case sensitive (only the first two parameters required).</li>
      <li>Another example: <strong>FILTER regex(str(?aURI),'GO','i')</strong></li>
      <li>In this example, the <em><strong>str</strong></em> operand to convert the URI into a string. </li>
    </ul>
  </li>
  <li>Filter on numbers
    <ul>
      <li>allows inequalities (and equalities)</li>
      <li>e.g., FILTER (?value &gt;= 4)</li>
      <li>e.g., FILTER (?score &gt;= 4 &amp;&amp; regex(?title,'^sometitle 123'))</li>
    </ul>
  </li>
  <li>Filter on dates
    <ul>
      <li>e.g., FILTER ( ?added &gt;= xsd:dateTime('2009-09-01T00:00:00Z') )</li>
    </ul>
  </li>
</ul>
<p><strong>Reference:</strong></p>
<ul>
  <li><a href="http://rdf.myexperiment.org/howtosparql?page=WHERE">http://rdf.myexperiment.org/howtosparql?page=WHERE</a></li>
</ul>

<p id="modifiers"><strong>2.6. How to use modifers? </strong></p>
<p>A solution sequence modifier is one of:</p>
<ul>
  <li><a href="https://www.w3.org/TR/sparql11-query/#modOrderBy">Order</a> modifier: put the solutions in order</li>
  <li><a href="https://www.w3.org/TR/sparql11-query/#modProjection">Projection</a> modifier: choose certain variables</li>
  <li><a href="https://www.w3.org/TR/sparql11-query/#modDistinct">Distinct</a> modifier: ensure solutions in the sequence are unique</li>
  <li><a href="https://www.w3.org/TR/sparql11-query/#modReduced">Reduced</a> modifier: permit elimination of some non-distinct solutions</li>
  <li><a href="https://www.w3.org/TR/sparql11-query/#modOffset">Offset</a> modifier: control where the solutions start from in the overall sequence of solutions</li>
  <li><a href="https://www.w3.org/TR/sparql11-query/#modResultLimit">Limit</a> modifier: restrict the number of solutions</li>
</ul>
<p><strong>GROUP BY</strong>: allow aggregation over one or more properties.</p>
<p><strong>ORDER BY</strong>: e.g., ORDER BY DESC(?added), ORDER BY DESC(xsd:nonNegativeInteger(?downloaded))</p>
<p><strong>References:</strong></p>
<ul>
  <li><a href="https://www.w3.org/TR/sparql11-query/#solutionModifiers">https://www.w3.org/TR/sparql11-query/#solutionModifiers</a><a href="https://www.w3.org/TR/sparql11-query/#solutionModifiers"></a></li>
  <li><a href="http://rdf.myexperiment.org/howtosparql?page=GROUP+BY#">http://rdf.myexperiment.org/howtosparql?page=GROUP+BY#</a></li>
  <li><a href="http://rdf.myexperiment.org/howtosparql?page=ORDER+BY#">http://rdf.myexperiment.org/howtosparql?page=ORDER+BY# </a></li>
  <li><a href="http://rdf.myexperiment.org/howtosparql?page=LIMIT#">http://rdf.myexperiment.org/howtosparql?page=LIMIT#</a></li>
</ul>


<p id="learningresources"><strong>2.7. Web resources for learning SPARQL programming: </strong></p>
<p><strong><em>The W3C SPARQL recommendations</em></strong><em>: </em></p>
<ul>
  <li>SPARQL 1.1 Query Language, W3C Recommendation 21 March 2013: <a href="https://www.w3.org/TR/sparql11-query/">https://www.w3.org/TR/sparql11-query/</a></li>
  <li>SPARQL Query Language for RDF, W3C Recommendation 15 January 2008: <a href="http://www.w3.org/TR/rdf-sparql-query/">http://www.w3.org/TR/rdf-sparql-query/</a></li>
</ul>

<p><em><strong>More resources on how to learn SPARQL programming:</strong></em> </p>
<ul>
  <li>HowToSparql: <a href="http://rdf.myexperiment.org/howtosparql?">http://rdf.myexperiment.org/howtosparql?</a></li>
  <li><a href="http://www.slideshare.net/LeeFeigenbaum/sparql-cheat-sheet">http://www.slideshare.net/LeeFeigenbaum/sparql-cheat-sheet</a></li>
</ul>
<p>&nbsp;</p>
<p><span class="style1">3. SPARQL Examples to Query Ontobee RDF Triple Store: </span></p>
<p>  This section provides many examples on how to query the Ontobee RDF triple store:  </p>
<p class="style3" id="ex1">(i). Example #1: Find all class-containing ontology graphs: </p>
<p>This example is aimed to find all ontologies in our RDF triple store. Typically every single ontology includes at least one class. This SPARQL script searches those ontology graphs in the RDF triple store that contains at least one class. </p>
<p>Below is the SPARQL script: </p>
<div>
  <pre class="data">

  PREFIX rdf: &lt;http://www.w3.org/1999/02/22-rdf-syntax-ns#&gt; 
  PREFIX owl: &lt;http://www.w3.org/2002/07/owl#&gt; 
  SELECT distinct ?graph_uri 
  WHERE 
  {
    GRAPH ?graph_uri { ?s rdf:type owl:Class } .  }
  </pre>
</div>

<p>This script is shown up in the default screen  in the <a href="<?php echo SITEURL; ?>sparql">Ontobee SPARQL query website</a>. This is also listd as the first example in the same website. Note that often the rdf and owl prefix definitions can be ignored since they are used as the default by the system. </p>


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
<div>
  <pre class="data">

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
<div>
  <pre class="data">

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
<div>
  <pre class="data">

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
<div>
  <pre class="data">

  PREFIX obo-term: &lt;http://purl.obolibrary.org/obo/&gt;
  SELECT count(DISTINCT ?x) as ?count
  FROM &lt;http://purl.obolibrary.org/obo/merged/OGG&gt;
  WHERE {
    ?x rdfs:subClassOf obo-term:OGG_2010009606 .
	?x a owl:Class .}
  </pre>
</div>
<p>This script is listd as the sixth example provided on the <a href="<?php echo SITEURL; ?>sparql">Ontobee SPARQL query website</a>. Note that this example came from the OGG paper: <a href="http://ceur-ws.org/Vol-1327/icbo2014_paper_23.pdf">http://ceur-ws.org/Vol-1327/icbo2014_paper_23.pdf</a>.</p>
<p>&nbsp;</p>

<p class="style3" id="ex7">(vii). Example #7: Count the number of mouse genes associated with mitochondrial DNA repair: </p>
<p>This example is aimed to find the number of mouse genes associated with GO 'mitochondrial DNA repair' (GO_0043504). The query fetches the text of the "has GO association" (OGG_0000000029) annotation for each gene from OGG, and then retrieves those genes that have the GO ID "GO_0043504" in their annotation.</p>
<p>Below is the SPARQL script: </p>
<div>
  <pre class="data">

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

<p class="style3" id="ex8">(viii). Example #8: Query on axiom: Find vaccines containing egg protein allergen: </p>
<p>The above examples are mostly based on ontology annotation properties. How to perform SPARQL queries based on logical axioms with object properties (or relations)? Here we provide some example.</p>
<p>Below is one such SPARQL script: </p>
<div>
  <pre class="data">
  
    PREFIX has_vaccine_allergen: &lt;http://purl.obolibrary.org/obo/VO_0000531&gt;
    PREFIX chicken_egg_protein_allergen: &lt;http://purl.obolibrary.org/obo/VO_0000912&gt;   
    SELECT distinct ?vaccine_label ?vaccine 
    FROM &lt;http://purl.obolibrary.org/obo/merged/VO&gt;
    WHERE {
        ?vaccine rdfs:label ?vaccine_label .
        ?vaccine rdfs:subClassOf ?vaccine_restriction .
        ?vaccine_restriction owl:onProperty has_vaccine_allergen:; owl:someValuesFrom chicken_egg_protein_allergen: .
	}      </pre>
</div>
<p>This script is listd as the eighth example provided on the <a href="<?php echo SITEURL; ?>sparql">Ontobee SPARQL query website</a>. Note that the ?vaccine_restriction is in essence an owl:Restriction. If you add a line of &quot;       ?vaccine_restriction a owl:Restriction.&quot;, the result will be the same. The answer of the query can be obtained by executing the query on the query website. This SPARQL quwery is the same as Figure 6 of the <a href="http://jbiomedsem.biomedcentral.com/articles/10.1186/s13326-016-0062-4">VICO paper </a>(PMID:<a href="https://www.ncbi.nlm.nih.gov/pubmed/27099700">27099700</a>).  </p>
<p>Note that the following code has the same effect as the top code:</p>

<div>
  <pre class="data">
          
    Prefix obo: &lt;http://purl.obolibrary.org/obo/&gt;   
    SELECT distinct ?label ?s
    From &lt;http://purl.obolibrary.org/obo/merged/VO&gt;
    Where {
     ?s rdfs:label ?label .
     ?s rdfs:subClassOf ?s1 .
     ?s1 owl:onProperty obo:VO_0000531; owl:someValuesFrom obo:VO_0000912 .
     }   </pre>
</div>
<p>The difference between the above two sets of SPARQL codes is that the first one looks easier to read. </p>
<p>How this works? It might be good to see the original <a href="https://github.com/VICO-ontology/VICO/blob/master/docs/developer/SPARQL query scripts.txt">VO code</a> for one answer - <a href="http://purl.obolibrary.org/obo/VO_0000006">VO_0000006</a> (i.e., Afluria).</p>

<div>
  <pre class="data">
   &lt;!-- http://purl.obolibrary.org/obo/VO_0000006 --&gt; 
    &lt;owl:Class rdf:about="&obo;VO_0000006"&gt; 
		... ...
        &lt;rdfs:subClassOf&gt; 
            &lt;owl:Restriction&gt;
                &lt;owl:onProperty rdf:resource="&obo;VO_0000531"/&gt; 
                &lt;owl:someValuesFrom rdf:resource="&obo;VO_0000912"/&gt; 
            &lt;/owl:Restriction>
        &lt;/rdfs:subClassOf&gt; 
       ... ... 
    &lt;/owl:Class>     </pre>
</div>
<p>Clearly, both  SPARQL queries references the VO OWL code. It is also noted that the above queries do not use the &lt;owl:Restriction&gt; directly. To use it, we can update the code to the following with the same outcome:</p>
<div>
  <pre class="data">
          
    Prefix obo: &lt;http://purl.obolibrary.org/obo/&gt;   
    SELECT distinct ?label ?s
    From &lt;http://purl.obolibrary.org/obo/merged/VO&gt;
    Where {
	  ?s rdfs:label ?label .    
 	  ?s rdfs:subClassOf ?restriction .
  	  ?restriction a owl:Restriction .
  	  ?restriction owl:onProperty obo:VO_0000531 . 
  	  ?restriction owl:someValuesFrom obo:VO_0000912 .   
     } </pre>
</div>
<p>Note that the VICO GitHub file: <a href="https://github.com/VICO-ontology/VICO/blob/master/docs/developer/SPARQL query scripts.txt">https://github.com/VICO-ontology/VICO/blob/master/docs/developer/SPARQL%20query%20scripts.txt </a>contains more examples.</p>
<p>&nbsp;</p>
<p id="papers"><em><strong>(XI) Selected papers citing Ontobee SPARQL</strong></em></p>
<p>The following is a list of peer-reviewed articles that reports the usage of Ontobee SPARQL. Each paper usually has  a figure of Ontobee SPARQL usage screenshot. These provide various ways of using Ontobee SPARQL for different applications. </p>
<ul>
  <li>Lin Y, Zheng J, He Y. <a href="http://www.jbiomedsem.com/content/7/1/20">VICO: Ontology-based representation and integrative analysis of vaccination informed consent forms</a>. <em>J Biomed Semantics</em>. 2016 Apr 19;7:20. doi: 10.1186/s13326-016-0062-4. PMID: <a href="http://www.ncbi.nlm.nih.gov/pubmed/27099700">27099700</a>. PMCID: <a href="http://www.ncbi.nlm.nih.gov/pmc/articles/PMC4837519/">PMC4837519</a>.<br/>
  </li>
  <li>Zheng J, Harris MR, Masci AM, Lin Y, Hero A, Smith B, He Y.<a href="http://jbiomedsem.biomedcentral.com/articles/10.1186/s13326-016-0100-2"> The Ontology of Biological and Clinical Statistics (OBCS) for Standardized and Reproducible Statistical Analysis</a>. <em>J Biomed Semantics</em>. 2016. Sep 14;7(1):53. PMID: <a href="https://www.ncbi.nlm.nih.gov/pubmed/27627881" ref="aid_type=pmid">27627881</a>. PMCID: <a href="https://www.ncbi.nlm.nih.gov/pmc/articles/PMC5024438/" ref="aid_type=pmcid">PMC5024438</a>.  </li>
  <li>Guo A*, Racz R*, Hur J, Lin Y, Xiang Z, Zhao L, Rinder J, Jiang G, Zhu Q, He Y. <a href="http://jbiomedsem.biomedcentral.com/articles/10.1186/s13326-016-0069-x">Ontology-based collection, representation and analysis of drug-associated neuropathy adverse events</a>. <em>Journal of Biomedical Semantics</em>. 2016. 7:29. PMID: <a href="https://www.ncbi.nlm.nih.gov/pubmed/27213033">27213033</a>. PMCID: <a href="http://www.ncbi.nlm.nih.gov/pmc/articles/PMC4875649/">PMC4875649</a>.</li>
  <li>Lin Y, Zheng J, He Y. <a href="http://www.jbiomedsem.com/content/7/1/20">VICO: Ontology-based representation and integrative analysis of vaccination informed consent forms</a>. <em>J Biomed Semantics</em>. 2016 Apr 19;7:20. doi: 10.1186/s13326-016-0062-4. PMID: <a href="http://www.ncbi.nlm.nih.gov/pubmed/27099700">27099700</a>. PMCID: <a href="http://www.ncbi.nlm.nih.gov/pmc/articles/PMC4837519/">PMC4837519</a>.</li>
  <li>Lin Y, Xiang Z, and He Y. <a href="http://www.jbiomedsem.com/content/6/1/37">Ontology-based representation and analysis of host-<em>Brucella</em> interactions</a>.<em> J Biomed Semantics</em>. 2015, 6:37. DOI: 10.1186/s13326-015-0036-y.	PMID: <a href="http://www.ncbi.nlm.nih.gov/pubmed/26445639">26445639</a>. PMCID: <a href="http://www.ncbi.nlm.nih.gov/pmc/articles/PMC4594885/">PMC4594885</a>.</li>
  <li>He Y, Liu Y, Zhao B. <a href="https://ogg.googlecode.com/svn/trunk/docs/papers/ogg-icbo2014.pdf">OGG: a biological ontology for representing genes and genomes in specific organisms</a>. <em>Proceedings of the 5th International Conference on Biomedical Ontologies (ICBO)</em>, Houston, Texas, USA. October 8-9, 2014. Pages 13-20. [http://ceur-ws.org/Vol-1327/icbo2014_paper_23.pdf, or: <a href="http://www.hegroup.org/docs/OGG-ICBO2014.pdf">http://www.hegroup.org/docs/OGG-ICBO2014.pdf</a>, whcih has correctly formatted Figure 3]</li>
  <li>Marcos E , Zhao B, He Y.<a href="http://www.jbiomedsem.com/content/4/1/40"> The ontology of vaccine adverse events (OVAE) and its usage in representing and analyzing adverse events associated with US-licensed human vaccines</a>. <em>Journal of Biomedical Semantics.</em> 2013 Nov 26;4(1):40. PMID: <a href="http://www.ncbi.nlm.nih.gov/pubmed/24279920">24279920</a>.<br/>
  </li>
</ul>
<p>&nbsp;</p>
<p class="style1" id="faqs">4. Frequently Asked Questions (FAQs): </p>
<ul>
  <li><strong>Q:</strong> <em>What is different between</em> <a href="http://www.ontobee.org/sparql"><em>http://www.ontobee.org/sparq</em></a><em> and</em> <em><a href="http://sparql.hegroup.org/sparql/">http://sparql.hegroup.org/sparql/</a></em>?<br/><br/>
  <strong>A: </strong>The website <a href="http://www.ontobee.org/tutorial/sparql">http://www.ontobee.org/tutorial/sparql</a> is the Ontobee SPARQL website, which outputs up to 200 results. If you want to obtain more results, you can use <a href="http://sparql.hegroup.org/sparql/">http://sparql.hegroup.org/sparql/</a>, whcih is our Ontobee (or called Hegroup) SPARQL endpoint.Both sides use the same ontology triple store. <br/><br/></li>
    <li><strong>Q:</strong> <em>Can we perform  time-consuming queries using your SPARQL query resource? </em><br/>
      <br/>
  <strong>A: </strong>It depends on the size. We encourage and recommend the usage of the Ontobee SPARQL. However, if a query is too time-consuming, it may crash the server, which is not recommended. We will later develop a locally installable version of the system so you can take usege of your local computational capability. </li>
</ul>
<p>&nbsp;</p>


<p class="style1" id="refs">5. References and Weblinks: </p>
<ul>
  <li><em><strong>Learn SPARQL programming:</strong></em>
    <ul>
      <li>SPARQL 1.1 Query Language, W3C Recommendation 21 March 2013: <a href="https://www.w3.org/TR/sparql11-query/">https://www.w3.org/TR/sparql11-query/</a></li>
      <li>SPARQL Query Language for RDF, W3C Recommendation 15 January 2008: <a href="http://www.w3.org/TR/rdf-sparql-query/">http://www.w3.org/TR/rdf-sparql-query/</a></li>
      <li>HowToSparql: <a href="http://rdf.myexperiment.org/howtosparql?">http://rdf.myexperiment.org/howtosparql?</a></li>
      <li><a href="http://www.slideshare.net/LeeFeigenbaum/sparql-cheat-sheet">http://www.slideshare.net/LeeFeigenbaum/sparql-cheat-sheet</a></li>
      <li></li>
    </ul>
  </li>
  <li><em><strong>Run SPARQL queries:</strong></em>
    <ul>
      <li><em>Biological and Biomedical:</em></li>
      <li>Ontobee SPARQL: <a href="http://www.ontobee.org/sparql">http://www.ontobee.org/sparql</a></li>
      <li>NCBO SPARQL BioProtal: <a href="http://www.bioontology.org/wiki/index.php/SPARQL_BioPortal">http://www.bioontology.org/wiki/index.php/SPARQL_BioPortal</a>
        <ul>
          <li>BioPortal SPARQL examples: <a href="http://sparql.bioontology.org/examples">http://sparql.bioontology.org/examples</a></li>
        </ul>
      </li>
      <li>bio2rdf: <a href="http://bio2rdf.org/sparql">http://bio2rdf.org/sparql </a></li>
      <li></li>
      <li><em>General: </em></li>
      <li>DBPedia: <a href="http://dbpedia.org/sparql">http://dbpedia.org/sparql </a>(RDF  data from Wikipedia)</li>
      <li>SPARQLer: <a href="http://sparql.org/sparql.html">http://sparql.org/sparql.html </a>(General-purpose SPARQL query endpoint
        )  </li>
      <li></li>
    </ul>
  </li>
  <li><em><strong>Other related web links: </strong></em>
    <ul>
      <li>W3C RDF: <a href="http://www.w3.org/RDF/">http://www.w3.org/RDF/</a></li>
      <li><a href="https://www.w3.org/wiki/SPARQL/Extensions">https://www.w3.org/wiki/SPARQL/Extensions </a></li>
      <li>Virtuoso Open-Source Edition: <a href="http://virtuoso.openlinksw.com/dataspace/doc/dav/wiki/Main/">http://virtuoso.openlinksw.com/dataspace/doc/dav/wiki/Main/</a></li></ul></li>
</ul>
<p>&nbsp;</p>
<p><strong>History of Document Preparation: </strong></p>
<ul>
  <li>1/10-11/2017: Oliver added the basic programming section, and added more examples. </li>
  <li>1/9/2017: Oliver updated Introduction section, and added FAQs section.</li>
  <li>Nov 2015: Edison updated this document using new Ontobee programming style.</li>
  <li>Updated with new example #5 by Bin, 3/31/2013.</li>
  <li>Initial version prepared by Bin Zhao and Oliver He, 8/21/2013. </li>
</ul>
<p>&nbsp;</p>


<?php require TEMPLATE . 'footer.default.dwt.php'; ?>