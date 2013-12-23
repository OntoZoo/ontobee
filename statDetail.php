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
-->
<!--
Author: Bin Zhao
The University Of Michigan
He Group
Date: June 2008 - December 2013
Purpose: Ontobee ontology statistic section detail page.
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


<?php
include_once('inc/Classes.php');

$orderColumn = isset($_GET['orderColumn']) ? $_GET['orderColumn'] : "IRI";
$orderBy = isset($_GET['orderBy']) ? $_GET['orderBy'] : "ASC";

$prefix = $_GET['prefix'];
$type = $_GET['type'];
$o = $_GET['ontology'];

$settings = getSettings($o);


$domain = $settings['ns_main'];



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

FILTER regex (?s, \"/{$prefix}\") .
FILTER (isIRI(?s)).
}
";

$queryResult = json_query($queryString);

$resultList = array();
foreach ($queryResult as $result) {
	if (sizeof($result) == 1) {
		$resultList[$result['class']] = ' ';
	} else {
		$resultList[$result['class']] = $result['label'];
	}
}


if($orderColumn == "IRI") {
	if($orderBy == "ASC") {
		ksort($resultList);
		$orderBy = "DESC";
	} else {
		krsort($resultList);
		$orderBy = "ASC";
	}
} else {
	if($orderBy == "ASC") {
		asort($resultList);
		$orderBy = "DESC";
	} else {
		arsort($resultList);
		$orderBy = "ASC";
	}
}


?>
<h3 class="head3_darkred"><?php echo $settings['ontology_fullname'];?> statistics</h3>
<h3>Statistic detail of prefix: &nbsp;<strong><?php echo $prefix;  ?></strong>&nbsp; in &nbsp;<strong><?php echo $type;?></strong></h3>



<?php

