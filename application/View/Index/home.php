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
 * @author Yongqun Oliver He
 * @author Zuoshuang Allen Xiang
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
<div id="ontologyTable">
<table id="ontologyList" class="tablesorter" border="0" cellpadding="2" style="">
<thead>
<tr>
<th width="3%"><strong>No.</strong></th>
<th width="17%"><strong>Ontology Prefix</strong></th>
<th width="40%"><strong>Ontology Full Name</strong></th>
<th width="8%"><string>OBO</th>
<th width="20%"><string>Domain</th>
<th width="12%"><strong>List of Terms</strong></th>
</tr>
</thead>

<tbody>
<?php
$index = 0;
foreach ( $ontologies as $key => $ontology ) {
	$index += 1;
	if ( $index % 2 == 0 ) {
		$bgcolor = 'even';
	} else {
		$bgcolor = 'odd';
	}
	$site = SITEURL;
	if ( isset( $ontology->foundry ) && !is_null( $ontology->foundry ) && !empty( $ontology->foundry ) ) {
		$foundry = $ontology->foundry;
	} else {
		$foundry = 'No';
	}
	if ( isset( $ontology->domain ) && !is_null( $ontology->domain ) && !empty( $ontology->domain ) ) {
		$domain = $ontology->domain;
	} else {
		$domain = '-';
	}
	echo
<<<END
<tr class="$bgcolor">
<td align="center"><strong>$index</strong></td>
<td><a href="{$site}ontology/$ontology->ontology_abbrv">$ontology->ontology_abbrv</a></td>
<td>$ontology->ontology_fullname
END;
	/*
	if ( isset( $ontology->license ) && !is_null( $ontology->license ) && !empty( $ontology->license ) ) {
		$license = preg_split( '/[|]/', $ontology->license );
		echo 
<<<END
<a href="$license[2]"><img height="15px" src="$license[1]" alt="$license[0]"></a>
END;
	}
	*/
	echo
<<<END
</td>
<td>$foundry</td>
<td>$domain</td>
<td align="center">
<a href="{$site}listTerms/$ontology->ontology_abbrv?format=xls"><img src="{$site}public/images/Excel_xls_Logo.png" alt="Excel XLS format" width="24" height="24" border="0"></a>
<a href="{$site}listTerms/$ontology->ontology_abbrv?format=xlsx"><img src="{$site}public/images/Excel_xlsx_Logo.png" alt="Excel XLSX format" width="24" height="24" border="0"></a>
</td>
</tr>
END;
}
?>
</tbody>
</table>
</div>

<script type="text/javascript">
$(document).ready(function() 
	    { 
	        $("#ontologyList").tablesorter({
	            headers: {
	                0: {
	                    sorter: false
	                },
	                5: {
	                    sorter: false
	            	}
	            }
	    	});
	    } 
	);
//Auto-reorder number
$("#ontologyList").bind("sortStart",function() {
	var clone = $("#ontologyList").clone(true);
	clone[0].setAttribute("id", "ontologyListOverlay");
	$("#ontologyList").hide();
	$("#ontologyTable").append(clone[0]);
}).bind("sortEnd",function() { 
    var i = 0;
    $("#ontologyList").find("tr:gt(0)").each(function(){
        i++;
        $(this).find("td:eq(0)").html("<strong>" + i + "<strong>");
        if ( i % 2 == 0 ) {
        	$(this).removeClass("odd even").addClass("even");
        } else {
        	$(this).removeClass("odd even").addClass("odd");
        }
    });
    $("#ontologyListOverlay").remove();
	$("#ontologyList").show();
});
</script>

<p><strong>Please cite the following reference for Ontobee: </strong></p>
<p>Xiang Z, Mungall C, Ruttenberg A, He Y. <a href="doc/Ontobee_ICBO-2011_Proceeding.pdf">Ontobee: A Linked Data Server and Browser for Ontology Terms</a>. <em>Proceedings of the 2nd International Conference on Biomedical Ontologies (ICBO)</em>, July 28-30, 2011, Buffalo, NY, USA. Pages 279-281. URL: <a href="http://ceur-ws.org/Vol-833/paper48.pdf">http://ceur-ws.org/Vol-833/paper48.pdf</a>. </p>
<p>&nbsp;</p>

<?php require TEMPLATE . 'footer.default.dwt.php'; ?>
