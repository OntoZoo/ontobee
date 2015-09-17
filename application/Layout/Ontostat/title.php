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
 * @file title.php
 * @author Edison Ong
 * @since Sep 8, 2015
 * @comment 
 */
 
if ( !$this ) {
	exit(header('HTTP/1.0 403 Forbidden'));
}

$site = SITEURL;

if ( isset( $ontologies ) ) {
	echo
<<<END
<h3 class="head3_darkred">Ontobeest:  Extraction and Display of Ontology Statistics </h3>
<p>Ontobeest is an  Ontobee-based statistics extraction and display tool. Ontobeest extracts statistical information from one or more ontologies from Ontobee and displays the results using a simple but comprehensive table format in the web site. The Ontobeest cover page provides the statistics of all the ontologies listed in Ontobee. Once you click on any specific ontology, you can obtain detailed information about the statistics on the specific ontology. In addition, you can access the same statistics page for an  ontology by clicking &quot;Detailed Statistics&quot; from the cover page of the specific ontology in Ontobee. See the tutorial of Ontobeest in <a href="../tutorial/index.php#ontostat">HERE</a>. </p>
<p>Click Ontology Prefix to see detail statistics.</p>
END;
} else {
	echo 
<<<END
<h3 class="head3_darkred">Statistics of <a href="{$site}ontology/$ontology->ontology_abbrv">$ontology->ontology_fullname</a></h3>
<p><strong>Ontology:</strong> $ontology->ontology_abbrv</p>
END;
}

?>

