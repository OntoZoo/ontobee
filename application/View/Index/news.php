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
 * @file news.php
 * @author Yongqun Oliver He
 * @author Zuoshuang Allen Xiang
 * @author Edison Ong
 * @since Sep 3, 2015
 * @comment 
 */
 
if (!$this) {
	exit(header('HTTP/1.0 403 Forbidden'));
}

?>

<?php require TEMPLATE . 'header.default.dwt.php'; ?>

<h3 class="head3_darkred">News</h3>
<p>The Ontobee program was  originated from a Vaccine Ontology (VO) web browser project launched in July  2008 in He Group. With recommendations and suggestions from many in the  ontology community, the VO browser technology was then applied in fall 2009 to  browse the Ontology for Biomedical Investigations (OBI) and later updated to  also display RDF content via xslt stylesheet. The name &quot;OntoBee&quot;  (later changed to &quot;Ontobee&quot;) was given in 2009. The website domain  name ontobee.org was registered by Oliver in 2010. By the end of 2010, all OBI  ontology term PURL IDs were by default resolved to Ontobee. The development and  improvement of Ontobee have benefited a lot from many suggestions, comments,  and discussions from the community (Thanks!). </p>
<p><strong>Here we have collected  a list of news related to Ontobee: </strong><br/>
(<em><strong>Note</strong></em>: The list usually does not include news about new ontology inclusion. It may not include  those technical issues discussed in  <a href="https://groups.google.com/forum/#!forum/ontobee-discuss">Google Ontobee-discuss</a>, <a href="https://sourceforge.net/p/ontobee/feature-requests/">SourceForge feature requests</a>, or   <a href="https://github.com/ontoden/ontobee/issues">Github issues</a>.) </p>
<br/>
<ul>
  <li><strong>1/9/2017</strong>: Ontobee source code in Github is updated. <a href="https://github.com/OntoZoo/ontobee/pull/97">https://github.com/OntoZoo/ontobee/pull/97</a></li>
  <li><strong>1/9/2017</strong>: The Ontobee paper, titled &quot;<a href="http://nar.oxfordjournals.org/content/45/D1/D347">Ontobee: A linked ontology data server to support ontology term dereferencing, linkage, query, and integration</a>&quot;, is published in <em>Nucleic Acids Research</em> 2017 Database issue. PMID: <a href="https://www.ncbi.nlm.nih.gov/pubmed/27733503">27733503</a>.	PMCID: <a href="https://www.ncbi.nlm.nih.gov/pmc/articles/PMC5210626/">PMC5210626</a>.</li>
  <li><strong>11/8/2015</strong>: Oliver and Edison announced to the update of the Ontobee program in the OBO-discuss and Ontobee-discuss email lists.</li>
  <li><strong>11/7/2015</strong>: Edison updated the Ontobee program to a new version. The old version is now moved to: <a href="http://oriontobee.hegroup.org/">http://oriontobee.hegroup.org/</a>.</li>
  <li><strong>10/19/2015</strong>: Oliver and Edison announced to the OBO-discuss and Ontobee-discuss email lists about a new beta version of Ontobee under testing.</li>
  <li><strong>8/4/2015</strong>: Edison  solved a major issue on searching for synonyms that are defined by synonym-related annotation properties in OboInOWL: has_exact_synonym, has_related_synonym, has_narrow_synonym, and has_broad_synonym. See the related <a href="https://github.com/ontoden/ontobee/issues/35">github issue (#35) </a>page for more detail. </li>
  <li><strong>8/4/2015</strong>: Edison Ong  (a bioinformatics graduate student in Oliver He lab) solved an issue related to finding terms when character length is 3 or less. See the related <a href="https://github.com/ontoden/ontobee/issues/37">github issue (#37)</a> page for more detail. We have also explained this issue in a simplified way in answering a question in <a href="faqs.php">FAQs</a>. </li>
  <li><strong>1/31/2015</strong>: The last day of Bin Zhao working in He lab as a programmer and bioinformatician. Bin has contributed a lot to the Ontobee project. </li>
  <li><strong>6/18/2014:</strong> In addition to csv format download, Bin added tsv and Excel download options for searched results. </li>
  <li><strong>6/16/2014:</strong> As suggested by Oliver, Bin added a feature in Ontobee search: provides an option for users to download searched results using csv format.</li>
  <li><strong>6/16/2014:</strong> As suggested by Oliver, Bin updated the Ontobee searching results display order: if the prefix of a searched term ID is the same as the ontology namespace, display it first. For example, if we <a href="<?php echo SITEURL; ?>search/?ontology=&keywords=planned+process&Submit2=Search+terms">search 'planned process'</a> (OBI_0000011), then the OBI ontology version of the term appears before other ontologies (e.g., VO) that import the same term from OBI. This display emphasizes the ontology that originally defines the term.   </li>
  <li><strong>5/19/2014:</strong> As <a href="https://github.com/ontoden/ontobee/issues/13">requested</a> by Chris Mungall  and suggested by Oliver, Bin added a feature to show pictures in Ontobee. See examples: <a href="http://purl.obolibrary.org/obo/UBERON_0001882">http://purl.obolibrary.org/obo/UBERON_0001882</a>. More examples: <a href="<? echo SITEURL; ?>/ontology/?o=UBERON&iri=http://purl.obolibrary.org/obo/UBERON_0001874">UBERON_0001874</a>, <a href="<? echo SITEURL; ?>ontology/?o=UBERON&iri=http://purl.obolibrary.org/obo/UBERON_0001875">UBERON_0001875</a>, <a href="<? echo SITEURL; ?>ontology/?o=UBERON&iri=http://purl.obolibrary.org/obo/UBERON_0002741">UBERON_0002741</a>.</li>
  <li><strong>4/2/2014:</strong> Bin fixed a bug and added <em>rdf:isDefinedBy</em> statement in the RDF version for each ontology term. See more about this issue on <a href="https://github.com/ontoden/ontobee/issues/7#issuecomment-39392404">Githhub</a>,  <a href="https://plus.google.com/+BernardVatant/posts/bj8VgRcbNfo">Google+</a>, or on <a href="https://groups.google.com/forum/#!topic/ontobee-discuss/KD_pATp-dMs">Ontobee-discuss</a>.</li>
  <li><strong>3/31/2014:</strong> With Oliver's suggestion, Bin fixed a bug to show general &quot;Annotations&quot; information for many ontologies listed in Ontobee. Such information could not be extracted and shown up on the cover page of an ontology for many non-OBO ontologies (<em>e.g.</em>, <a href="<?php echo SITEURL; ?>ontology/OCRe">OCRe</a>) or ontologies without &quot;OBO&quot; label (<em>e.g.</em>,<a href="<?php echo SITEURL; ?>ontology/BFO11"> BFO 1.1</a>). Based on his work, Bin also generated a new <a href="<?php echo SITEURL; ?>tutorial/sparql#ex5">SPAQL Example #5</a>.  </li>
  <li><strong>3/30/2014:</strong> Oliver  reorganized the Ontobee pages. Specifically, the previous &quot;Links&quot;  page was changed to &quot;Download&quot; page. The contents in the previous  &quot;Links&quot; page were merged to the pages of &quot;References&quot; and  &quot;Contact&quot; or kept in the &quot;Download&quot; page. This  &quot;Download&quot; page now only includes the information about Ontobee  source code, its download information, and its licensing information. </li>
  <li><strong>3/26-4/3/2014:</strong> Oliver  updated many Ontobee web pages, including History, Acknowledgements, FAQs,  References, and Links. </li>
  <li><strong>3/25/2014:</strong> Oliver and Bin changed the  name of the &quot;Ontostat&quot; tool from &quot;Ontostat&quot; to &quot;Ontobeest&quot;. Note that the URL of the program has kept the same: <a href="<?php echo SITEURL; ?>ontostat">http://www.ontobee.org/ontostat</a>. The reason we changed to the &quot;Ontobeest&quot; is that the  name  sounds like &ldquo;ontobeast&rdquo;, which fits well with our onto-animal series  like OntoFox, ontobee, ontodog, ... :-). As Bin (primary developer of the Ontobeest tool) commented, the new name  is &quot;fun and appropriate&quot;. </li>
  <li><strong>3/22/2014:</strong> Oliver updated the <a href="tutorial/index.php">Tutorial</a> page by adding the information about  Ontobee Statistics page (Ontostat). Oliver also generated an <a href="tutorial/tutorial_ontobeep.php">Ontobeep tutorial</a> page to introduce the Ontobeep features. </li>
  <li><strong>12/23/2013:</strong> Bin and Oliver cleaned up the Ontobee sourcecode, and then submitted the sourcecode to the new Ontobee github repository website: <a href="https://github.com/ontoden/ontobee">https://github.com/ontoden/ontobee</a>. The source code license is <a href="http://apache.org/licenses/LICENSE-2.0.html">Apache 2.0 open source license</a>. </li>
  <li><strong>10/11/2013:</strong> Several improvements were made by Bin on the Ontobee statistics proejct. A new <a href="ontostat/index.php">statistics coverpage</a> was also generated and shown on the Ontobee navigation bar. </li>
  <li><strong>9/30/2013</strong>: Mr. Bin Zhao in He lab developed an ontology statistics page for each ontology. An example is shown <a href="<?php echo SITEURL; ?>ontostat/?ontology=ICO">HERE</a> (for Informed Consent Ontology or ICO). This page can be linked by clicking on &quot;Detailed Statistics&quot; on the ontology home page such as the <a href="<?php echo SITEURL; ?>ontology/ICO">ICO webpage</a>. The program was made openly available on 9/30/2013. </li>
  <li><strong>8/29-9/1/2013</strong>: Ontologies and ontology terms in Ontobee could not be dereferenced. The reason is the broken Virtuoso RDF server. Oliver tried to resume the ontology RDF triiple store. However, the triple store was not stable and easily broken again. On 8/31, Yue helped to update and install the Virtuoso version 6.1.7. However, some compatibility issue occurred. New efforts will be put to ensure a smooth update to new Virtuoso version. Anyway, the RDF triple store database appeared back and stable for now. The daily RDF triple store update feature was temporarily closed. </li>
  <li><strong>8/22/2013:</strong> The Ontobee <a href="tutorial/tutorial_sparql.php">SPARQL tutorial </a>web page was generated by Bin Zhao and Olvier. </li>
  <li><strong>8/18/2013:</strong> The Ontobee development <a href="history.php">History</a> web page was generated and posted by Oliver. </li>
  <li><strong>8/14/2013:</strong> Oliver started to fill up information on the Ontobee <a href="tutorial/index.php">tutorial web page.</a> </li>
  <li>8/10/2013: A bug related to a special character handling was solved with Yue Liu's help. </li>
  <li><strong>8/2/2013:</strong> The <a href="https://groups.google.com/forum/#!forum/ontobee-discuss">Ontobee-discuss Google Group</a> was generated by Oliver. </li>
  <li><strong>7/29-30/2013:</strong> Ontobee was broken due to a broken RDF triple store. Oliver restarted the Virtuoso triple store and reloaded the OWL updated program., </li>
  <li><strong>7/7/2013:</strong> In the <a href="http://ncorwiki.buffalo.edu/index.php/2013_ICBO_OBO_tutorial">ICBO 2013 OBO Foundry tutorial session</a>, Jie Zheng and others presented and discussed Ontobee and related technologies (including OntoFox). Both Ontobee and OntoFox use the same Hegroup RDF triple store. When the RDF triple store is broken, both Ontobee and OntoFox won't work properly. </li>
  <li><strong>March 22, 2013</strong>: The last day of Allen's working in He lab. Oliver started to take the responsibility of maintaining, debugging, and updating Ontobee. Allen agreed to provide necessary consultation if needed. </li>
  <li><strong>Summer 2011 to 2013: </strong>Incremental improvements (including debugging) of Ontobee. The further Ontobee development during this period was  motivated by many requests from the community.  </li>
  <li><strong>Summer, 2011: </strong>Ontobee started to use <a href="http://sparql.hegroup.org/sparql/">Hegroup RDF triple store</a> to get all OWL files. Since Aug 2010, Ontobee had partially used Neurocommons RDF triple store. However, the Neurocommons RDF triple store  was not continuously maintained and updated. In addition, this Neurocommon triple store used unmerged ontologies, which led to the ignorance of all imports when the base ontology was queried from the triple store. Therefore, the usage of the unmerged version made  the data incomplete (i.e., the imported data missing from the base ontology) and slower query performance. </li>
  <li><strong>July, 2011</strong>: Oliver attended the 2nd ICBO meeting and presented the Ontobee work. The <a href="<?php echo SITEURL; ?>docs/Ontobee_ICBO-2011_Proceeding.pdf">first Ontobee paper</a> was also accepted for publication. As shown in the paper, the name &quot;OntoBee&quot; has been changed to &quot;Ontobee&quot;. </li>
  <li><strong>Dec 9, 2010: </strong>Allen presented Ontobeep, an extension and application of OntoBee, and used Ontobeep to study the IDO extensions in  the<a href="http://www.bioontology.org/wiki/index.php/IDO_Workshop_2010"> IDO Workshop 2010</a> at Baltimore Airport Hilton, Baltimore, MD, USA. The Ontobeep idea came from Oliver, and the Ontobeep program was co-developed by Allen and Oliver. See Allen's  presentation <a href="http://ontology.buffalo.edu/10/IDO/xiang.pptx">PPT file located in the IDO workshop</a> or the <a href="docs/20101208IDO.pdf">PDF  file located in Ontobee</a> server. </li>
  <li><strong>Aug-Dec, 2010:</strong> All OBI PURLs resolved to Ontobee. </li>
  <li><strong>Aug 26, 2010:</strong> Bjoern Peters  would like to link directly from an OBI ID to a corresponding website with definition, metadata, synonyms etc. Oliver He suggested to Bjoern that Ontobee could be used for dereferencing OBI ontology term IDs. Such a feature had been implemented for VO already at that time. Oliver further mentioned the unique feature of Ontobee that Ontobee &quot;managed to separate the web display and source OWL file output &quot; with one http: URL. What a user input an ontology VO term IRI, the use can find the HTM information from the Ontobee web site. However, if you look for the source of the web page, it's owl format and contains all information about this term. The automatical deferencing of OBI terms in Ontobee was not ready for OBI terms yet. One reason was that the forwarding system (from OBO PURLS to Ontobee) was not established. Chris Mungall  proposed to use OntoBee for all OBO foundry ontology term visualization. He group was working with Chris Mungell and Alan Ruttenberg to get more features available. Check the <a href="http://obi-developers.2851539.n2.nabble.com/linking-OBI-IDs-to-website-entry-td5467522.html#a5467598">email thread</a>. </li>
  <li><strong>May 18, 2010</strong>: Oliver registered the web domain name: ontobee.org. </li>
  <li><strong>Fall 2009 to early 2010</strong>: Allen was able to update his script to generate RDF/XML source code, which included a XSL link to the HTML document. The contents of the RDF and HTML documents are different. From any HTML page, if the source of the web page is checked, it's owl format and contains all information about this term. So we have managed to separate the web display and source OWL file output. This feature supports semantic web application. </li>
  <li><strong>Oct 05, 2009</strong>: Melanie Courtot recommended VOBrowser (i.e., OntoBee) to the OBI group. With Oliver's supervision and support, Allen Xiang from He Laboratory started to extend the OntoBee method for OBI. Check the <a href="http://obi-developers.2851539.n2.nabble.com/OBI-browser-and-SVN-access-td3770617.html">email thread</a>. </li>
  <li><strong>September, 2009</strong>: The name &quot;OntoBee&quot; was coined by Yongqun &quot;Oliver&quot; He, and the OntoBee project was set up with the URL: http://ontobee.hegroup.org/. This system stored an ontology (or ontologies) in a Virtuoso RDF database, and then used PHP and   SPARQL programming to access the rdf database and make sparql queries.   The tool&nbsp;could not    support SOAP.</li>
  <li><strong>July, 2008:</strong> The VO Browser (the early version of Ontobee) was launched in the <a href="http://www.violinet.org">VIOLIN</a> <a href="http://www.violinet.org/vaccineontology">Vaccine Ontology (VO)</a> website. Note: The old VO Browser URL (http://www.violinet.org/vaccineontology/vobrowser) was discontinued later and replaced by: <a href="<?php echo SITEURL; ?>ontology/VO">http://www.ontobee.org/ontology/VO</a>.</li>
  </ul>
<p>&nbsp;</p>
<p><strong>Notes:</strong></p>
<ul>
  <li>Link to OBI developers'  discussion emails that are related to Ontobee: <a href="http://obi-developers.2851539.n2.nabble.com/template/NamlServlet.jtp?macro=search_page&node=2851539&query=OntoBee">http://obi-developers.2851539.n2.nabble.com/template/NamlServlet.jtp?macro=search_page&amp;node=2851539&amp;query=OntoBee</a></li>
  <li>Search Google for &quot;Ontobee ontology&quot;: <a href="https://www.google.com/search?q=Ontobee+ontology">https://www.google.com/search?q=Ontobee+ontology</a></li>
  <li>Search Google Scholar for  &quot;Ontobee ontology&quot;:<a href="http://scholar.google.com/scholar?hl=en&q=Ontobee+ontology"> http://scholar.google.com/scholar?hl=en&amp;q=Ontobee+ontology</a></li>
  <li>See more acknowledgements on Ontobee development from the <a href="acknowledge.php">Acknowledgements</a> web page. </li>
  <li>Know more information about the  <a href="history.php">History of Ontobee Development</a>. </li>
</ul>
<p>&nbsp;</p>

<?php require TEMPLATE . 'footer.default.dwt.php'; ?>