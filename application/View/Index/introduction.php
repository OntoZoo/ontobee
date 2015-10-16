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
 * @file introduction.php
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

<p> Ontology development is a rapidly growing area of research, especially in the life sciences domain. Biomedical ontologies are consensus-based controlled biomedical vocabularies of terms and relations with associated definitions, which are logically formulated to promote automated reasoning. The Semantic Web is a web of data that allows machines to understand the meaning &ndash; or &quot;semantics&quot; &ndash; of information on the World Wide Web. To ensure that computers can understand the semantics of terms, machine-readable ontologies play a fundamental role in Semantic Web development. However, how to present the meaning of ontology terms by its URI is still a challenge. Most ontology URIs did not point to real web pages. While some URIs point to specific pages, the pages shown were often in pure HTML format or in OWL (RDF/XML) format which contains the whole ontology instead of individual terms. In both cases these pages do not efficiently support the Semantic Web.</p>
<p>The objective of the Linking Open Data (LOD)  community is to extend the Web with a data commons by publishing various open  datasets as RDF on the Web. These RDF links between data items can come from  different data sources and accessed anywhere through the web. All of the  sources on these LOD diagrams are open data. To support LOD, one basic  requirement is to map individual ontology terms to real RDF files through the  Web. Many LOD browsers are available, for example, Ontology-browser (<a href="http://code.google.com/p/ontology-browser/">http://code.google.com/p/ontology-browser/</a>),  VisiNav (<a href="http://visinav.deri.org/">http://visinav.deri.org/</a>), and  more on the web page: <a href="http://en.wikipedia.org/wiki/Linked_Data#Browsers">http://en.wikipedia.org/wiki/Linked_Data#Browsers</a>.  However, these programs focus on RDF semantic data structure browsing without returning  fragmented ontology term information in RDF format. </p>
<p>Ontobee is a web-based linked data server and browser specifically designed for ontology terms. Ontobee supports ontology visualization, query, and development. Ontobee provides a user-friendly web interface for displaying the details and its hierarchy of a specific ontology term. Meanwhile, Ontobee provides a RDF source code for the particular web page, which supports remote query of the ontology term and the Semantic Web. Ontobee provides an efficient and publicly available method to promote ontology sharing, interoperability, and data integration.</p>
<p>Ontobee is the default linked ontology data server for  OBO Foundry library ontologies. </p>
<p>Ontobeep is an Ontobee-based program for ontology alignment,  comparison, and result visualization. Ontobeep aligns,   compares, and displays the similarities and differences among selected   ontologies available in Ontobee. Ontobeep also provides a Statistics   page to summarize the findings out of the ontology alignment and   comparison. </p>
<p>&nbsp;</p>

<?php require TEMPLATE . 'footer.default.dwt.php'; ?>