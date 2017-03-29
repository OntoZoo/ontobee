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
 * @since Mar 28, 2017
 * @comment 
 */
 
if ( !$this ) {
	exit( header( 'HTTP/1.0 403 Forbidden' ) );
}

$site = SITEURL;

?>

<?php require TEMPLATE . 'header.default.dwt.php'; ?>

<?php 


$html = '';

$html .= 
<<<END

<h3><span class="head3_darkred">Ontobeep</span></h3>

<div style="border:thin #808080 solid; padding:4px">
	<p style="font-size:14px">Ontobeep is an ontology alignment and comparison program that aligns, compares, and displays the similarities and differences among selected ontologies available in Ontobee. Ontobeep also provides a Statistics page to summarize the findings out of the ontology alignment and comparison. See more information in <a href="{$site}tutorial/ontobeep">Ontobeep Tutorial</a>. </p>
	<p style="font-size:14px"><strong><em>Please select two to three ontologies:</em></strong></p>
	
	<form action="{$site}ontobeep/compare" method="get">
		<div style="padding:4px">
 			<select name="ontology[]" size="20" multiple id="ontology" onChange="checkOntology1()">
END;

foreach ( $ontologies as $key => $ontology ) {
	$html .=
<<<END
				<option value="$ontology->ontology_abbrv">$ontology->ontology_fullname ($ontology->ontology_abbrv)</option>
END;
}

$html .=
<<<END
			</select>
		</div>

		<div style="padding:4px" align="center"><span style="font-weight:bold">
			<input type="submit"> 
		</div>
	</form>
</div>
END;

echo Helper::tidyHTML( $html );
?>

<?php require TEMPLATE . 'footer.default.dwt.php'; ?>