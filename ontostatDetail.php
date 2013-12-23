<!-- 
Copyright © 2013 The Regents of the University of Michigan
 
Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at
 
http://www.apache.org/licenses/LICENSE-2.0
 
Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.
 
For more information, questions, or permission requests, please contact:
Yongqun “Oliver” He - yongqunh@med.umich.edu
Unit for Laboratory Animal Medicine, Center for Computational Medicine & Bioinformatics
University of Michigan, Ann Arbor, MI 48109, USA
He Group:  http://www.hegroup.org
<!--
Author: Bin Zhao
The University Of Michigan
He Group
Date: June 2008 - December 2013
Purpose: Ontobee ontostat section statistic detail page.
-->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html><!-- InstanceBegin template="/Templates/default.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<!-- InstanceBeginEditable name="doctitle" -->
<title>Ontobee</title>
<!-- InstanceEndEditable --><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="shortcut icon" href="/favicon.ico" />
<link href="css/styleMain.css" rel="stylesheet" type="text/css">
<!-- InstanceBeginEditable name="head" --><!-- InstanceEndEditable -->
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-4869243-9']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</head>

<body>
<div id="topbanner"><a href="/index.php" style="font-size:36px; color:#111144; text-decoration:none"><img src="images/logo.gif" alt="Logo" width="280" height="49" border="0"></a></div>
<div id="topnav"><a href="index.php" class="topnav">Home</a><a href="introduction.php" class="topnav">Introduction</a><a href="ontostat/index.php" class="topnav">Statistics</a><a href="sparql/index.php" class="topnav">SPARQL</a><a href="ontobeep/index.php" class="topnav">Ontobeep</a><a href="tutorial/index.php" class="topnav">Tutorial</a><a href="faqs.php" class="topnav">FAQs</a><a href="references.php" class="topnav">References</a><a href="links.php" class="topnav">Links</a><a href="contactus.php" class="topnav">Contact</a><a href="acknowledge.php" class="topnav">Acknowledge</a><a href="news.php" class="topnav">News</a></div>
<div id="mainbody">
<!-- InstanceBeginEditable name="Main" -->
<h3 class="head3_darkred">Ontology Statistics</h3>

<p> Here is the statitics for this ontology: e.g., UBERON</p>
<?php
include_once('inc/Classes.php');
$o = 'VO';
$settings = getSettings($o);

$prefix = $_GET['prefix'];
$type = $_GET['type'];

$ontologyName = "VO";

$domain = "http://purl.obolibrary.org/merged/" . $ontologyName;


