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
Purpose: Statistic page of Ontobee.
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

if (isset($_GET['ontology'])) {

$o = $_GET['ontology'];


$settings = getSettings($o);



?>
<h3 class="head3_darkred">Statistics of <?php echo "<a href=\"browser/index.php?o={$o}\">{$settings['ontology_fullname']}</a>"; ?></h3>
<p><strong>Ontology:</strong> <?php echo $o?></p>

 <?php


$queryString = "
PREFIX rdf: <{$settings['ns_rdf']}>
PREFIX owl: <{$settings['ns_owl']}>
SELECT  distinct ?s as ?class
FROM <{$settings['ns_main']}>
WHERE
{
?s a owl:Class .
FILTER (isIRI(?s)).

}
";

$result = json_query($queryString);

$prefixArray = array();
$classNoPrefixCount = 0;

foreach($result as $class){
    if (preg_match('/\/([A-Za-z\.\-_]+)#[a-zA-Z_0-9]+/', $class['class'], $match)) {
        $prefix = $match[1];
        if(array_key_exists($prefix, $prefixArray)){
            $prefixArray[$prefix] += 1;
        }
        else{
            $prefixArray[$prefix] = 1;
        }    
    }
	elseif (preg_match('/\/([A-Z][A-Za-z]+)_[-a-zA-Z_0-9]+/', $class['class'], $match)) {
	    $prefix = $match[1];
        if(array_key_exists($prefix, $prefixArray)){
            $prefixArray[$prefix] += 1;
        }
        else{
            $prefixArray[$prefix] = 1;
        } 
	}
	elseif (preg_match('/\/([a-z]+)_[0-9]+/', $class['class'], $match)) {
	    $prefix = $match[1];
        if(array_key_exists($prefix, $prefixArray)){
            $prefixArray[$prefix] += 1;
        }
        else{
            $prefixArray[$prefix] = 1;
        } 
	}
	else {
	
		$classNoPrefixCount ++;
	}
}


$cCountQuery = "
PREFIX rdf: <{$settings['ns_rdf']}>
PREFIX owl: <{$settings['ns_owl']}>
SELECT  count(distinct ?s) as ?cCount
FROM <{$settings['ns_main']}>
WHERE
{
?s a owl:Class .
FILTER (isIRI(?s)).

}
";

$oCountQuery = "
PREFIX rdf: <{$settings['ns_rdf']}>
PREFIX owl: <{$settings['ns_owl']}>
SELECT  count(distinct ?s) as ?oCount
FROM <{$settings['ns_main']}>
WHERE
{
?s a owl:ObjectProperty .
FILTER (isIRI(?s)).

}
";

$aCountQuery = "
PREFIX rdf: <{$settings['ns_rdf']}>
PREFIX owl: <{$settings['ns_owl']}>
SELECT  count(distinct ?s) as ?aCount
FROM <{$settings['ns_main']}>
WHERE
{
?s a owl:AnnotationProperty .
FILTER (isIRI(?s)).

}
";

$dCountQuery = "
PREFIX rdf: <{$settings['ns_rdf']}>
PREFIX owl: <{$settings['ns_owl']}>
SELECT  count(distinct ?s) as ?dCount
FROM <{$settings['ns_main']}>
WHERE
{
?s a owl:DatatypeProperty .
FILTER (isIRI(?s)).

}
";

$cCountResult = json_query($cCountQuery);

$aCountResult = json_query($aCountQuery);

$dCountResult = json_query($dCountQuery);

$oCountResult = json_query($oCountQuery);

$cCount = $cCountResult[0]['cCount'];

$aCount = $aCountResult[0]['aCount'];

$dCount = $dCountResult[0]['dCount'];

$oCount = $oCountResult[0]['oCount'];

$total = $cCount + $aCount + $dCount + $oCount;







?>
  
  The total number of ontology terms in the ontology is:  <strong><?php  echo $total;?></strong></p>


<?php

$objectPropertyQueryString = "
PREFIX rdf: <{$settings['ns_rdf']}>
PREFIX owl: <{$settings['ns_owl']}>
SELECT  distinct ?s as ?object
FROM <{$settings['ns_main']}>
WHERE
{
?s a owl:ObjectProperty .
FILTER (isIRI(?s)).

}
";


$AnnotationPropertyQueryString = "
PREFIX rdf: <{$settings['ns_rdf']}>
PREFIX owl: <{$settings['ns_owl']}>
SELECT  distinct ?s as ?annotation
FROM <{$settings['ns_main']}>
WHERE
{
?s a owl:AnnotationProperty .
FILTER (isIRI(?s)).

}
";


$DatatypePropertyQueryString = "
PREFIX rdf: <{$settings['ns_rdf']}>
PREFIX owl: <{$settings['ns_owl']}>
SELECT  distinct ?s as ?datatype
FROM <{$settings['ns_main']}>
WHERE
{
?s a owl:DatatypeProperty .
FILTER (isIRI(?s)).

}
";


$propertyResult = json_query($objectPropertyQueryString);
$objectPrefixArray = array();
$objectNoPrefixCount = 0;

if ( count($propertyResult) != 0 )
{



foreach($propertyResult as $property){
    if (preg_match('/\/([A-Za-z\.\-_]+)#[a-zA-Z_0-9]+/', $property['object'], $match)) {
        $prefix = $match[1];
        if(array_key_exists($prefix, $objectPrefixArray)){
            $objectPrefixArray[$prefix] += 1;
        }
        else{
            $objectPrefixArray[$prefix] = 1;
        }    
    }
	elseif (preg_match('/\/([A-Z][A-Za-z]+)_[-a-zA-Z_0-9]+/', $property['object'], $match)) {
	    $prefix = $match[1];
        if(array_key_exists($prefix, $objectPrefixArray)){
            $objectPrefixArray[$prefix] += 1;
        }
        else{
            $objectPrefixArray[$prefix] = 1;
        } 
	}
	elseif (preg_match('/\/([a-z]+)_[0-9]+/', $property['object'], $match)) {
	    $prefix = $match[1];
        if(array_key_exists($prefix, $objectPrefixArray)){
            $objectPrefixArray[$prefix] += 1;
        }
        else{
            $objectPrefixArray[$prefix] = 1;
        } 
	}
	else {
	
		$objectNoPrefixCount ++;
	
	}
}

}

$apropertyResult = json_query($AnnotationPropertyQueryString);
$annotationPrefixArray = array();

$annotationNoPrefixCount = 0;

if ( count($apropertyResult) != 0)
{



foreach($apropertyResult as $property){
    if (preg_match('/\/([A-Za-z\.\-_]+)#[a-zA-Z_0-9]+/', $property['annotation'], $match)) {
        $prefix = $match[1];
        if(array_key_exists($prefix, $annotationPrefixArray)){
            $annotationPrefixArray[$prefix] += 1;
        }
        else{
            $annotationPrefixArray[$prefix] = 1;
        }    
    }
	elseif (preg_match('/\/([A-Z][A-Za-z]+)_[-a-zA-Z_0-9]+/', $property['annotation'], $match)) {
	    $prefix = $match[1];
        if(array_key_exists($prefix, $annotationPrefixArray)){
            $annotationPrefixArray[$prefix] += 1;
        }
        else{
            $annotationPrefixArray[$prefix] = 1;
        } 
	}
	elseif (preg_match('/\/([a-z]+)_[0-9]+/', $property['annotation'], $match)) {
	    $prefix = $match[1];
        if(array_key_exists($prefix, $annotationPrefixArray)){
            $annotationPrefixArray[$prefix] += 1;
        }
        else{
            $annotationPrefixArray[$prefix] = 1;
        } 
	}
	else {
	
		$annotationNoPrefixCount ++;
	
	}
}

}

$dpropertyResult = json_query($DatatypePropertyQueryString);
$datatypePrefixArray = array();

$datatypeNoPrefixCount = 0;

if ( count($dpropertyResult) != 0 )
{



foreach($dpropertyResult as $property){
    if (preg_match('/\/([A-Za-z\.\-_]+)#[a-zA-Z_0-9]+/', $property['datatype'], $match)) {
        $prefix = $match[1];
        if(array_key_exists($prefix, $datatypePrefixArray)){
            $datatypePrefixArray[$prefix] += 1;
        }
        else{
            $datatypePrefixArray[$prefix] = 1;
        }    
    }
	elseif (preg_match('/\/([A-Z][A-Za-z]+)_[-a-zA-Z_0-9]+/', $property['datatype'], $match)) {
	    $prefix = $match[1];
        if(array_key_exists($prefix, $datatypePrefixArray)){
            $datatypePrefixArray[$prefix] += 1;
        }
        else{
            $datatypePrefixArray[$prefix] = 1;
        } 
	}
	elseif (preg_match('/\/([a-z]+)_[0-9]+/', $property['datatype'], $match)) {
	    $prefix = $match[1];
        if(array_key_exists($prefix, $datatypePrefixArray)){
            $datatypePrefixArray[$prefix] += 1;
        }
        else{
            $datatypePrefixArray[$prefix] = 1;
        } 
	}	
	else {
	
		$datatypeNoPrefixCount ++;
	
	}
	
}

}


$totalPrefixArray = array();

if (count( $prefixArray ) != 0) {
	foreach(array_keys($prefixArray) as $classPrefix) {
		if (array_key_exists($classPrefix, $totalPrefixArray)) {
			$totalPrefixArray[$classPrefix] += $prefixArray[$classPrefix];
		}
		else {
			$totalPrefixArray[$classPrefix] = $prefixArray[$classPrefix];
		
		}	
	}
} 

if (count( $objectPrefixArray ) != 0) {
	foreach(array_keys($objectPrefixArray) as $classPrefix) {
		if (array_key_exists($classPrefix, $totalPrefixArray)) {
			$totalPrefixArray[$classPrefix] += $objectPrefixArray[$classPrefix];
		}
		else {
			$totalPrefixArray[$classPrefix] = $objectPrefixArray[$classPrefix];
		
		}	
	}
} 

if (count( $annotationPrefixArray ) != 0) {
	foreach(array_keys($annotationPrefixArray) as $classPrefix) {
		if (array_key_exists($classPrefix, $totalPrefixArray)) {
			$totalPrefixArray[$classPrefix] += $annotationPrefixArray[$classPrefix];
		}
		else {
			$totalPrefixArray[$classPrefix] = $annotationPrefixArray[$classPrefix];
		
		}	
	}
}

if (count( $datatypePrefixArray ) != 0) {
	foreach(array_keys($datatypePrefixArray) as $classPrefix) {
		if (array_key_exists($classPrefix, $totalPrefixArray)) {
			$totalPrefixArray[$classPrefix] += $datatypePrefixArray[$classPrefix];
		}
		else {
			$totalPrefixArray[$classPrefix] = $datatypePrefixArray[$classPrefix];
		
		}	
	}
}

arsort($totalPrefixArray); 

?>

<br/>

<table  align="center" width="800">
	<tr  align="center" height="25">
		<td bgcolor="#AAAAAA"><strong>Index</strong></td>
		<td bgcolor="#AAAAAA"><strong>Prefix</strong></td>
		<td bgcolor="#AAAAAA"><strong>Classes</strong></td>
		<td bgcolor="#AAAAAA"><strong>Object Properties</strong></td>
		<td bgcolor="#AAAAAA"><strong>Datatype Properties</strong></td>
		<td bgcolor="#AAAAAA"><strong>Annotation Properties</strong></td>
		<td bgcolor="#AAAAAA"><strong>Total</strong></td>
	</tr>
	
<?php
	
$index = 1;

		foreach( array_keys( $totalPrefixArray ) as $prefix ) {
		
		if(array_key_exists( $prefix, $prefixArray)){
			$classPrefixCount = $prefixArray[$prefix];
		}
		else {
			$classPrefixCount = 0;		
		}
		
		if(array_key_exists( $prefix, $objectPrefixArray)){
			$objectPrefixCount = $objectPrefixArray[$prefix];
		}
		else {
			$objectPrefixCount = 0;		
		}
		
		if(array_key_exists( $prefix, $datatypePrefixArray)){
			$datatypePrefixCount = $datatypePrefixArray[$prefix];
		}
		else {
			$datatypePrefixCount = 0;		
		}
		
		if(array_key_exists( $prefix, $annotationPrefixArray)){
			$annotationPrefixCount = $annotationPrefixArray[$prefix];
		}
		else {
			$annotationPrefixCount = 0;		
		}
		
			
		?>
		
		<tr  height="25" align="center" <?php if ($index %2 == 0) { echo "bgcolor = \"#CCECFB\"";} ?>>
			<td><?php echo $index; ?></td>
			<td><strong><?php echo $prefix; ?></strong></td>
			<td><?php if ($classPrefixCount != 0) {echo "<a href=\"statDetail.php?prefix={$prefix}&type=Class&ontology={$o}\">{$classPrefixCount}</a>"; } else { echo $classPrefixCount;} ?></td>
			<td><?php if ($objectPrefixCount != 0) {echo "<a href=\"statDetail.php?prefix={$prefix}&type=ObjectProperty&ontology={$o}\">{$objectPrefixCount}</a>"; } else { echo $objectPrefixCount;} ?></td>
			<td><?php if ($datatypePrefixCount != 0) {echo "<a href=\"statDetail.php?prefix={$prefix}&type=DatatypeProperty&ontology={$o}\">{$datatypePrefixCount}</a>"; } else { echo $datatypePrefixCount;} ?></td>
			<td><?php if ($annotationPrefixCount != 0) {echo "<a href=\"statDetail.php?prefix={$prefix}&type=AnnotationProperty&ontology={$o}\">{$annotationPrefixCount}</a>"; } else { echo $annotationPrefixCount;} ?></td>
			<td><?php if ($totalPrefixArray[$prefix] != 0) {echo "<a href=\"statDetail.php?prefix={$prefix}&type=totalCount&ontology={$o}\">{$totalPrefixArray[$prefix]}</a>";} else {echo $totalPrefixArray[$prefix];} ?></td>			
		</tr>
		
<?php

$index ++;

		}
		
	$classPrefixTotalCount = 0;
	foreach( $prefixArray as $prefix => $value) {
		$classPrefixTotalCount += $value;	
	}
	
	$objectPrefixTotalCount = 0;
	foreach( $objectPrefixArray as $prefix => $value) {
		$objectPrefixTotalCount += $value;	
	}
	
	$datatypePrefixTotalCount = 0;
	foreach( $datatypePrefixArray as $prefix => $value) {
		$datatypePrefixTotalCount += $value;	
	}
	
	$annotationPrefixTotalCount = 0;
	foreach( $annotationPrefixArray as $prefix => $value) {
		$annotationPrefixTotalCount += $value;	
	}
	
	$totalPrefixCount = $classPrefixTotalCount + $objectPrefixTotalCount + $datatypePrefixTotalCount + $annotationPrefixTotalCount;
		
?>

	<tr  height="25" align="center">
		<td><?php echo $index; ?></td>
		<td><strong>-No Prefix-</strong></td>
		<td><?php if ($classNoPrefixCount != 0) {echo "<a href=\"statDetail.php?prefix=NoPrefix&type=Class&ontology={$o}\">{$classNoPrefixCount}</a>";} else {echo $classNoPrefixCount;} ?></td>
		<td><?php if ($objectNoPrefixCount != 0) {echo "<a href=\"statDetail.php?prefix=NoPrefix&type=ObjectProperty&ontology={$o}\">{$objectNoPrefixCount}</a>";} else {echo $objectNoPrefixCount;} ?></td>
		<td><?php if ($datatypeNoPrefixCount != 0) {echo "<a href=\"statDetail.php?prefix=NoPrefix&type=DatatypeProperty&ontology={$o}\">{$datatypeNoPrefixCount}</a>";} else {echo $datatypeNoPrefixCount;} ?></td>
		<td><?php if ($annotationNoPrefixCount != 0) {echo "<a href=\"statDetail.php?prefix=NoPrefix&type=AnnotationProperty&ontology={$o}\">{$annotationNoPrefixCount}</a>";} else {echo $annotationNoPrefixCount;} ?></td>
		<td><?php $num = $classNoPrefixCount + $objectNoPrefixCount + $datatypeNoPrefixCount + $annotationNoPrefixCount;  if ($num != 0) {echo "<a href=\"statDetail.php?prefix=NoPrefix&type=totalCount&ontology={$o}\">{$num}</a>";} else {echo $num;} ?></td>
	</tr>

	<tr align="center" height="25">
		<td bgcolor="#DDDDDD"><strong>Total Count</strong></td>
		<td bgcolor="#DDDDDD"> - </td>
		<td bgcolor="#DDDDDD"><strong><?php $num = $classPrefixTotalCount + $classNoPrefixCount; if ($num != 0) {echo "<a href=\"statDetail.php?prefix=total&type=Class&ontology={$o}\">{$num}</a>"; } else { echo $num;} ?></strong></td>
		<td bgcolor="#DDDDDD"><strong><?php $num = $objectPrefixTotalCount + $objectNoPrefixCount; if ($num != 0) {echo "<a href=\"statDetail.php?prefix=total&type=ObjectProperty&ontology={$o}\">{$num}</a>"; } else { echo $num;} ?></strong></td>
		<td bgcolor="#DDDDDD"><strong><?php  $num = $datatypePrefixTotalCount + $datatypeNoPrefixCount; if ($num != 0) {echo "<a href=\"statDetail.php?prefix=total&type=DatatypeProperty&ontology={$o}\">{$num}</a>"; } else { echo $num;} ?></strong></td>
		<td bgcolor="#DDDDDD"><strong><?php  $num = $annotationPrefixTotalCount + $annotationNoPrefixCount; if ($num != 0) {echo "<a href=\"statDetail.php?prefix=total&type=AnnotationProperty&ontology={$o}\">{$num}</a>"; } else { echo $num;} ?></strong></td>
		<td bgcolor="#DDDDDD"><strong><?php $num = $totalPrefixCount + $classNoPrefixCount + $objectNoPrefixCount + $datatypeNoPrefixCount + $annotationNoPrefixCount; if ($num != 0) {echo "<a href=\"statDetail.php?prefix=total&type=totalCount&ontology={$o}\">{$num}</a>"; } else { echo $num;} ?></strong></td>
	</tr>
	
	
</table>
<br>

<?php 
} else {
?>
<br>
<p style="color:#FF0000">Note:</p>
<p align="center">Please go to <a href="ontostat/index.php">Ontobee statistics index page </a> to choose an ontology.</p>
<br>
<br>
<?php

}
?>


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
