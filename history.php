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
Purpose: Ontobee history page.
-->

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html><!-- InstanceBegin template="/Templates/default.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<!-- InstanceBeginEditable name="doctitle" -->
<title>Ontobee</title>
<!-- InstanceEndEditable --><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="shortcut icon" href="/favicon.ico" />
<link href="css/styleMain.css" rel="stylesheet" type="text/css">
<!-- InstanceBeginEditable name="head" --><!-- InstanceEndEditable -->
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
<div id="topbanner"><a href="/index.php" style="font-size:36px; color:#111144; text-decoration:none"><img src="images/logo.gif" alt="Logo" width="280" height="49" border="0"></a></div>
<div id="topnav"><a href="index.php" class="topnav">Home</a><a href="introduction.php" class="topnav">Introduction</a><a href="ontostat/index.php" class="topnav">Statistics</a><a href="sparql/index.php" class="topnav">SPARQL</a><a href="ontobeep/index.php" class="topnav">Ontobeep</a><a href="tutorial/index.php" class="topnav">Tutorial</a><a href="faqs.php" class="topnav">FAQs</a><a href="references.php" class="topnav">References</a><a href="links.php" class="topnav">Links</a><a href="contactus.php" class="topnav">Contact</a><a href="acknowledge.php" class="topnav">Acknowledge</a><a href="news.php" class="topnav">News</a></div>
<div id="mainbody">
<!-- InstanceBeginEditable name="Main" -->
<h3 class="head3_darkred">The History of Ontobee Development </h3>
<p>The Ontobee program was originally developed as a web browser for the Vaccine Ontology (VO) in He Group as part of Dr. He's NIH R01 grant study. It has now gained a high popularity in the ontology community. Currently, Ontobee is the default linked  data server for most OBO Foundry and OBO library ontologies. The name &quot;OntoBee&quot; (later changed to &quot;Ontobee&quot;) was given in  2009. The website domain name ontobee.org was registered by Oliver in 2010. </p>
<p>Many people are wondering and have been asking  the history of Ontobee and why the name &quot;Ontobee&quot; was given. Before the history becomes too long to be forgotten, we prepared this web page. Roughtly, the  history of the Ontobee development can be split into the following three stages: </p>
<p><strong>Stage 1: Development of the first version of Ontobee with our model ontology Vaccine Ontology (VO) [Time period: early 2008 to the end of 2009]: </strong></p>
<blockquote>
  <p>Since early 2008, Dr. Oliver He has initiated and led the development of a community-based Vaccine Ontology (VO; <a href="http://www.violinet.org/vaccineontology">http://www.violinet.org/vaccineontology</a>). The initial research on VO development  got a lot of support and collaboration from  many ontology developers, especially Barry Smith and Lindsay Cowell. Dr. He received his NIH-NIAID R01 grant (R01AI081062) in September 2009, which provided further funding on the VO development and VO applications. </p>
  <p>While we were developing VO,  Allen Xiang, a bioinformatician in He Laboratory, tried to develop a VO web browser under Dr. He's mentorship. The first VOBrowser was launched in July, 2008. The original VOBrowser was based on the <a href="http://www.co-ode.org/downloads/owldoc/">OWLDoc</a> program. One issue in our use of OWLDoc was that we had to generate one individual HTML file for one ontology term. When the VO became bigger and bigger, we generated so many individual HTML files that we felt it would be not feasible to maintain and keep this approach. Then, Oliver suggested to Allen to  use the XSLT scripting technology based on Oliver's experience in developing the <a href="http://ci.vbi.vt.edu/pathinfo/index.php">PIML  project</a> (Reference: 
    He Y, et al. PIML: the Pathogen Information Markup Language. <em>Bioinformatics</em>.  2005 Jan 1;21(1):116-21. PMID: <a href="http://www.ncbi.nlm.nih.gov/pubmed/15297293" target="_blank">15297293</a>) at the Virginia Bioinformatics Institute (VBI), Blacksburg, VA, USA. 
    In the  PIML project, Oliver  wrote an <a href="http://ci.vbi.vt.edu/pathinfo/piml-docs/pathinfo.xsl">XSLT script program</a> to transform the XML-based PIML to HTML  files.  Oliver suggested to Allen to use a similar strategy. Oliver and Allen  futher discussed this and made a significant and novel update.  Instead of directly parsing the VO OWL file using XSLT,  Allen stored VO to an internally generated Virtuoso RDF triple store and then retrieved related ontology information from the RDF triple store using SPARQL. Then he developed one XSLT script (in the same spirit to Oliver's code) that generates HTML  output. The XSLT script embeds HTML code and ontology contents queried using SPARQL from the Hegroup Virtuoso RDF triple store. This script was applied to all individual VO ontology terms, leading to the generation of a nice VOBrowser that displayed the ontology term hierarchy and other information nicely and automatically. In this way, we did not need to generate thousands of HTML files and store them statically. One XSLT-based script program was  sufficient and can dynamically generate HTML files with users' requests. </p>
  <p>The further development of Ontobee was inspired by a paper &quot;<a href="http://webont.org/owled/2008/papers/owled2008eu_submission_38.pdf">The OWL of Biomedical Investigations</a>&quot;, published in the conference <a href="http://webont.org/owled/2008/">OWLED 2008</a>, and authored by the Ontology of Biomedical Investigations (OBI) developers Melanie Courtot, Alan Ruttenberg, etc. This paper introduces a prototype of presenting ontology term using thePersistent Uniform Resource Locator (PURL) and XSL stylesheet. <strong>Note</strong>: Here is an introduction of the prototype from Alan Ruttenberg on 8/6/2013:  &quot;Incidentally,  the prototype was not an OBI project, but rather one that sprung from work that Jonathan Rees and I did working for Science Commons. Through my work on OBI we used OBI as a use case and therefore brought OBI in as a working example. Melanie was most involved as the initial developer from OBI who was interested in the issue and helped guide development of the OBI proof of concept from the first protoptype I implemented using the Neurocommons wiki.  See <a href="http://neurocommons.org/page/CommonsPurl">http://neurocommons.org/page/CommonsPurl</a>. 
    For the OBI prototype, where we couldn't use semi-static templates, the proof of concept was to generate static RDF html by code that used (I can't remember which atm) either the Pellet API, or the OWLAPI. We intended to deploy, at first, by generating these static pages as part of the OBI Build process.&quot;  </p>
  <p> In the prototype using an OBI term as an example, an XSL stylesheet was designed to present HTML code by transforming the OBI OWL file. From our VOBrowser development experience, it was difficult to generate an XSLT based on an OWL file because the OWL file may frequently change (it is usually the case). However, it was much easier to use the SPARQL-retrieved and further processed data as input for an XSLT program development. After realizing the potential usage of an RDF/XML output, Oliver advised Allen to do this: In addiiton to an HTML output for a VO ontology term, extend the VOBrowser program by including an RDF/XML output for each ontology term. Briefly, the updated VOBrowser program used SPARQL to query ontology term information from the Hegroup RDF triple store, processed the contents, and then generated RDF/XML output file. In the beginning of the RDF/XMLoutput file, an XSL stylesheet can direct a Web browser to display a HTML output. Since both HTML and OWL/RDF version directly come from RDF triple store, different contents can be separately designed. Besides SPARQL/RDF triple store and XSLT, Ontobee also used other technologies including JSON, OWLAPI, and PHP. After deligent and hard work by Allen and Oliver, Ontobee was eventually able to dereference individual VO ontology URIs by outputing an RDF/XML output, and meanwhile, providing well-structured HTML output for web browser visualization.  The RDF/XML output can be seen in a web browser if one searches for the source code of the HTML display. Based on the Principles of <a href="http://www.w3.org/DesignIssues/LinkedData.html">Linked Data</a>, such a system of dereferencing both RDF and HTML would be ideal. </p>
  <p>   To our knowledge, the updated VOBrowser program,  developed by Oliver and Allen, was  the first  web-based linked data server that successfully dereferenced each ontology term from a whole ontology (VO in this case) using the XSLT/SPARQL//RDF triple store technologies. As described above, our project  design was inspired by the prototype generated by Alan, Jonathan, Melanie, and OBI developers. However, our technical system architecture design had its unique aspects, which made it practically feasible to achieve the goal. </p>
  <p>After realizing the potential use of the same technology on other ont';[logies, Oliver changed the name &quot;VOBrowse&quot; to &quot;OntoBrowser&quot; (briefly) , and then to &quot;OntoBee&quot; in September, 2009. The giving of this name was inspired by the name of another ontology-based software program  <a href="http://ontofox.hegroup.org/">OntoFox</a>. After coining the project name OntoFox, Oliver was thinking about another  short animal name for the next version of VOBrowser. After   imagining a bee flying and hanging over a flower as a pollinator, Oliver quickly thought  &quot;OntoBee&quot; would be a good name. Allen also liked the name. The website for this OntoBee project was then changed to: http://ontobee.hegroup.org/. </p>
  <p>In summary, during the first stage of development, Allen and Oliver successfully developed the VOBrowser and initiated the OntoBee program. The basic functions of displaying RDF and HTML output formats were established using the VO as model ontology. While their research and development were inspired by the OBI prototype and other progesses in the area of Sementic Web,  Oliver and Allen basically worked alone on the project without collaborating with others.  </p>
  <p><em>Historic Quotes by Oliver</em>: </p>
  </blockquote>
<ul>
  <li>&quot;Our strategy is simple: we store an ontology in a Virtuoso RDF database, and then use PHP and SPARQL programming to access the rdf database and make sparql queries. Therefore, I am sorry to say that our tool (we name OntoBee) cannot support SOAP. Basically, OntoBee will provide an VOBrowser like ontology browsing feature: 
    http://www.violinet.org/vaccineontology/vobrowser/ 
    and will also include a SPARQL script web interface like the following: http://www.violinet.org/vaccineontology/sparql/index.php&quot; (Note: quoted from an email dated on Oct 6, 2009, from the <a href="http://obi-developers.2851539.n2.nabble.com/OBI-browser-and-SVN-access-td3770617.html">Email thread</a>). </li>
  <li>In an email sent on 11/17/2009 from Oliver to Allen, Oliver wrote the following sentences in a draft of an OntoBee paper: &quot;We developed OntoBee (http://ontobee.hegroup.org/), a web server aimed to facilitate ontology visualization, query, and development. OntoBee provides a user-friendly web interface for displaying the details and its hierarchy of a specific ontology term. Meanwhile, OntoBee provides a RDF source code for the particular web page, which supports remote query of the ontology term and the Semantic Web.&quot; </li>
</ul>
<p><strong>Stage 2: Further Development and application of Ontobee for OBI and other ontologies [Time period: the end of 2009 to summer  2011]: </strong></p>
<blockquote>
  <p> Realizing the potential appliaction of  VOBrowser (i.e., OntoBee) to viewing the Ontology for Biomedical Investigations (OBI), Melanie sent <a href="http://obi-developers.2851539.n2.nabble.com/OBI-browser-and-SVN-access-td3770617.html">an email</a> on Oct 5, 2009, to recommend VOBrowser (i.e., OntoBee) to the OBI group. Oliver offered to share the source code and help. After that, Allen and Oliver continued to develop OntoBee, with an aim to make OntoBee work better for VO and also possibly use it for other ontologies. At this stage, we obtained a lot of help from the ontology community, esp. Alan and Chris. </p>
  <p>To ensure  that computers can understand the semantics of terms, it is required to dereference ontology term uniform resource identifier (URIs). TheURI dereferencing represents the act of retrieving a representation of a resource identified by a URI. However, how to present the meaning of ontology terms by its URI  was a challenge  in 2009. VO ontology  URIs were not dereferencable since they did not point to real web pages dedicated for the individual URIs. When some URIs of an ontology pointed to specific pages, the pages shown were often in pure HTML  format or in OWL (RDF/XML) format which contained the whole ontology instead of  individual terms. In both cases these pages did not efficiently support the Semantic  Web and Linked Data principles. As an OBO library ontology, VO ontology term URIs use the format: http://purl.obolibrary.org/obo/VO_xxxxxxx. To properly dereference VO URIs, Oliver and Allen requested help from Chris Mungall and OBO Foundry group to automatically forward VO OBO PURL to VOBrowser and then OntoBee for default display. Eventually the OntoBee system worked out well. For example, the VO class 'DNA vaccine' (<a href="http://purl.obolibrary.org/obo/VO_0000032" target="_blank" rel="nofollow" link="external">http://purl.obolibrary.org/obo/VO_0000032</a>) would  directly be linked to an OntoBee site for display, and the source code of the HTML page was provided in RDF/XML format (instead of HTML format).</p>
  <p>To achieve the task of using the same VOBrowser method for other ontologies, Allen and Oliver worked together with Chris and Alan. The Hegroup RDF triple store  stored only VO and a couple of other ontologies.  To access OBI and  other OBO library ontologies, since August 2010, we started to use the Neurocommons RDF triple store that was developed by Alan's group. Since both RDF triple stores used the Virtuoso system, both RDF triple stores were used by OntoBee without much compatibility problem. During this period, Alan provided a lot of support to Allen on how the Neurocommons RDF triple store could be accessed and queried through SPARQL. Due to incontinuous maintenance and updates, Ontobee started to use  only the Hegroup RDF triple store from  the summer 2011.  </p>
  <p>On May 18, 2010, Oliver registered the web domain name: ontobee.org. After that, the Ontobee website became http://www.ontobee.org instead of http://ontobee.hegroup.org. Oliver decided to make the update and pay the domain registration fee because he predicted that the tool could be widely used as a community-based program, a new domain name might boost its usage.  After consultation with many, Oliver also changed the name &quot;OntoBee&quot; to &quot;Ontobee&quot;. </p>
  <p>On Aug 26, 2010, Bjoern Peters emailed to OBI-devel &quot;As we are starting to use OBI IDs in our production system, we would like to link directly from an OBI ID to a corresponding website with definition, metadata, synonyms etc. I know Alan had previously prototyped such a page; is their an estimate when this would be available?&quot;  Oliver replied the email &quot;It's now available for VO IDs. For example, if you click on the VO class 'DNA vaccine': http://purl.obolibrary.org/obo/VO_0000032, it will directly link to: http://www.violinet.org/vaccineontology/vobrowser/rdf.php?iri=http://purl.obolibrary.org/obo/VO_0000032. In addition, if you look for the source of the web page, it's owl format and contains all information about this term. So we have managed to separate the web display and source OWL file output. This feature has partially been expanded to all other ontologies in OntoBee: http://www.ontobee.org/. It contains OBI ... We don't have a direct link to the above page from a click on the URL yet: http://purl.obolibrary.org/obo/OBI_0000426. 
    Some work needs to be done first from http://purl.obolibrary.org/obo/ to make this occur.&quot; Bjoern replied that &quot;Oliver: That looks really nice, excellent!&quot; (Note: all quotes are from <a href="http://obi-developers.2851539.n2.nabble.com/linking-OBI-IDs-to-website-entry-td5467522.html">the email thread</a>).</p>
  <p>Initially, when SPARQL queries were complex, our Ontobee execuation was often slow. Alan helped Allen to optimize the SPARQL queries. For example, Alan introduced to Allen the Concise Bounded Description (CBD) technique. After this, the Ontobee query became much faster especially when complex queried were executed.  </p>
  <p>By the end of 2010, all OBI ontology term PURL IDs resolved to Ontobee. The same was for many other OBO library ontology terms.   </p>
  <p>The Ontobee program can be used for different applications. One application would be ontology alignment, comparison, and analysis. Oliver was developing a Brucellosis Ontology, as an extension of the Infectious Disease Ontolgy core (IDO-core). Other IDO-core extensions, including Malaria Ontology and Influenza Ontology, were also being developed. Under Oliver's suggestions, Allen developed the Ontobeep program targeted for aligning and comparing different ontologies, such as IDO-core and all its extensions. In  <a href="http://www.bioontology.org/wiki/index.php/IDO_Workshop_2010"> IDO Workshop 2010</a> held on Dec 8-9, 2010, Allen  presented the program and its usage in the alignment and comparion between IDO-core and its extensions (see the <a href="http://ontology.buffalo.edu/10/IDO/xiang.pptx">PPT file</a> in the worshop side, or the <a href="http://www.ontobee.org/docs/20101208IDO.pdf">PDF</a> copy in Ontobee site). </p>
  <p>In the 2nd International Conference on Biomedical Ontologies (ICBO) held on July 2011, the Ontobee was presented as a short paper by Oliver and Allen in the 2nd International Conference on Biomedical Ontologies (ICBO). The Ontobee proceeding paper file is here: <a href="http://ceur-ws.org/Vol-833/paper48.pdf">http://ceur-ws.org/Vol-833/paper48.pdf</a>. </p>
  <p>In summary, at the second stage, we were able to further develop Ontobee and made it successful and the default linked data sever for VO, OBI, and many other ontologies. During this stage, the development of this project obtained many help from the community, esp. Alan Ruttenberg and Chris Mungall. </p>
</blockquote>
<p><strong>Stage 3: Further development and application of Ontobee [from summer 2011 to now]: </strong></p>
<blockquote>
  <p>This project has been under continuous development. Almost all OBO libary ontologies are now using Ontobee as its default linked data server. Many non-OBO ontologies are also included in Ontobee.</p>
  <p>Many researchers from the ontology community, esp. the OBO Foundry community, have provided a lot of suggestions and comments. Thank you! </p>
  </blockquote>
<blockquote>&nbsp;</blockquote>
</p> 
- The page was generated by Oliver He on 8/18/2013. 
<p><strong>Disclaimer:</strong> The information introduced in this page was provided  primarily based on preserved electronic records. It was prepared to Oliver's best knowledge. If you find any description inaccurate or incomplete, please contact Oliver for possible correction. </p>
<p>&nbsp; </p>
<!-- InstanceEndEditable -->
</div>
<div id="footer">
  <div id="footer_hl"></div>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><div id="footer_left"><a href="http://www.hegroup.org" target="_blank">He Group</a><br>
University of Michigan Medical School<br>
Ann Arbor, MI 48109</div></td>
		<td width="300"><div id="footer_right"><a href="http://www.umich.edu" target="_blank"><img src="images/wordmark_m_web.jpg" alt="UM Logo" width="166" height="20" border="0"></a></div></td>
	</tr>
</table>
</div>
</body>
<!-- InstanceEnd --></html>
