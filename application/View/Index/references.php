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
 * @file reference.php
 * @author Edison Ong
 * @since Sep 3, 2015
 * @comment 
 */
 
if (!$this) {
	exit(header('HTTP/1.0 403 Forbidden'));
}

?>

<?php require TEMPLATE . 'header.default.dwt.php'; ?>

<h3 class="head3_darkred">References and Links </h3>
<p>Many references have been used for Ontobee development or are related to Ontobee. Many web links also exist.  This web page provides the paper reference you can use to cite Ontobee, references related to Ontobee, and many useful web links:   </p>

<p><strong>Please cite Ontobee with the following reference:</strong></p>
<ul>
  <li>Xiang Z, Mungall C, Ruttenberg A, He Y. <a href="<?php echo SITEURL; ?>docs/Ontobee_ICBO-2011_Proceeding.pdf">Ontobee: A Linked Data Server and Browser for Ontology Terms</a>. <em>Proceedings of the 2nd International Conference on Biomedical Ontologies (ICBO)</em>, July 28-30, 2011, Buffalo, NY, USA. Pages 279-281. URL: <a href="http://ceur-ws.org/Vol-833/paper48.pdf">http://ceur-ws.org/Vol-833/paper48.pdf</a>. </li>
</ul>
<p><strong>OBO Foundry documents: </strong></p>
<ul>
  <li> The ontologies.txt: <a href="http://obo.cvs.sourceforge.net/viewvc/obo/obo/website/cgi-bin/ontologies.txt">http://obo.cvs.sourceforge.net/viewvc/obo/obo/website/cgi-bin/ontologies.txt</a></li>
  <li>Configuration of the OBO PURL domain: <a href="https://code.google.com/p/obo-foundry-operations-committee/wiki/OBOPURLDomain">https://code.google.com/p/obo-foundry-operations-committee/wiki/OBOPURLDomain</a> </li>
  <li>HOWTO request a prefix and domain for a new resource: <a href="https://code.google.com/p/obo-foundry-operations-committee/wiki/Policy_for_OBO_namespace_and_associated_PURL_requests">https://code.google.com/p/obo-foundry-operations-committee/wiki/Policy_for_OBO_namespace_and_associated_PURL_requests</a> </li>
  <li>Setting up Protege to work with OBO ontologies: <a href="https://code.google.com/p/obo-foundry-operations-committee/wiki/SettingUpProtege">https://code.google.com/p/obo-foundry-operations-committee/wiki/SettingUpProtege</a> </li>
  <li>OBO Foundry Operations Committee: <a href="http://code.google.com/p/obo-foundry-operations-committee/">http://code.google.com/p/obo-foundry-operations-committee/</a></li>
</ul>
<p><strong>W3C web  references:</strong></p>
<ul>
  <li>W3C OWL 2: <a href="http://www.w3.org/TR/owl2-overview/">http://www.w3.org/TR/owl2-overview/</a></li>
  <li>W3C RDF: <a href="http://www.w3.org/RDF/">http://www.w3.org/RDF/</a></li>
  <li>W3C SPARQL: <a href="http://www.w3.org/TR/rdf-sparql-query/">http://www.w3.org/TR/rdf-sparql-query/</a></li>
</ul>
<p><strong>Biomedical Ontologies: </strong></p>
<ul>
  <li> Ontologies in OBO Foundry: <a href="http://www.obofoundry.org">http://www.obofoundry.org</a></li>
  <li>OBI: Ontology for Biomedical Investigations: <a href="http://obi-ontology.org/">http://obi-ontology.org</a></li>
  <li>VO: Vaccine Ontology: <a href="http://www.violinet.org/vaccineontology">http://www.violinet.org/vaccineontology</a></li>
  <li>Ontologies supported by Ontobee: <a href="<?php echo SITEURL_INDEX; ?>">http://www.ontobee.org/index.php</a>. </li>
</ul>
<p><strong>Related Tools: </strong></p>
<ul>
  <li> Protege ontology editor: <a href="http://protege.stanford.edu/">http://protege.stanford.edu/</a></li>
  <li>NCBO BioPortal: <a href="http://bioportal.bioontology.org/">http://bioportal.bioontology.org/</a></li>
  <li>Linked Data Browsers: <a href="http://en.wikipedia.org/wiki/Linked_Data#Browsers">http://en.wikipedia.org/wiki/Linked_Data#Browsers </a></li>
</ul>
<p><strong>Other related paper references:</strong></p>
<ul>
  <li> 
    Courtot M, Bug W, Gibson F,  Lister AL, Malone J, Schober D, Brinkman RR, Ruttenberg A. <a href="http://www.webont.org/owled/2008/papers/owled2008eu_submission_38.pdf">The OWL of Biomedical Investigations</a>. OWLED 2008.</li>
  <li>Ruttenberg A, Rees JA, Samwald M, Marshall MS. <a href="http://www.ncbi.nlm.nih.gov/pubmed/19282504">Life sciences on the Semantic Web: the Neurocommons and beyond</a>. <em>Brief Bioinform</em>. 2009 Mar;10(2):193-204. PMID: 19282504. </li>
  <li>Smith B, Ashburner M, Rosse C, Bard J, Bug W, Ceusters W, Goldberg LJ,  Eilbeck K, Ireland A, Mungall CJ; OBI Consortium, Leontis N,  Rocca-Serra P, Ruttenberg A, Sansone SA, Scheuermann RH, Shah N,  Whetzel PL, Lewis S. (2007). <a href="http://www.ncbi.nlm.nih.gov/entrez/query.fcgi?cmd=Retrieve&amp;db=pubmed&amp;dopt=Abstract&amp;list_uids=17989687">The OBO Foundry: coordinated evolution of  ontologies to support biomedical data integration</a>. <em>Nat Biotechnol</em> 25 (11): 1251-5. PMID 17989687.</li>
</ul>
<br/>
<p><strong>Note</strong>: Please let us know if you want to recommend  some other related information in this web page. Thanks. </p>

<?php require TEMPLATE . 'footer.default.dwt.php'; ?>