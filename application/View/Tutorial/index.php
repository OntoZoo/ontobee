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
 * @file index.php
 * @author Edison Ong
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

<h3 class="head3_darkred">Tutorial</h3>

<p>In general, the Ontobee HTML website is  easy to use and understand. You can usually search and navagate the linked results following your intuition. The source RDF or OWL output may need some explanation. </p>
<p>Below is a tutorial that introduces some key features of the Ontobee system. </p>
<p><strong>Table of Contents</strong></p>
<ol>
  <li><a href="<?php echo SITEURL; ?>tutorial/#query">Ontobee web data query engine</a></li>
  <li><a href="<?php echo SITEURL; ?>tutorial/#html_deref">HTML web interface derefencing ontology term</a></li>
  <li><a href="<?php echo SITEURL; ?>tutorial/#rdf_deref">RDF output deferencing ontology term</a></li>
  <li><a href="<?php echo SITEURL; ?>tutorial/#ontobeest">Ontobeest: Ontobee-based ontology statistics extraction and display  </a></li>
  <li><a href="<?php echo SITEURL; ?>tutorial/#sparql">Ontobee SPARQL queries</a> (directly link to the<a href="<?php echo SITEURL?>tutorial/sparql"> SPARQL tutorial</a> page) </li>
  <!-- <li><a href="#ontobeep">Ontobeep ontology alignment and comparison</a> (directly link to the<a href="tutorial_ontobeep.php"> Ontobeep tutorial</a> page) </li> -->
</ol>
<br/>
<p class="style1" id="query">1. Ontobee Web Data Query Engine: </p>
<p>Below is an introduction of the basic features on the Ontobee web form-based query engine: </p>
<p class="style2">(1) Ontobee web query interfaces:  </p>
<p>The Ontobee data query engine is composed of two types: one is the query program located in the Ontobee cover page, the other is the query program in the cover page of each individual ontology. The basic functions of these two types of queries  are similar. One main difference is that the latter is only dedicated for the searching of a specific ontology, and the former allows querying one or all ontologies listed in Ontobee. The other difference is that the Ontobee query engine in the Ontobee cover page provides a &quot;<em>Jump to http://purl.obolibrary.org/obo/</em>&quot; function. </p>
<p style="text-align:center"><img src="<?php echo SITEURL; ?>public/images/tutorial/queryinterface.jpg" alt="Web query interfaces" width="493" height="203"></p>
<p>The Ontobee query engine in the Ontobee cover page allows a user to search all ontologies listed in Ontobee together or select a specific ontology for querying ontology terms in the single ontology.  </p>
<p class="style2">(2) Auto-completion search:  </p>
<p class="style3">Introduction of the auto-completion searching function: </p>
<p>The jQuery JavaScript Library  is used for the development of Ontobee ontology term searching program. The auto-completion feature of the searching function was built based on the jQuery library. When a  user types three or more letters, the Ontobee search program will list all hit terms in  an alphabetic order in a drop-down menu. This function is available for the general query program and the ontology-specific query program: </p>
<p style="text-align:center"><img src="<?php echo SITEURL; ?>public/images/tutorial/autocomp1.jpg" alt="auto completion 1" width="574" height="299"></p>
<p class="style3"> Selection of auto-complete query result: </p>
<p>Aftdr the list of hits is displayed, one can be selected, and then the web site of the ontology term will be accessed. </p>
<p  style="text-align:center"><img src="<?php echo SITEURL; ?>public/images/tutorial/autocomp2.jpg" width="836" height="517"></p>
<p>&nbsp;</p>
<p class="style2">(3) Classical Search (by clicking &quot;Search terms&quot;): </p>
<p>Alternatively, a user can type searching characters, and then click the &quot;Search terms&quot; button. A list of matched terms will then be displayed in a list. The list of hits is sorted in an order to first show terms that start  with the search string, from the shortest to the longest, then terms that include the  typed string, from the shortest to the longest. Then a user can select one URI to link to the ontology term page. </p>
<p style="text-align:center"><img src="<?php echo SITEURL; ?>public/images/tutorial/query_search.jpg" width="404" height="329"></p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p class="style1" id="html_deref">2. HTML web Interface Dereferencing Ontology Term: </p>
<p>The following screenshot shows the elements of an HTML web page dedicated for a specific ontology term, for example, VO term 'vaccine' (VO_0000002) in this case: </p>
<p style="text-align:center"><img src="<?php echo SITEURL; ?>public/images/tutorial/html_deref_output.jpg" alt="Deferencing ontology term URI by HTML" width="597" height="715" ></p>
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
<p>Notes: The website URL of the screenshot is: <a href="http://purl.obolibrary.org/obo/VO_0000002">http://purl.obolibrary.org/obo/VO_0000002</a>, which is directed to: <a href="<?php echo SITEURL; ?>ontology/VO&iri=http://purl.obolibrary.org/obo/VO_0000002"><?php echo SITEURL; ?>ontology/VO&amp;iri=http://purl.obolibrary.org/obo/VO_0000002</a>. The screenshot was generated on August 14, 2013. The content may change given time. </p>
<p>&nbsp;</p>
<p class="style1" id="rdf_deref">3. RDF/XML Output Dereferencing Ontology Term: </p>
<p>The following screenshot shows the elements of an RDF/XML output that dereferences an ontology term, for example,  VO term 'vaccine' (VO_0000002) (the same as above): </p>
<p style="text-align:center"><img src="<?php echo SITEURL; ?>public/images/tutorial/rdf_deref_output.jpg" alt="RDF/XML output for referencing an ontology term" width="680" height="1177" align="middle"></p>
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
<p id="#html_deref">Notes: The screenshot is the <strong>source</strong> of the website: <a href="http://purl.obolibrary.org/obo/VO_0000002">http://purl.obolibrary.org/obo/VO_0000002</a>, which is directed to: <a href="<?php echo SITEURL; ?>ontology/VO&iri=http://purl.obolibrary.org/obo/VO_0000002"><?php echo SITEURL; ?>ontology/VO&amp;iri=http://purl.obolibrary.org/obo/VO_0000002</a>. To open the source of a web page in Firefox, you can right click on the page and choose from the menu &quot;View Page Source&quot;. The screenshot was generated on August 14, 2013. The content may change given time. </p>
<p>&nbsp;</p>
<p class="style1" id="ontobeest">4. Ontobeest: Extraction and display of ontology Statistics: </p>
<p>The <a href="<?php echo SITEURL; ?>statistic">Ontobeest tool</a>   is an Ontobee program that extracts and displays detailed statistics of ontologies listed in  Ontobee. The Ontobeest tool was primarily developed by Mr. Bin Zhao in Dr. Oliver He's laboratory. </p>
<p>The Ontobeest cover page provides the statistics of all the ontologies listed in Ontobee. Below is a screenshot image that contains  the top and bottom parts of the overall ontology statistics form shown in the <a href="<?php echo SITEURL; ?>statistic">Ontobeest coverpage</a>. This image  was generated by Oliver on 3/22/2014 (Saturday). </p>
<p style="text-align:center"><img src="<?php echo SITEURL; ?>public/images/tutorial/ontostat_all.jpg" alt="Statistics of all ontologies" width="680" align="middle"></p>

