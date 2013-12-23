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
Purpose: Ontobee tutorial section index page.
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
<h3 class="head3_darkred">Tutorial</h3>

<p>In general, the Ontobee HTML website is  easy to use and understand. You can usually search and navagate the linked results following your intuition. The source RDF or OWL output may need some explanation. </p>
<p>Below is a tutorial that introduces some key features of the Ontobee system. </p>
<p><strong>Table of Contents</strong></p>
<ol>
  <li><a href="#query">Ontobee web data query engine</a></li>
  <li><a href="#html_deref">HTML web interface derefencing ontology term</a></li>
  <li><a href="#rdf_deref">RDF output deferencing ontology term</a></li>
  <li><a href="#sparql">Ontobee SPARQL queries</a> (directly link to the<a href="tutorial_sparql.php"> SPARQL tutorial</a> page) </li>
  <li><a href="#ontobeep">Ontobeep ontology alignment and comparison</a></li>
</ol>
<br/>
<p class="style1" id="query">1. Ontobee Web Data Query Engine: </p>
<p>Below is an introduction of the basic features on the Ontobee web form-based query engine: </p>
<p class="style2">(1) Ontobee web query interfaces:  </p>
<p>The Ontobee data query engine is composed of two types: one is the query program located in the Ontobee cover page, the other is the query program in the cover page of each individual ontology. The basic functions of these two types of queries  are similar. One main difference is that the latter is only dedicated for the searching of a specific ontology, and the former allows querying one or all ontologies listed in Ontobee. The other difference is that the Ontobee query engine in the Ontobee cover page provides a &quot;<em>Jump to http://purl.obolibrary.org/obo/</em>&quot; function. </p>
<p style="text-align:center"><img src="queryinterface.jpg" alt="Web query interfaces" width="493" height="203"></p>
<p>The Ontobee query engine in the Ontobee cover page allows a user to search all ontologies listed in Ontobee together or select a specific ontology for querying ontology terms in the single ontology.  </p>
<p class="style2">(2) Auto-completion search:  </p>
<p class="style3">Introduction of the auto-completion searching function: </p>
<p>The jQuery JavaScript Library  is used for the development of Ontobee ontology term searching program. The auto-completion feature of the searching function was built based on the jQuery library. When a  user types three or more letters, the Ontobee search program will list all hit terms in  an alphabetic order in a drop-down menu. This function is available for the general query program and the ontology-specific query program: </p>
<p style="text-align:center"><img src="autocomp1.jpg" alt="auto completion 1" width="574" height="299"></p>
<p class="style3"> Selection of auto-complete query result: </p>
<p>Aftdr the list of hits is displayed, one can be selected, and then the web site of the ontology term will be accessed. </p>
<p  style="text-align:center"><img src="autocomp2.jpg" width="836" height="517"></p>
<p>&nbsp;</p>
<p class="style2">(3) Classical Search (by clicking &quot;Search terms&quot;): </p>
<p>Alternatively, a user can type searching characters, and then click the &quot;Search terms&quot; button. A list of matched terms will then be displayed in a list. The list of hits is sorted in an order to first show terms that start  with the search string, from the shortest to the longest, then terms that include the  typed string, from the shortest to the longest. Then a user can select one URI to link to the ontology term page. </p>
<p style="text-align:center"><img src="query_search.jpg" width="404" height="329"></p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p class="style1" id="html_deref">2. HTML web Interface Dereferencing Ontology Term: </p>
<p>The following screenshot shows the elements of an HTML web page dedicated for a specific ontology term, for example, VO term 'vaccine' (VO_0000002) in this case: </p>
<p style="text-align:center"><img src="html_deref_output.jpg" alt="Deferencing ontology term URI by HTML" width="597" height="715" ></p>
<p><em>Below is a description of these elements</em>:</p>
<ul style="list-style-type: none">
  <li>(1) The type of term, label, its definition, and the term IRI (bolded).</li>
  <li>(2) All annotations on the term, such as editor notes and synonyms, and term editor. </li>
  <li>(3) Equivalents, the strongest form of logical definition as they are both necessary and sufficient conditions. Formatted with Manchester Syntax. </li>
  <li>(4) Hierarchical context in the ontology structure. </li>
  <li>(5) Direct superclasses and class axioms. </li>
  <li>(6) Other terms in the ontology whose axioms make  reference to this page&rsquo;s term. </li>
  <li>(7) Other ontologies within Ontobee that use the ontology term.</li>
  <li>(8) Offer to show the SPARQL queries used to generate the page. </li>
</ul>
<p>Notes: The website URL of the screenshot is: <a href="http://purl.obolibrary.org/obo/VO_0000002">http://purl.obolibrary.org/obo/VO_0000002</a>, which is directed to: <a href="http://www.ontobee.org/browser/rdf.php?o=VO&iri=http://purl.obolibrary.org/obo/VO_0000002">http://www.ontobee.org/browser/rdf.php?o=VO&amp;iri=http://purl.obolibrary.org/obo/VO_0000002</a>. The screenshot was generated on August 14, 2013. The content may change given time. </p>
<p>&nbsp;</p>
<p class="style1" id="rdf_deref">3. RDF/XML Output Dereferencing Ontology Term: </p>
<p>The following screenshot shows the elements of an RDF/XML output that dereferences an ontology term, for example,  VO term 'vaccine' (VO_0000002) (the same as above): </p>
<p style="text-align:center"><img src="rdf_deref_output.jpg" alt="RDF/XML output for referencing an ontology term" width="680" height="1177" align="middle"></p>
<p><em>Below is a description of these elements</em>:</p>
<ul style="list-style-type: none">
  <li>(1) XML stylesheet to transform the output to HTML version. </li>
  <li>(2) Introduction of xml namespace and resources.</li>
  <li>(3) The section of annotation properties  </li>
  <li>(4) The section of object properties (relations)  </li>
  <li>(5) The section of classes. </li>
  <li>(6) Equivalent class definition (part)  </li>
  <li>(7) Notification of the ontology version (as Annotation). </li>
  </ul>
<p id="#html_deref">Notes: The screenshot is the <strong>source</strong> of the website: <a href="http://purl.obolibrary.org/obo/VO_0000002">http://purl.obolibrary.org/obo/VO_0000002</a>, which is directed to: <a href="http://www.ontobee.org/browser/rdf.php?o=VO&iri=http://purl.obolibrary.org/obo/VO_0000002">http://www.ontobee.org/browser/rdf.php?o=VO&amp;iri=http://purl.obolibrary.org/obo/VO_0000002</a>. To open the source of a web page in Firefox, you can right click on the page and choose from the menu &quot;View Page Source&quot;. The screenshot was generated on August 14, 2013. The content may change given time. </p>
<p>&nbsp;</p>
<p class="style1" id="sparql">4. Ontobee SPARQL queries: </p>
<p>Since this is a big topic, we have generated a separate web page dedicated for the Ontobee SPARQL queries. </p>
<p>Here is the link: <a href="http://www.ontobee.org/tutorial/tutorial_sparql.php">http://www.ontobee.org/tutorial/tutorial_sparql.php</a></p>
<p>&nbsp;</p>
<p class="style1" id="ontobeep">5. Ontobeep ontology alignment and comparison: </p>
<p>--  To be continued ... </p>
<p>&nbsp;</p>
<p>-- Prepared by Oliver He, 8/14/2013, 8/17/2013. </p>
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