if( sizeof( $queryResult ) == 0 ) {

?>

<div align="center"><p>IRI with prefix: <?php echo $prefix; ?> in <?php echo $type; ?> not found.</p></div>

<?php
}
else {

?>

<table align="center" width="850">

<tr height="25" align="center">
	<td  bgcolor="#AAAAAA" ><strong><a href="statDetail.php?prefix=<?php echo $prefix; ?>&type=<?php echo $type; ?>&ontology=<?php echo $o; ?>&orderColumn=IRI&orderBy=<?php echo $orderBy; ?>"/>IRI</a></strong></td>
	<td  bgcolor="#AAAAAA"><strong><a href="statDetail.php?prefix=<?php echo $prefix; ?>&type=<?php echo $type; ?>&ontology=<?php echo $o; ?>&orderColumn=LABEL&orderBy=<?php echo $orderBy; ?>"/>label</a></strong></td>
</tr>

<?php

$index = 0;

foreach( $resultList as $key => $value ){

?>

<tr height="25" <?php if ( $index % 2 == 1){ echo "bgcolor = \"#CCECFB\""; } ?> >
	<td width="480">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=" <?php echo $key?> "/> <?php echo $key ?> </a></td>
	<td  >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $value;  ?></td>
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

FILTER regex (?s, \"/{$prefix}\") .
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

FILTER regex (?s, \"/{$prefix}\") .
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

FILTER regex (?s, \"/{$prefix}\") .
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

FILTER regex (?s, \"/{$prefix}\") .
FILTER (isIRI(?s)).
}
";

$classQueryResult = json_query($classQueryString);
$objectQueryResult = json_query($objectQueryString);
$annotationQueryResult = json_query($annotationQueryString);
$datatypeQueryResult = json_query($datatypeQueryString);


$index = 0;

$totalList = array();

foreach ($classQueryResult as $result) {
	if (sizeof($result) == 1) {
		$totalList[$result['class']] = " ";
	} else {
		$totalList[$result['class']] = $result['label'];
	}
}

foreach ($objectQueryResult as $result) {
	if (sizeof($result) == 1) {
		$totalList[$result['object']] = " ";
	} else {
		$totalList[$result['object']] = $result['label'];
	}
}

foreach ($annotationQueryResult as $result) {
	if (sizeof($result) == 1) {
		$totalList[$result['annotation']] = " ";
	} else {
		$totalList[$result['annotation']] = $result['label'];
	}
}

foreach ($datatypeQueryResult as $result) {
	if (sizeof($result) == 1) {
		$totalList[$result['datatype']] = " ";
	} else {
		$totalList[$result['datatype']] = $result['label'];
	}
}


if($orderColumn == "IRI") {
	if($orderBy == "ASC") {
		ksort($totalList);
		$orderBy = "DESC";
	} else {
		krsort($totalList);
		$orderBy = "ASC";
	}
} else {
	if($orderBy == "ASC") {
		asort($totalList);
		$orderBy = "DESC";
	} else {
		arsort($totalList);
		$orderBy = "ASC";
	}
}

?>

<h3 class="head3_darkred"><?php echo $settings['ontology_fullname'];?> statistics</h3>
<h3>Statistic detail of prefix: &nbsp;<strong><?php echo $prefix;  ?></strong>&nbsp; in &nbsp;<strong><?php echo $type;?></strong></h3>
<table align="center" width="850">

<tr height="25" align="center">
	<td  bgcolor="#AAAAAA" ><strong><a href="statDetail.php?prefix=<?php echo $prefix; ?>&type=<?php echo $type; ?>&ontology=<?php echo $o; ?>&orderColumn=IRI&orderBy=<?php echo $orderBy; ?>"/>IRI</a></strong></td>
	<td  bgcolor="#AAAAAA"><strong><a href="statDetail.php?prefix=<?php echo $prefix; ?>&type=<?php echo $type; ?>&ontology=<?php echo $o; ?>&orderColumn=LABEL&orderBy=<?php echo $orderBy; ?>"/>label</a></strong></td>
</tr>
<?php
 
	foreach ($totalList as $key => $value) {
	?>
	<tr height="25" <?php if ( $index % 2 == 1){ echo "bgcolor = \"#CCECFB\""; } ?>>
		<td width="480">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=" <?php echo $key; ?> "/> <?php echo $key; ?>   </td>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $value;  ?></td>
	</tr>
	
	<?php
	$index ++;
	
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

$resultList = array();

foreach ($queryResult as $result) {
	if (sizeof($result) == 1) {
		$resultList[$result['class']] = " ";
	} else {
		$resultList[$result['class']] = $result['label'];	
	}
}

if($orderColumn == "IRI") {
	if($orderBy == "ASC") {
		ksort($resultList);
		$orderBy = "DESC";
	} else {
		krsort($resultList);
		$orderBy = "ASC";
	}
} else {
	if($orderBy == "ASC") {
		asort($resultList);
		$orderBy = "DESC";
	} else {
		arsort($resultList);
		$orderBy = "ASC";
	}
}

$index = 0;

?>

<h3 class="head3_darkred"><?php echo $settings['ontology_fullname'];?> statistics</h3>
<h3>Statistic detail of prefix: &nbsp;<strong><?php echo $prefix;  ?></strong>&nbsp; in &nbsp;<strong><?php echo $type;?></strong></h3>
<table align="center" width="850">

<tr height="25" align="center">
	<td  bgcolor="#AAAAAA" ><strong><a href="statDetail.php?prefix=<?php echo $prefix; ?>&type=<?php echo $type; ?>&ontology=<?php echo $o; ?>&orderColumn=IRI&orderBy=<?php echo $orderBy; ?>"/>IRI</a></strong></td>
	<td  bgcolor="#AAAAAA"><strong><a href="statDetail.php?prefix=<?php echo $prefix; ?>&type=<?php echo $type; ?>&ontology=<?php echo $o; ?>&orderColumn=LABEL&orderBy=<?php echo $orderBy; ?>"/>label</a></strong></td>
</tr>
<?php

foreach ($resultList as $key => $value) {
	?>
	<tr height="25" <?php if ( $index % 2 == 1){ echo "bgcolor = \"#CCECFB\""; } ?>>
		<td width="480">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=" <?php echo $key; ?> "/> <?php echo $key; ?>   </td>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $value; ?></td>
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

$totalList = array();

foreach ($classQueryResult as $result) {
	if (sizeof($result) == 1) {
		$totalList[$result['class']] = " ";
	} else {
		$totalList[$result['class']] = $result['label'];
	}
}

foreach ($objectQueryResult as $result) {
	if (sizeof($result) == 1) {
		$totalList[$result['object']] = " ";
	} else {
		$totalList[$result['object']] = $result['label'];
	}
}

foreach ($annotationQueryResult as $result) {
	if (sizeof($result) == 1) {
		$totalList[$result['annotation']] = " ";
	} else {
		$totalList[$result['annotation']] = $result['label'];
	}
}

foreach ($datatypeQueryResult as $result) {
	if (sizeof($result) == 1) {
		$totalList[$result['datatype']] = " ";
	} else {
		$totalList[$result['datatype']] = $result['label'];
	}
}

if($orderColumn == "IRI") {
	if($orderBy == "ASC") {
		ksort($totalList);
		$orderBy = "DESC";
	} else {
		krsort($totalList);
		$orderBy = "ASC";
	}
} else {
	if($orderBy == "ASC") {
		asort($totalList);
		$orderBy = "DESC";
	} else {
		arsort($totalList);
		$orderBy = "ASC";
	}
}

?>
<h3 class="head3_darkred"><?php echo $settings['ontology_fullname'];?> statistics</h3>
<h3>Statistic detail of prefix: &nbsp;<strong><?php echo $prefix;  ?></strong>&nbsp; in &nbsp;<strong><?php echo $type;?></strong></h3>
<table align="center" width="850">

<tr height="25" align="center">
	<td  bgcolor="#AAAAAA" ><strong><a href="statDetail.php?prefix=<?php echo $prefix; ?>&type=<?php echo $type; ?>&ontology=<?php echo $o; ?>&orderColumn=IRI&orderBy=<?php echo $orderBy; ?>"/>IRI</a></strong></td>
	<td  bgcolor="#AAAAAA"><strong><a href="statDetail.php?prefix=<?php echo $prefix; ?>&type=<?php echo $type; ?>&ontology=<?php echo $o; ?>&orderColumn=LABEL&orderBy=<?php echo $orderBy; ?>"/>label</a></strong></td>
</tr>
<?php
	
	foreach ($totalList as $key => $value) {
			?>
	<tr height="25" <?php if ( $index % 2 == 1){ echo "bgcolor = \"#CCECFB\""; } ?>>
		<td width="480">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=" <?php echo $key; ?> "/> <?php echo $key; ?>   </td>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $value; ?></td>
	</tr>

			<?php
			
			$index ++;
			
		}


?>
</table>


</br>

<p>Total #: <strong><?php echo $index; ?></strong></p>
<?php





} elseif ($prefix == 'NoPrefix' && $type != 'totalCount') {

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
$noPrefixArray = array();

if (sizeof($queryResult) != 0) {
	foreach ($queryResult as $result) {
		if (preg_match('/\/([A-Za-z\.\-_]+)#[a-zA-Z_0-9]+/', $result['class'], $match)) {
			
			continue;
		} elseif (preg_match('/\/([A-Z][A-Za-z]+)_[-a-zA-Z_0-9]+/', $result['class'], $match)) {
			
			continue;
		} elseif (preg_match('/\/([a-z]+)_[0-9]+/', $result['class'], $match)) {
			
			continue;
		} else {

			if (sizeof($result) == 1) {
				$noPrefixArray[$result['class']] = " ";
			} else {
				$noPrefixArray[$result['class']] = $result['label'];
			}

		}
	}
}

if($orderColumn == "IRI") {
	if($orderBy == "ASC") {
		ksort($noPrefixArray);
		$orderBy = "DESC";
	} else {
		krsort($noPrefixArray);
		$orderBy = "ASC";
	}
} else {
	if($orderBy == "ASC") {
		asort($noPrefixArray);
		$orderBy = "DESC";
	} else {
		arsort($noPrefixArray);
		$orderBy = "ASC";
	}
}


$index = 0;

?>
<h3 class="head3_darkred"><?php echo $settings['ontology_fullname'];?> statistics</h3>
<h3>Statistic detail of prefix: &nbsp;<strong><?php echo $prefix;  ?></strong>&nbsp; in &nbsp;<strong><?php echo $type;?></strong></h3>
<table align="center" width="850">

<tr height="25" align="center">
	<td  bgcolor="#AAAAAA" ><strong><a href="statDetail.php?prefix=<?php echo $prefix; ?>&type=<?php echo $type; ?>&ontology=<?php echo $o; ?>&orderColumn=IRI&orderBy=<?php echo $orderBy; ?>"/>IRI</a></strong></td>
	<td  bgcolor="#AAAAAA"><strong><a href="statDetail.php?prefix=<?php echo $prefix; ?>&type=<?php echo $type; ?>&ontology=<?php echo $o; ?>&orderColumn=LABEL&orderBy=<?php echo $orderBy; ?>"/>label</a></strong></td>
</tr>

<?php

if (sizeof($noPrefixArray) != 0) {
	foreach ($noPrefixArray as $key => $value) {
		?>
		<tr height="25" <?php if ( $index % 2 == 1){ echo "bgcolor = \"#CCECFB\""; } ?>>
			<td width="480">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=" <?php echo $key?> "/> <?php echo $key; ?>   </td>
			<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $value; ?></td>
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



} elseif ($prefix == "NoPrefix" && $type == "totalCount") {

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
FILTER (isIRI(?s)).

}
";

$classQueryResult = json_query($classQueryString);
$objectQueryResult = json_query($objectQueryString);
$datatypeQueryResult = json_query($datatypeQueryString);
$annotationQueryResult = json_query($annotationQueryString);

$index = 0;

$noPrefixClassArray = array();
$noPrefixObjectArray = array();
$noPrefixDatatypeArray = array();
$noPrefixAnnotationArray = array();

if (sizeof($classQueryResult) != 0) {
	foreach ($classQueryResult as $result) {
		if (preg_match('/\/([A-Za-z\.\-_]+)#[a-zA-Z_0-9]+/', $result['class'], $match)) {
			continue;
		} elseif (preg_match('/\/([A-Z][A-Za-z]+)_[-a-zA-Z_0-9]+/', $result['class'], $match)) {
			continue;
		} elseif (preg_match('/\/([a-z]+)_[0-9]+/', $result['class'], $match)) {
			continue;
		} else {
			if (sizeof($result) == 1) {
				$noPrefixClassArray[$result['class']] = " ";
			} else {
				$noPrefixClassArray[$result['class']] = $result['label'];
			}

		}
	}
}

ksort($noPrefixClassArray);

if (sizeof($objectQueryResult) != 0) {
	foreach ($objectQueryResult as $result) {
		if (preg_match('/\/([A-Za-z\.\-_]+)#[a-zA-Z_0-9]+/', $result['object'], $match)) {
			continue;
		} elseif (preg_match('/\/([A-Z][A-Za-z]+)_[-a-zA-Z_0-9]+/', $result['object'], $match)) {
			continue;
		} elseif (preg_match('/\/([a-z]+)_[0-9]+/', $result['object'], $match)) {
			continue;
		} else {
			if (sizeof($result) == 1) {
				$noPrefixObjectArray[$result['object']] = " ";
			} else {
				$noPrefixObjectArray[$result['object']] = $result['label'];
			}

		}
	}
}

ksort($noPrefixObjectArray);

if (sizeof($datatypeQueryResult) != 0) {
	foreach ($datatypeQueryResult as $result) {
		if (preg_match('/\/([A-Za-z\.\-_]+)#[a-zA-Z_0-9]+/', $result['datatype'], $match)) {
			continue;
		} elseif (preg_match('/\/([A-Z][A-Za-z]+)_[-a-zA-Z_0-9]+/', $result['datatype'], $match)) {
			continue;
		} elseif (preg_match('/\/([a-z]+)_[0-9]+/', $result['datatype'], $match)) {
			continue;
		} else {
			if (sizeof($result) == 1) {
				$noPrefixDatatypeArray[$result['datatype']] = " ";
			} else {
				$noPrefixDatatypeArray[$result['datatype']] = $result['label'];
			}

		}
	}
}

ksort($noPrefixDatatypeArray);

if (sizeof($annotationQueryResult) != 0) {
	foreach ($annotationQueryResult as $result) {
		if (preg_match('/\/([A-Za-z\.\-_]+)#[a-zA-Z_0-9]+/', $result['annotation'], $match)) {
			continue;
		} elseif (preg_match('/\/([A-Z][A-Za-z]+)_[-a-zA-Z_0-9]+/', $result['annotation'], $match)) {
			continue;
		} elseif (preg_match('/\/([a-z]+)_[0-9]+/', $result['annotation'], $match)) {
			continue;
		} else {
			if (sizeof($result) == 1) {
				$noPrefixAnnotationArray[$result['annotation']] = " ";
			} else {
				$noPrefixAnnotationArray[$result['annotation']] = $result['label'];
			}

		}
	}
}

ksort($noPrefixAnnotationArray);

$resultList = array();

foreach($noPrefixClassArray as $key => $value) {
	$resultList[$key] = $value;
}
foreach($noPrefixDatatypeArray as $key => $value) {
	$resultList[$key] = $value;
}
foreach($noPrefixAnnotationArray as $key => $value) {
	$resultList[$key] = $value;
}
foreach($noPrefixObjectArray as $key => $value) {
	$resultList[$key] = $value;
}

if($orderColumn == "IRI") {
	if($orderBy == "ASC") {
		ksort($resultList);
		$orderBy = "DESC";
	} else {
		krsort($resultList);
		$orderBy = "ASC";
	}
} else {
	if($orderBy == "ASC") {
		asort($resultList);
		$orderBy = "DESC";
	} else {
		arsort($resultList);
		$orderBy = "ASC";
	}
}

?>
<h3 class="head3_darkred"><?php echo $settings['ontology_fullname'];?> statistics</h3>
<h3>Statistic detail of prefix: &nbsp;<strong><?php echo $prefix;  ?></strong>&nbsp; in &nbsp;<strong><?php echo $type;?></strong></h3>
<table align="center" width="850">

<tr height="25" align="center">
	<td  bgcolor="#AAAAAA" ><strong><a href="statDetail.php?prefix=<?php echo $prefix; ?>&type=<?php echo $type; ?>&ontology=<?php echo $o; ?>&orderColumn=IRI&orderBy=<?php echo $orderBy; ?>"/>IRI</a></strong></td>
	<td  bgcolor="#AAAAAA"><strong><a href="statDetail.php?prefix=<?php echo $prefix; ?>&type=<?php echo $type; ?>&ontology=<?php echo $o; ?>&orderColumn=LABEL&orderBy=<?php echo $orderBy; ?>"/>label</a></strong></td>
</tr>

<?php

if (sizeof($noPrefixClassArray) == -1) {
	foreach ($noPrefixClassArray as $key => $value) {
		?>
		<tr height="25" <?php if ( $index % 2 == 1){ echo "bgcolor = \"#CCECFB\""; } ?>>
			<td width="480">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=" <?php echo $key?> "/> <?php echo $key; ?>   </td>
			<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $value; ?></td>
		</tr>
		<?php
		$index ++;
	}
}

if (sizeof($noPrefixObjectArray) == -1) {
	foreach ($noPrefixObjectArray as $key => $value) {
		?>
		<tr height="25" <?php if ( $index % 2 == 1){ echo "bgcolor = \"#CCECFB\""; } ?>>
			<td width="480">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=" <?php echo $key?> "/> <?php echo $key; ?>   </td>
			<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $value; ?></td>
		</tr>
		<?php
		$index ++;
	}
}

if (sizeof($noPrefixDatatypeArray) == -1) {
	foreach ($noPrefixDatatypeArray as $key => $value) {
		?>
		<tr height="25" <?php if ( $index % 2 == 1){ echo "bgcolor = \"#CCECFB\""; } ?>>
			<td width="480">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=" <?php echo $key?> "/> <?php echo $key ?>   </td>
			<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $value; ?></td>
		</tr>
		<?php
		$index ++;
	}
}

if (sizeof($noPrefixAnnotationArray) == -1) {
	foreach ($noPrefixAnnotationArray as $key => $value) {
		?>
		<tr height="25" <?php if ( $index % 2 == 1){ echo "bgcolor = \"#CCECFB\""; } ?>>
			<td width="480">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=" <?php echo $key?> "/> <?php echo $key ?>   </td>
			<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $value; ?></td>
		</tr>
		<?php
		$index ++;
	}
}

foreach ($resultList as $key => $value) {
		?>
		<tr height="25" <?php if ( $index % 2 == 1){ echo "bgcolor = \"#CCECFB\""; } ?>>
			<td width="480">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=" <?php echo $key?> "/> <?php echo $key ?>   </td>
			<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $value; ?></td>
		</tr>
		<?php
		$index ++;
	}


?>
</table>

</br>

<p>Total #: <strong><?php echo $index; ?></strong></p>

<?php

}
?>

<p>&nbsp;</p>



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