if ($prefix != "total" && $prefix != "NoPrefix" && $type != "totalCount") {
$queryString = "
SELECT distinct ?s as ?class ?l as ?label
FROM <{$domain}>
WHERE
{
?s a owl:{$type} .

OPTIONAL
{
?s rdfs:label ?l.
}

FILTER regex (?s, \"{$prefix}\") .
FILTER (isIRI(?s)).
}
";

$queryResult = json_query($queryString);

?>
</br>
<h3>Statistic detail of prefix: <strong><?php echo $prefix;  ?></strong></h3>



<?php

if( sizeof( $queryResult ) == 0 ) {

?>

<div align="center"><p>IRI with prefix: <?php echo $prefix; ?> in <?php echo $type; ?> not found.</p></div>

<?php
}
else {

?>

<table align="center" width="800">

<tr height="25" align="center">
	<td  bgcolor="#AAAAAA" ><strong>IRI</strong></td>
	<td  bgcolor="#AAAAAA"><strong>label</strong></td>
</tr>

<?php

$index = 0;

foreach( $queryResult as $resultItem ){

?>

<tr height="25" <?php if ( $index % 2 == 1){ echo "bgcolor = \"#CCECFB\""; } ?> >
	<td width="320">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=" <?php echo $resultItem['class']?> "/> <?php echo $resultItem['class'] ?>   </td>
	<td  >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if ( sizeof ($resultItem) == 2) {echo $resultItem['label'];} else { echo " "; }  ?></td>
</tr>



<?php

$index ++;

}


?>






</table>



<?php


}



?>

</br>

<p>Total #: <strong><?php echo $index; ?></strong></p>

<?php
} elseif ($prefix != "total" && $prefix != "NoPrefix" && $type == "totalCount") {

$classQueryString = "
SELECT distinct ?s as ?class ?l as ?label
FROM <{$domain}>
WHERE
{
?s a owl:Class .

OPTIONAL
{
?s rdfs:label ?l.
}

FILTER regex (?s, \"{$prefix}\") .
FILTER (isIRI(?s)).
}
";

$datatypeQueryString = "
SELECT distinct ?s as ?datatype ?l as ?label
FROM <{$domain}>
WHERE
{
?s a owl:DatatypeProperty .

OPTIONAL
{
?s rdfs:label ?l.
}

FILTER regex (?s, \"{$prefix}\") .
FILTER (isIRI(?s)).
}
";

$objectQueryString = "
SELECT distinct ?s as ?object ?l as ?label
FROM <{$domain}>
WHERE
{
?s a owl:ObjectProperty .

OPTIONAL
{
?s rdfs:label ?l.
}

FILTER regex (?s, \"{$prefix}\") .
FILTER (isIRI(?s)).
}
";

$annotationQueryString = "
SELECT distinct ?s as ?annotation ?l as ?label
FROM <{$domain}>
WHERE
{
?s a owl:AnnotationProperty .

OPTIONAL
{
?s rdfs:label ?l.
}

FILTER regex (?s, \"{$prefix}\") .
FILTER (isIRI(?s)).
}
";

$classQueryResult = json_query($classQueryString);
$objectQueryResult = json_query($objectQueryString);
$annotationQueryResult = json_query($annotationQueryString);
$datatypeQueryResult = json_query($datatypeQueryString);

$index = 0;
?>
</br>
<h3>Statistic detail of prefix: <strong><?php echo $prefix;  ?></strong></h3>
<table align="center" width="800">

<tr height="25" align="center">
	<td  bgcolor="#AAAAAA" ><strong>IRI</strong></td>
	<td  bgcolor="#AAAAAA"><strong>label</strong></td>
</tr>
<?php
if (sizeof($classQueryResult != 0)) {
	foreach ($classQueryResult as $result) {
	?>
	<tr height="25" <?php if ( $index % 2 == 1){ echo "bgcolor = \"#CCECFB\""; } ?>>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=" <?php echo $result['class']?> "/> <?php echo $result['class'] ?>   </td>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if ( sizeof ($result) == 2) {echo $result['label'];} else { echo " "; }  ?></td>
	</tr>
	
	<?php
	
	$index ++;
	
	}


} 

if (sizeof($objectQueryResult != 0)) {
	foreach ($objectQueryResult as $result) {
	?>
	<tr height="25" <?php if ( $index % 2 == 1){ echo "bgcolor = \"#CCECFB\""; } ?>>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=" <?php echo $result['object']?> "/> <?php echo $result['object'] ?>   </td>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if ( sizeof ($result) == 2) {echo $result['label'];} else { echo " "; }  ?></td>
	</tr>
	
	<?php
	
	$index ++;
	
	}


} 

if (sizeof($annotationQueryResult != 0)) {
	foreach ($annotationQueryResult as $result) {
	?>
	<tr height="25" <?php if ( $index % 2 == 1){ echo "bgcolor = \"#CCECFB\""; } ?>>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=" <?php echo $result['annotation']?> "/> <?php echo $result['annotation'] ?>   </td>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if ( sizeof ($result) == 2) {echo $result['label'];} else { echo " "; }  ?></td>
	</tr>
	
	<?php
	
	$index ++;
	
	}


} 

if (sizeof($datatypeQueryResult != 0)) {
	foreach ($datatypeQueryResult as $result) {
	?>
	<tr height="25" <?php if ( $index % 2 == 1){ echo "bgcolor = \"#CCECFB\""; } ?>>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=" <?php echo $result['datatype']?> "/> <?php echo $result['datatype'] ?>   </td>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if ( sizeof ($result) == 2) {echo $result['label'];} else { echo " "; }  ?></td>
	</tr>
	
	<?php
	
	$index ++;
	
	}


} 

?>
</table>

</br>

<p>Total #: <strong><?php echo $index; ?></strong></p>
<?php
} elseif ($prefix == "total" && $type != "totalCount") {

$queryString = "
SELECT distinct ?s as ?class ?l as ?label
FROM <{$domain}>
WHERE
{
?s a owl:{$type} .

OPTIONAL
{
?s rdfs:label ?l.
}

FILTER (isIRI(?s)).

}
";

$queryResult = json_query($queryString);

$index = 0;

?>
</br>
</br>
<table align="center" width="800">

<tr height="25" align="center">
	<td  bgcolor="#AAAAAA" ><strong>IRI</strong></td>
	<td  bgcolor="#AAAAAA"><strong>label</strong></td>
</tr>
<?php

foreach ($queryResult as $result) {
	?>
	<tr height="25" <?php if ( $index % 2 == 1){ echo "bgcolor = \"#CCECFB\""; } ?>>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=" <?php echo $result['class']?> "/> <?php echo $result['class'] ?>   </td>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if ( sizeof ($result) == 2) {echo $result['label'];} else { echo " "; }  ?></td>
	</tr>
	<?php
	$index ++;
}

?>
</table>

</br>

<p>Total #: <strong><?php echo $index; ?></strong></p>
<?php



} elseif ($prefix == "total" && $type == "totalCount") {

$classQueryString = "
SELECT ?s as ?class ?l as ?label
FROM <{$domain}>
WHERE
{
?s a owl:Class .

OPTIONAL
{
?s rdfs:label ?l.
}

FILTER (isIRI(?s)).

}
";

$objectQueryString = "
SELECT ?s as ?object ?l as ?label
FROM <{$domain}>
WHERE
{
?s a owl:ObjectProperty .

OPTIONAL
{
?s rdfs:label ?l.
}

FILTER (isIRI(?s)).

}
";

$datatypeQueryString = "
SELECT ?s as ?datatype ?l as ?label
FROM <{$domain}>
WHERE
{
?s a owl:DatatypeProperty .

OPTIONAL
{
?s rdfs:label ?l.
}

FILTER (isIRI(?s)).

}
";

$annotationQueryString = "
SELECT ?s as ?annotation ?l as ?label
FROM <{$domain}>
WHERE
{
?s a owl:AnnotationProperty .

OPTIONAL
{
?s rdfs:label ?l.
}
FILTER (isIRI(?s)).

}
";

$classQueryResult = json_query($classQueryString);
$objectQueryResult = json_query($objectQueryString);
$datatypeQueryResult = json_query($datatypeQueryString);
$annotationQueryResult = json_query($annotationQueryString);

$index = 0;

?>
</br>
</br>
<table align="center" width="800">

<tr height="25" align="center">
	<td  bgcolor="#AAAAAA" ><strong>IRI</strong></td>
	<td  bgcolor="#AAAAAA"><strong>label</strong></td>
</tr>
<?php
	
	if (sizeof($classQueryResult) != 0) {
		foreach ($classQueryResult as $result) {
			?>
	<tr height="25" <?php if ( $index % 2 == 1){ echo "bgcolor = \"#CCECFB\""; } ?>>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=" <?php echo $result['class']?> "/> <?php echo $result['class'] ?>   </td>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if ( sizeof ($result) == 2) {echo $result['label'];} else { echo " "; }  ?></td>
	</tr>
			<?php
			
			$index ++;
		}


	}
	if (sizeof($objectQueryResult) != 0) {
		foreach ($objectQueryResult as $result) {
			?>
	<tr height="25" <?php if ( $index % 2 == 1){ echo "bgcolor = \"#CCECFB\""; } ?>>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=" <?php echo $result['object']?> "/> <?php echo $result['object'] ?>   </td>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if ( sizeof ($result) == 2) {echo $result['label'];} else { echo " "; }  ?></td>
	</tr>

			<?php
			
			$index ++;
			
		}
	}

	if (sizeof($datatypeQueryResult) != 0) {
		foreach ($datatypeQueryResult as $result) {
			?>
	<tr height="25" <?php if ( $index % 2 == 1){ echo "bgcolor = \"#CCECFB\""; } ?>>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=" <?php echo $result['datatype']?> "/> <?php echo $result['datatype'] ?>   </td>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if ( sizeof ($result) == 2) {echo $result['label'];} else { echo " "; }  ?></td>
	</tr>

			<?php
			
			$index ++;
			
		}
	}

	if (sizeof($annotationQueryResult) != 0) {
		foreach ($annotationQueryResult as $result) {
			?>
	<tr height="25" <?php if ( $index % 2 == 1){ echo "bgcolor = \"#CCECFB\""; } ?>>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=" <?php echo $result['annotation']?> "/> <?php echo $result['annotation'] ?>   </td>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if ( sizeof ($result) == 2) {echo $result['label'];} else { echo " "; }  ?></td>
	</tr>

			<?php
			
			$index ++;
			
		}
	}


?>
</table>


</br>

<p>Total #: <strong><?php echo $index; ?></strong></p>
<?php





}
?>



<p>&nbsp;</p>
<p>&nbsp;</p>
<p>This is the bottome of the page.</p>




<!-- InstanceEndEditable -->
</div>
<div id="footer">
  <div id="footer_hl"></div>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><div id="footer_left"><a href="http://www.hegroup.org" target="_blank">He Group</a><br>
University of Michigan Medical School<br>
Ann Arbor, MI 48109</div></td>
		<td width="300"><div id="footer_right"><a href="http://www.umich.edu" target="_blank"><img src="images/wordmark_m_web.jpg" alt="UM Logo" width="166" height="20" border="0"></a></div></td>
	</tr>
</table>
</div>
</body>
<!-- InstanceEnd --></html>
