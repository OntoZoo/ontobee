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
 * @file home.php
 * @author Edison Ong
 * @since Sep 4, 2015
 * @comment 
 */
 
if (!$this) {
	exit(header('HTTP/1.0 403 Forbidden'));
}

?>

<?php require TEMPLATE . 'header.default.dwt.php'; ?>

<h3 class="head3_darkred">Welcome to Ontobee!</h3>

<p><strong>Ontobee: </strong>A <a href="http://www.w3.org/DesignIssues/LinkedData.html">linked data</a> server designed for ontologies. Ontobee is aimed to  facilitate ontology data sharing, visualization, query, integration, and analysis. Ontobee dynamically <a href="http://www.w3.org/2001/tag/doc/httpRange-14/2007-05-31/HttpRange-14">dereferences</a> and presents individual ontology term URIs to (i) <em>HTML web pages</em> for user-friendly web browsing and navigation, and to  (ii) <em>RDF source code </em>for <a href="http://en.wikipedia.org/wiki/Semantic_Web">Semantic Web</a> applications. Ontobee is the default linked data server for most <a href="http://obofoundry.org/">OBO Foundry  library ontologies</a>. Ontobee has also been used for many non-OBO ontologies. </p>

<?php require TEMPLATE . 'search.keyword.php'; ?>

<form action="<?php echo SITEURL ?>search/redirect" method="get" id="home-redirect" style="margin-top:16px;">
  <div class="ui-widget">Jump to http://purl.obolibrary.org/obo/
    <input id="redirect-id" name="id" size="20" />
    <input type="submit" name="submit" value="Go" />
</div>
</form>

<p>Currently Ontobee has been applied for the following ontologies: </p>
<table border="0" cellpadding="2" style="border:1px #A0A0A4 solid">
<tr>
<td bgcolor="#158AFF" align="center"><strong>No.</strong></td>
<td bgcolor="#158AFF" align="center"><strong>Ontology Prefix</strong></td>
<td bgcolor="#158AFF" align="center"><strong>Ontology Full Name</strong></td>
<td bgcolor="#158AFF" align="center"><strong>List of Terms</strong></td>
</tr>

<?php
$index = 0;
foreach ( $ontologies as $key => $ontology ) {
	$index += 1;
	if ( $index % 2 == 0 ) {
		$bgcolor = '#BBDDFF';
	} else {
		$bgcolor = '';
	}
	$site = SITEURL;
	echo
<<<END
<tr bgcolor="$bgcolor">
<td align="center"><strong>$index</strong></td>
<td><a href="{$site}ontology/$ontology->ontology_abbrv">$ontology->ontology_abbrv</a></td>
<td>$ontology->ontology_fullname</td>
<td align="center"><a href="{$site}listTerms/$ontology->ontology_abbrv"><img src="{$site}public/images/Excel_2010_Logo.png" alt="Excel format" width="16" height="16" border="0"></a></td>
</tr>
END;
}
?>

</table>

<p><strong>Please cite the following reference for Ontobee: </strong></p>
<p>Xiang Z, Mungall C, Ruttenberg A, He Y. <a href="doc/Ontobee_ICBO-2011_Proceeding.pdf">Ontobee: A Linked Data Server and Browser for Ontology Terms</a>. <em>Proceedings of the 2nd International Conference on Biomedical Ontologies (ICBO)</em>, July 28-30, 2011, Buffalo, NY, USA. Pages 279-281. URL: <a href="http://ceur-ws.org/Vol-833/paper48.pdf">http://ceur-ws.org/Vol-833/paper48.pdf</a>. </p>
<p>&nbsp;</p>

<?php require TEMPLATE . 'footer.default.dwt.php'; ?>
