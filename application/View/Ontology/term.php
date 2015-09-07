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
 * @file term.php
 * @author Edison Ong
 * @since Sep 6, 2015
 * @comment 
 */
 
if ( !$this ) {
	exit(header('HTTP/1.0 403 Forbidden'));
}

$pageURL = SITEURL . "ontology/term/?ontology=$ontAbbr&iri=" . urlencode( $typeIRI ) . "&letter=$letter&page=";

$letterURL = SITEURL . "ontology/term/?ontology=$ontAbbr&iri=" . urlencode( $typeIRI ) . "&letter=";

$termURL = SITEURL . "ontology/?ontology=$ontAbbr&iri=";

?>

<?php require TEMPLATE . 'header.default.dwt.php'; ?>

<?php
echo '<p>' . sizeof( $terms ) . ' terms(s) returned. ';
if ( sizeof( $terms ) >= 10000 ) {
	echo '<span class="darkred">There are still more terms in this ontology.  Please click a term for detail information. </p></span>';
}
?>

<table border="0">
<tr>
<td bgcolor="#F5FAF7" class="tdData" style="padding-left:20px; padding-right:20px ">
<strong>Term Type:</strong> <?php echo array_search( $typeIRI, $GLOBALS['ontology']['type'] ); ?>
</td>
<td bgcolor="#F5FAF7" class="tdData" style="padding-left:20px; padding-right:20px ">

<?php 

echo 
	'<strong>Record:</strong>' . ( ( $page - 1 ) * $GLOBALS['ontology']['term_max_per_page'] + 1 ) . ' to ';
if ( ( $page * $GLOBALS['ontology']['term_max_per_page'] ) < sizeof( $terms ) ) {
	echo $page * $GLOBALS['ontology']['term_max_per_page'];
} else {
	echo sizeof( $terms );
}
echo ' of ' . sizeof( $terms ) . ' Records</td>';
?>

<td bgcolor="#F5FAF7" class="tdData" style="padding-left:20px; padding-right:20px ">

<?php 
echo "<strong>Page:</strong> $page of $pageCount, ";
if ( $page > 1 ) {
	echo "<a href=\"{$pageURL}1\">First</a> ";
	echo "<a href=\"$pageURL" . ( $page - 1 ) . '">Previous</a> ';
} else {
	echo 'First ';
	echo 'Previous ';
}
if ( $page < $pageCount ) {
	echo "<a href=\"$pageURL" . ( $page + 1 ) . '">Next</a> ';
	echo "<a href=\"{$pageURL}$pageCount\">Last</a> ";
} else {
	echo 'Next ';
	echo 'Last ';
}
?>

</td>
</tr>
</table>
<?php
foreach ( array_keys( $letters ) as $l ) {
	if ( $l == $letter ) {
		echo "<span style=\"font-size:14px; font-weight:bold; margin-right:12px;\">$l</span>";
	} else {
		echo "<a href=\"$letterURL$l\" style=\"font-size:14px; font-weight:bold; margin-right:12px;\">$l</a>"; 
	}
}
?>

<ul>

<?php
$termsToShow = array_slice(
	$terms,
	( $page - 1 ) * $GLOBALS['ontology']['term_max_per_page'],
	$GLOBALS['ontology']['term_max_per_page']
);
foreach ( $termsToShow as $termIRI => $termLabel ) {
	echo "<li><a href=\"$termURL" . urlencode( $termIRI ) . '">' . htmlentities( $termLabel ) . '</a></li>';
}
?>

</ul>

<?php ?>

<?php require TEMPLATE . 'footer.default.dwt.php'; ?>