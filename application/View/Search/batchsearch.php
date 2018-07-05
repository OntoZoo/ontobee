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
 * @file batchsearch.php
 * @author Edison Ong
 * @since Jul 5, 2018
 * @comment 
 */
 

if ( !$this ) {
	exit( header( 'HTTP/1.0 403 Forbidden' ) );
}

$site = SITEURL;

?>

<?php require TEMPLATE . 'header.default.dwt.php'; ?>

<h3 class="head3_darkred">Ontobee Batch Search</h3>

<div>
	<form enctype="multipart/form-data" name="batchsearch" id="batchsearch" action="<?php echo SITEURL; ?>search/batchsearch" method="get">
		
		<table border="0">
		
			<tr>
				<td>
					<span style="margin-left:32px;"><strong>Paste keywords (One per line):</strong></span><br/>
				</td>
			</tr>
			
			<tr>
				<td>
					<textarea name="keywords" cols="70" rows="10" id="term_iris" style="margin-left:32px"></textarea><br/><br/>
				</td>
			</tr>
			
<!-- 			
			<tr>
				<td>
					<span style="margin-left:32px;"><strong>Upload text file:</strong></span>
					<input type="file" name="file" id="file" size="50" style="margin-left:32px;" /><br/>
				</td>
			</tr>
 -->
			
		</table>
		
		<p style="text-align:center;">
			<input type="submit" name="submit" id="submit" value="Search" text-align="center" onClick="submitForm();"/>
			
			<input type="reset" name="reset" value="Reset" style="margin-left:40px;" text-align="center" onClick="resetForm();"/>
		</p>
		
	</form>
</div>


<?php 

if ( $submit && !empty( $jsons ) ) {
	echo
<<<END
<h3 class="head3_darkred">Batch Results</h3>
<table border="1" width="100%" cellpadding="5">
	<tr style='text-align:center;font-weight:bold'>
		<td>
			Search Term
		</td>
		<td>
			Matched Term
 		</td>
 		
 		<td>
 			Source Ontology
 		</td>
 		
 		<td>
 			Term IRI
 		</td>
	</tr>
END;
	
	foreach( $jsons as $keyword => $json ) {
		foreach( $json as $index => $match ) {
			$rowspan = sizeof( $json );
			if ( $index == 0 ) {
				echo
<<<END
	<tr>
		<td rowspan="$rowspan">
			$keyword
		</td>
		<td>
			{$match['label']}
 		</td>
 		
 		<td>
 			{$match['ontology']}
 		</td>
 		
 		<td>
 			{$match['iri']}
 		</td>
	</tr>
END;
			} else {
				echo
<<<END
	<tr>
		<td>
			{$match['label']}
 		</td>
 		
 		<td>
 			{$match['ontology']}
 		</td>
 		
 		<td>
 			{$match['iri']}
 		</td>
	</tr>
END;
			}
		}
	}
	
	echo
<<<END
</table>
END;
}

?>

<?php require TEMPLATE . 'footer.default.dwt.php'; ?>