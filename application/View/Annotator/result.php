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
 * @file result.php
 * @author Edison Ong
 * @since Jul 9, 2018
 * @comment 
 */
 
if ( !$this ) {
	exit( header( 'HTTP/1.0 403 Forbidden' ) );
}

$site = SITEURL;

?>

<?php require TEMPLATE . 'header.default.dwt.php'; ?>

<script src="<?php echo SITEURL; ?>public/js/mark/jquery.mark.min.js"></script>
<script src="<?php echo SITEURL; ?>public/js/annotator.display.js"></script>
<link href="<?php echo SITEURL; ?>public/css/annotator.css" rel="stylesheet" type="text/css"/>

<h3 class="head3_darkred">Ontobee Annotator</h3>

<div>
	<span><strong>Query text:</strong></span><br/>
	<p class="querytext" name="querytext" id="querytext"><?php echo implode( "<br>", $texts );?></p>
<?php

	$html = "";
if ( !empty( $results ) ) {
	$html .=
<<<END
	
	<h3 class="head3_darkred">Results</h3>
	<div class="result_table">
	<table border="1" width="100%" cellpadding="5">
	
		<tr style='text-align:center;font-weight:bold'>
			<td>
				Matched Term IRI
			</td>
			<td>
				Matched Term
	 		</td>
 			<td>
 				Source Ontology
 			</td>
	 		<td>
	 			Also available in Ontology
	 		</td>
 			<td style="border-style:hidden">
 			</td>
		</tr>
END;
	
	$idCount = 0;
	foreach( $results as $resultIRI => $result ) {
		$termIRI = Helper::encodeURL( $resultIRI );
		$prefix = Helper::getIRIPrefix( $resultIRI );
		
		$rowspan = sizeof( $result );
		$firstLineFlag = false;
		
		foreach( $result as $label => $matches ) {
			$html .=
<<<END
		<tr class="highlight">
END;
			
			if ( !$firstLineFlag ) {
				$html .=
<<<END
			<td rowspan="$rowspan">
				<a href="$termIRI">$termIRI</a>
			</td>
END;
				
			}
			
			$html .=
<<<END
	 		<td>
	 			<a class="term" id="$idCount">$label</a>
	 		</td>
 			<td>
 				<a href="{$site}ontology/$prefix?iri={$termIRI}">$prefix</a>
 			</td>
			<td>
END;
 			$idCount ++;
			
			$ontologies = array();
			foreach( $matches as $match ) {
				$ontologies[] = $match['ontology'];
			}
			$ontologies = array_unique( $ontologies );
			sort( $ontologies);
			if ( ( $index = array_search( $prefix, $ontologies ) ) !== false ) {
				/* In case purl link is not redirecting back to ontobee
				 * We still need to display the ontobee link
				 */
				$tmpToken = $ontologies[$index];
				unset( $ontologies[$index] );
			}
			
			$anchor = "";
			foreach( $ontologies as $ontology ) {
				$anchor .=
<<<END
				<a href="{$site}ontology/$ontology?iri={$termIRI}">$ontology</a>, 
END;
			}
			$anchor = substr( $anchor, 0, -2 );
			if ( $anchor ) $html .= $anchor;
			
			if ( !$firstLineFlag ) {
				$html .=
<<<END
	 		</td>
			<td style="border-style:hidden" rowspan="$rowspan">
 				<img class="del" src="https://png.icons8.com/metro/1600/delete-sign.png" alt="del" width="10" border="1">
 			</td>
		</tr>
END;
			$firstLineFlag = true;
			} else {
				$html .=
<<<END
	 		</td>
		</tr>
END;
			}
		}
	}
	
	$html .=
<<<END
	</table>
	</div>
	</br>

	<button class="exportHTML">Export Result</button>	

END;
} else {
	$html .=
<<<END
	<h3 class="head3_darkred">Unable to find any ontology terms.</h3>
END;
}
	
?>

<?php echo Helper::tidyHTML( $html );?>

</div>

<?php require TEMPLATE . 'footer.default.dwt.php'; ?>