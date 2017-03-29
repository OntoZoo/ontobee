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
 * @file ontobeep.php
 * @author Edison Ong
 * @since Mar 28, 2017
 * @comment 
 */
 
if ( !$this ) {
	exit( header( 'HTTP/1.0 403 Forbidden' ) );
}

?>

<?php require TEMPLATE . 'header.default.dwt.php'; ?>


<h3 class="head3_darkred">Tutorial: Ontobeep for aligment and comparison of ontologies listed in Ontobee </h3>

<p>The Ontobeep (<a href="http://www.ontobee.org/ontobeep">http://www.ontobee.org/ontobeep</a>) is a relatively independent tool in Ontobee. Ontobeep is targeted for ontology alignment and comparison of ontologies that are listed in Ontobee. Ontobee is able to display the similarities and differences among selected   ontologies. Ontobeep also provides a page to summarize statistical numbers out of the ontologies' alignment and comparison. </p>
<p><strong>Table of Contents </strong> </p>
<ol>
  <li><a href="#intro">Introduction of ontology alignment, matching, and comparison </a>    </li>
  <li><a href="#features">Ontobeep features</a>
    <ol type="i">
      <li><a href="#align">Ontology alignment and comparison </a></li>
        <li><a href="#stat">Display of ontology comparison statistics </a></li>
      </ol>
  </li>
  <li><a href="#refs">Ontology alignment tools, references, and web links</a>
    <ol type="i">
      <li><a href="#tools">Ontology alignment tools</a></li>
      <li><a href="#bookarticles">Books and review articles</a></li>
        <li><a href="#weblinks">Additional web links</a></li>
      </ol>
  </li>
</ol>
<br/>
<p class="style1" id="intro">1. Introduction of Ontology alignment, matching, and comparison: </p>
<p>Ontology alignment, or ontology matching, is the process of determining correspondences between terms in ontologies. Usually two ontologies are used for alignment. </p>
<p>Historically, the need for ontology alignment originated from the requirement of  integrating  hetereogeneous databases that were  developed independently with own data vocabulary.  Ontology alignment tools are able to find ontological classes &quot;semantically equivalent&quot;.  These classes are not necessarily logically identical. </p>
<p>There are many algorithms and tools available to support ontology alignment and comparison. Each of these tools tend to have different focuses based on dfiferent settings. For example, <a href="http://protegewiki.stanford.edu/wiki/PROMPT">PROMPT</a> and <a href="http://alviz.sourceforge.net/">AlViz</a> are two <a href="http://protege.stanford.edu/">Protege</a> plugin programs related to ontology alignments, where PROMPT focuses on ontology merging  and AlViz focuses on visual ontology alignments. <a href="http://wiki.knoesis.org/index.php/BLOOMS">BLOOMS</a> is an ontology alignment system for linked open data (LOD) schema alignment based on the idea of bootstrapping information already present on the LOD cloud. <a href="http://alignapi.gforge.inria.fr/">Aligment-API</a> is a Java API and implementation for expressing and sharing ontology alignments. More ontology alignment tools are listed in the section of <a href="#tools">&quot;Ontology alignment tools&quot;</a> on this web page. </p>
<p>Ontobeep is developed with a different emphasis in mind. Instead of matching different ontology terms by assuming different ontologies naturally use different labels and axioms to represent the same entities (or concepts), Ontobeep is developed by first assuming different ontologies are integrated and reuse terms that already exist in reference ontologies. This assumption is consistent with the <a href="http://www.obofoundry.org/crit.shtml">OBO Foundry principles</a>, including &quot;<a href="http://www.obofoundry.org/wiki/index.php/FP_005_delineated_content">FP 005 delineated content</a>&quot; and &quot;<a href="http://www.obofoundry.org/wiki/index.php/FP_010_collaboration">FP 010 collaboration</a>&quot;. Based on this assumption, Ontobeep aims to support ontology term  matching and reuse, compare hierarchical ontology  structures,  and identify possible redundancy and errors for future fixation.   </p>
<p>&nbsp;</p>
<p id="features"><span class="style1">2. Ontobeep features: </span></p>
<p>  This section  introduces individual features of Ontobeep:   </p>
<p class="style3" id="align">(i) Ontology alignment and comparison:  </p>
<p>On the cover page of Ontobeep, you will be able to select 2-3 ontologies using the Ontobeep comparison form. For example, here I compared three ontologies: BFO, CLO, and CL:   </p>
<p style="text-align:center"><img src="<?php echo SITEURL; ?>public/images/tutorial/Ontobeep.png" alt="Ontobeep selection" width="453" style="border:thin solid blue"></p>
<p>After clicking the &quot;Compare Selected&quot;, you will come to an Ontobeep page where you can click the &quot;Expand One Level Down&quot; or the &quot;+&quot;-containing square boxes to expand the ontology hierarchy. The next screenshot displays some comparison results (generated by Oliver He on 3/22/2014): </p>
<p style="text-align:center"><img src="<?php echo SITEURL; ?>public/images/tutorial/Ontobeep-comp-ex.png" alt="Ontobeep ex" width="469" style="border:thin solid blue"></p>
<p>As seen in the above screenshot, different ontologies are displayed with different colors, which help you to visualize the shared or unique terms among these ontologies.</p>
<p>In the above page, you can also click &quot;Statistics&quot;. Once you do so, you will come to the statistics display as seen below.  </p>
<p>&nbsp;</p>
<p class="style3" id="stat">(ii) Display of ontology comparison statistics:  </p>
<p>The following web page shows you an example term statistics page in Ontobeep. This example compared the term statistics results among BFO, CLO, and CL. </p>
<p style="text-align:center"><img src="<?php echo SITEURL; ?>public/images/tutorial/Ontobeep-termstat.png" alt="Ontobee term stat" width="470"   style="border:thin solid blue"></p>
<p>Note that the above screenshot (generated by Oliver He on 3/22/2014) suggests that some ontololgy improvements can possibly be done. For example, some terms have the same term labels and different term URIs. This can be solved by using only one URI and merging the usage of the other URI to this chosen URI. </p>
<p>&nbsp;</p>
<p class="style1" id="refs">3. Web links and References: </p>
<p class="style1" id="tools"><em>(i) Ontology alignment tools:</em></p>
<ul>
  <li><a href="http://agreementmaker.org/">AgreementMaker</a>: Efficient matching for large real-world schemas and ontologies</li>
  <li>AlViz - A tool for visual ontology alignment (<a href="http://disi.unitn.it/~p2p/RelatedWork/Matching/Alviz-IV06.pdf">paper PDF</a>) </li>
  <li> Biomixer: A web-based collaborative ontology visualization tool (<a href="http://ceur-ws.org/Vol-897/session4-paper21.pdf">PDF</a>) </li>
  <li><a href="http://wiki.knoesis.org/index.php/BLOOMS">BLOOMS</a>: Ontology alignment for linked open data (<a href="http://knoesis.org/pascal/resources/publications/BLOOMS.pdf">PDF</a>) <br>
  </li>
  <li><a href="http://www.bioontology.org/CogZ">CogZ</a>: Cognitive support and visualization for human-guided mapping systems</li>
  <li>Falcon-AO: A practical ontology matching system (<a href="http://disi.unitn.it/~p2p/OM-2007/5-o-Hu.OAEI.2007.pdf">PDF</a>) </li>
  <li><a href="http://dbs.uni-leipzig.de/GOMMA">GOMMA</a>: Generic Ontology Matching and Mapping Management (<a href="http://dbs.uni-leipzig.de/file/GOMMA Results for OAEI 2012_b.pdf">PDF</a>) <br>
  </li>
  <li>ITM Align: Semi-automated ontology alignment (<a href="http://www.mondeca.com/content/download/718/6964/file/ITM_ALIGN_en.pdf">PDF</a>) <br>
    </li>
  <li><a href="http://cobweb.cs.uga.edu/~uthayasa/Optima/Optima.html">Optima</a>: A visual ontology alignment tool</li>
  <li><a href="http://protegewiki.stanford.edu/wiki/PROMPT">PROMPT</a>: Automated ontology merging and alignment (<a href="http://cobweb.cs.uga.edu/~kochut/Teaching/8350/Papers/Ontologies/PROMPT.pdf">PDF</a>) </li>
  <li>Aligment-API and alignment Server: <a href="http://alignapi.gforge.inria.fr/">http://alignapi.gforge.inria.fr/</a><br>
  </li>
</ul>
<p class="style4" id="bookarticles">(ii) Selected books and articles:   </p>
<ul>
  <li><span class="style5"><em>Books</em>: </span></li>
  <li>J&eacute;r&ocirc;me Euzenat, Pavel Shvaiko. <a href="http://www.springer.com/computer/database+management+&+information+retrieval/book/978-3-642-38720-3">Ontology Matching</a>. <em>Springer</em>. 2013. ISBN: 978-3-642-38720-3 (Print) 978-3-642-38721-0 (Online)</li>
  <li></li>
  <li><strong><em>Articles</em></strong><em> (ordered by publication time)</em><strong><em>:</em></strong></li>
  <li>Y Kalfoglou, M Schorlemmer. <a href="http://eprints.soton.ac.uk/260519/1/ker02-ontomap.pdf">Ontology mapping: the state of the art</a>. <em>The Knowledge Engineering Review</em>. 2003. 18(01):  1-31.</li>
  <li>Davide Fossati, Gabriele Ghidoni, Barbara Di Eugenio, Isabel Cruz, Huiyong Xiao, Rajen Subba. <a href="http://disi.unitn.it/~p2p/RelatedWork/Matching/align-EACL06.pdf">The problem of ontology alignment on the web: a first report</a>. <em>Proceeding WAC '06 Proceedings of the 2nd International Workshop on Web as Corpus</em>. <br>
  </li>
  <li>Sean M. Falconer and Margaret-Anne Storey. <a href="http://iswc2007.semanticweb.org/papers/113.pdf">A cognitive support framework for ontology mapping</a>. The Semantic Web, 2007 - Springer.</li>
  <li>Viviana Mascardi, Angela Locoro, Paolo Rosso. <a href="http://www.disi.unige.it/person/MascardiV/Download/TKDE-maggio-2009.pdf">Automatic ontology matching via upper ontologies: A systematic evaluation</a>. Knowledge and Data Engineering, <em>IEEE Transactions</em> on Volume:22, Issue: 5. </li>
  <li>Pavel Shvaiko, J&eacute;r&ocirc;me Euzenat. <a href="http://disi.unitn.it/~p2p/RelatedWork/Matching/SurveyOMtkde_SE.pdf">Ontology matching: State of the art and future challenges</a>.<em> IEEE Transactions on Knowledge and Data Engineering</em>, vol. 25, no. 1, pp. 158-176, Jan. 2013, doi:10.1109/TKDE.2011.253.</li>
  <li>Heiko Paulheim, Sven Hertling, Dominique Ritze. <a href="http://eswc-conferences.org/sites/default/files/papers2013/paulheim.pdf">Towards evaluating interactive ontology matching tools</a>. <span prefix="dc: http://purl.org/dc/terms/ isbd: http://iflastandards.info/ns/isbd/elements/" about="http://ub-madoc.bib.uni-mannheim.de/33363" typeof="dc:BibliographicResource isbd:C2001">In: <em><span property="isbd:P1162">Lecture Notes in Computer Science</span> <span property="isbd:P1137 dc:title">The semantic web : semantics and big data ; 10th international conference ; proceedings / ESWC 2013</span></em>; <span property="isbd:P1054">31-45</span>. <span property="dc:publisher">Springer</span>, <span property="isbd:P1016">Berlin [u.a.]</span>, <span property="dc:issued">2013</span></span>. </li>
  </ul>
<p class="style4" id="weblinks">(iii) Ontology alignment-related web links:  </p>
<ul>
  <li>Wiki page on Ontology Alignment: <a href="http://en.wikipedia.org/wiki/Ontology_alignment">http://en.wikipedia.org/wiki/Ontology_alignment</a></li>
  <li>Ontology Matching: <a href="http://www.ontologymatching.org/">http://www.ontologymatching.org/</a> </li>
  <li>OAEI - Ontology Alignment Evaluation Initiative: <a href="http://oaei.ontologymatching.org/">http://oaei.ontologymatching.org/</a></li>
  <li><a href="http://disi.unitn.it/~p2p/matching/SWAP06-OMtutorial.pdf">Tutorial on Ontology Matching</a> (presentation slides),  by Pavel Shvaiko and  J&eacute;r&ocirc;me Euzenat. </li>
</ul>
<p>&nbsp;</p>
<p><strong>Back to <a href="<?php echo SITEURL; ?>tutorial">Ontobee Tutorial</a> page </strong></p>

<p>-- Prepared by Oliver He, 3/22-23/2014.</p>

<?php require TEMPLATE . 'footer.default.dwt.php'; ?>