<p>Once you click on any specific ontology (e.g., VO), you can obtain detailed   information about the statistics on the specific ontology. For example, here is the information of the <a href="<?php echo SITEURL; ?>statistic/VO">VO statistic</a>s (screenshot generated on 03/22/2014):</p>
<p style="text-align:center"><img src="<?php echo SITEURL; ?>public/images/tutorial/VO_stat_03222014.png" alt="VO statistics March 2014" width="680"></p>
<p>In addition,   you can access the same statistics page for an  ontology by clicking   "Detailed Statistics" from the cover page of the specific ontology, for example, for the Vaccine Ontology (<a href="http://www.ontobee.org/browser/index.php?o=VO">VO</a>): </p>
<p style="text-align:center"><img src="<?php echo SITEURL; ?>public/images/tutorial/VO_stat_access.png" width="380"></p>
<p><strong>Note:</strong> This version of Ontobeest statistics metrics does not include any instance data. This feature will be implemented in the future. </p>
<p>&nbsp;</p>
<p class="style1" id="sparql">5. Ontobee SPARQL queries: </p>
<p>Since this is a big topic, we have generated a separate  web page  dedicated for introducing the Ontobee SPARQL queries. </p>
<p>Here is the link: <a href="<?php echo SITEURL; ?>tutorial/sparql"><?php echo SITEURL; ?>tutorial/sparql</a></p>
<p>&nbsp;</p>
<!-- 
<p class="style1" id="ontobeep">6. Ontobeep ontology alignment and comparison: </p>
<p>Since Ontobeep is a relative big and indepenent topic, we have generated a separate web page dedicated for introducing Ontobeep: </p>
<p><a href="tutorial_ontobeep.php">http://www.ontobee.org/tutorial/tutorial_ontobeep.php</a></p>
<p>&nbsp;</p>
 -->
<p>-- Prepared by Oliver He, 8/14/2013, 8/17/201; Updated by Oliver with the Ontostat tutorial on  3/23/2014. </p>
<p>&nbsp;</p>

<?php require TEMPLATE . 'footer.default.dwt.php'; ?>