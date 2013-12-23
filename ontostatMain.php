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
Purpose: Ontobee ontology statistic section main page.
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

$db = ADONewConnection($driver);
$db->Connect($host, $username, $password, $database);

$strSql="select * from ontology where loaded='y' and to_list='y' order by ontology_abbrv";
$rs=$db->Execute($strSql);

$index = 1;

?>
<br>
<h3 class="head3_darkred">Overall ontology statistics</h3>
<p>Click Ontology Prefix to see detail statistics.</p>

<table  align="center" width="800">
	<tr  align="center" height="25">
		<td bgcolor="#AAAAAA"><strong>Index</strong></td>
		<td bgcolor="#AAAAAA"><strong>Ontology Prefix</strong></td>
		<td bgcolor="#AAAAAA"><strong>Classes</strong></td>
		<td bgcolor="#AAAAAA"><strong>Object Properties</strong></td>
		<td bgcolor="#AAAAAA"><strong>Datatype Properties</strong></td>
		<td bgcolor="#AAAAAA"><strong>Annotation Properties</strong></td>
		<td bgcolor="#AAAAAA"><strong>Total</strong></td>
	</tr>


<?php
foreach ($rs as $row){

$o = $row['ontology_abbrv'];
$settings = getSettings($o);


$classQueryString = "
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

$objectQueryString = "
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

$datatypeQueryString = "
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

$annotationQueryString = "
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

$cCountResult = json_query($classQueryString);
$aCountResult = json_query($annotationQueryString);
$dCountResult = json_query($datatypeQueryString);
$oCountResult = json_query($objectQueryString);

$cCount = $cCountResult[0]['cCount'];
$aCount = $aCountResult[0]['aCount'];
$dCount = $dCountResult[0]['dCount'];
$oCount = $oCountResult[0]['oCount'];

$total = $cCount + $aCount + $dCount + $oCount;


?>
	<tr align="center" height="25" <?php if ($index %2 == 0) { echo "bgcolor = \"#CCECFB\"";} ?>>
		<td><strong><?php echo $index; ?></strong></td>
		<td><?php echo "<a href=\"ontostat.php?ontology={$row['ontology_abbrv']}\">{$row['ontology_abbrv']}</a>"; ?></td>
		<td><?php echo $cCount; ?></td>
		<td><?php echo $oCount; ?></td>
		<td><?php echo $dCount; ?></td>
		<td><?php echo $aCount; ?></td>
		<td><?php echo $total; ?></td>
	
<?php

	$index ++;
}

?>
</table>

<br>

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
