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

<script src="<?php echo SITEURL; ?>public/js/inputpicker/jquery.inputpicker.js"></script>
<link href="<?php echo SITEURL; ?>public/css/inputpicker/jquery.inputpicker.css" rel="stylesheet" type="text/css"/>

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
					<textarea name="batchkeywords" cols="70" rows="10" id="batchkeywords" style="margin-left:32px">
<?php 
	if ( !empty( $keywords ) ) {
		echo implode( PHP_EOL, $keywords );
	}
?>
</textarea><br/><br/>
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
		
		 <span style="margin-left:32px;"><strong>Please select ontologies (optional):</strong></span><br/>
	

	
		<div style="margin-left:32px">
		<input class="form-control" id="ontology" name="ontology" style="width:50%;height:32px;" value="<?php echo implode(",",$batchontologies);?>"/>
		<script>
			$('#ontology').inputpicker({
			    data:[
<?php
foreach ( $ontologies as $ontology ) {
	echo
<<<END
			{value:"{$ontology->ontology_abbrv}", fullname: "{$ontology->ontology_fullname}"},
END;
}
?>
			    ],
			    fields:['value','fullname'],
			    fieldText : 'fullname',
			    multiple: true,
			    filterOpen: true,
			    autoOpen: true,
			});
		</script>
		</div>
		
		<p style="text-align:center;">
			<input type="submit" name="submit" id="submit" value="Search" text-align="center" onClick="submitForm();"/>
			
			<button type="button" name="reset" value="Reset" style="margin-left:40px;" text-align="center" onClick="document.getElementById('batchkeywords').value='';document.getElementById('ontology').value=''">Reset</button>
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
			Matched Term IRI
 		</td>
 		
 		<td>
 			Source Ontology in Ontobee
 		</td>
 		
 		<td>
 			Also in Ontobee
 		</td>
	</tr>
END;
	
	foreach( $jsons as $keyword => $json ) {
		
		$validResult = array();
		foreach ( $json as $index => $match ) {
			if ( !$match['deprecate'] ) {
				if ( !array_key_exists( $match['iri'], $validResult ) ) {
					$validResult[$match['iri']] = array();
				}
				$validResult[$match['iri']][] = $match['ontology'];
			}
		}
		ksort( $validResult );
		$rowspan = sizeof( $validResult );
		
		$labelFlag = false;
		foreach ( $validResult as $resultIRI => $availableOntologies ) {
			$termIRI = Helper::encodeURL( $resultIRI );
			$prefix = Helper::getIRIPrefix( $resultIRI );
			
			echo
<<<END
	<tr>
END;
			if ( !$labelFlag ) {
				echo
<<<END
		<td rowspan="$rowspan">
			$keyword
		</td>
END;
				$labelFlag = true;
			}
			
			echo
<<<END
 		<td>
 			<a class="term" href="$termIRI">$termIRI</a>
 		</td>
		<td>
			<a class="term" href="{$site}ontology/{$prefix}?iri={$termIRI}">$prefix</a>
 		</td>
		<td>
END;

			$availableOntologies = array_unique( $availableOntologies );
			sort( $availableOntologies );
			if ( ( $index = array_search( $prefix, $availableOntologies ) ) !== false ) {
				/* In case purl link is not redirecting back to ontobee
				 * We still need to display the ontobee link
				 */
				$tmpToken = $availableOntologies[$index];
				unset( $availableOntologies[$index] );
			}
			
			foreach ( $availableOntologies as $index => $availableOntology ) {
				echo
<<<END
 			<a class="term" href="{$site}ontology/$availableOntology?iri=$termIRI">$availableOntology</a>, 
END;
			}
			echo
<<<END
 		</td>
	</tr>
END;
		}
	}
	
	echo
<<<END
</table>
END;
}

?>

<?php require TEMPLATE . 'footer.default.dwt.php'; ?>