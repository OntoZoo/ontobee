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
Author: Zuoshuang Xiang
The University Of Michigan
He Group
Date: June 2008 - March 2013
Purpose: Ontobee ontology browsing section index page.
-->

<?php include_once('../inc/Classes.php');
$vali=new Validation($_REQUEST);

$o = $vali->getInput('o', 'Ontology', 1, 60, true);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html><!-- InstanceBegin template="/Templates/default.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<!-- InstanceBeginEditable name="doctitle" -->
<title>Ontobee: <?php echo $o?></title>
<!-- InstanceEndEditable --><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="shortcut icon" href="/favicon.ico" />
<link href="../css/styleMain.css" rel="stylesheet" type="text/css">
<!-- InstanceBeginEditable name="head" -->
<meta charset="utf-8">
<link rel="stylesheet" href="../js/jquery/themes/base/jquery.ui.all.css">
<script src="../js/jquery/jquery-1.7.1.js"></script>
<script src="../js/jquery/ui/jquery.ui.core.js"></script>
<script src="../js/jquery/ui/jquery.ui.widget.js"></script>
<script src="../js/jquery/ui/jquery.ui.position.js"></script>
<script src="../js/jquery/ui/jquery.ui.autocomplete.js"></script>
<style>
.ui-autocomplete-loading { background: white url('../images/ui-anim_basic_16x16.gif') right center no-repeat; }
</style>
<script>
$(function() {
	$( "#keywords" ).autocomplete({
		source: "../getTerm.php?ontology="+$( "#ontology" ).val(),
		minLength: 3,
		select: function( event, ui ) {
			var params = ui.item.id.split( /:::/ );
			window.location = "/browser/rdf.php?o="+$( "#ontology" ).val() + "&iri=" + params.pop();
		}
	});
});
</script>
<!-- InstanceEndEditable -->
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
<div id="topbanner"><a href="/index.php" style="font-size:36px; color:#111144; text-decoration:none"><img src="../images/logo.gif" alt="Logo" width="280" height="49" border="0"></a></div>
<div id="topnav"><a href="../index.php" class="topnav">Home</a><a href="../introduction.php" class="topnav">Introduction</a><a href="../ontostat/index.php" class="topnav">Statistics</a><a href="../sparql/index.php" class="topnav">SPARQL</a><a href="../ontobeep/index.php" class="topnav">Ontobeep</a><a href="../tutorial/index.php" class="topnav">Tutorial</a><a href="../faqs.php" class="topnav">FAQs</a><a href="../references.php" class="topnav">References</a><a href="../links.php" class="topnav">Links</a><a href="../contactus.php" class="topnav">Contact</a><a href="../acknowledge.php" class="topnav">Acknowledge</a><a href="../news.php" class="topnav">News</a></div>
<div id="mainbody">
<!-- InstanceBeginEditable name="Main" -->
<?php if ($vali->getErrorMsg()=='') {
	$settings = getSettings($o);
	
	if (isset($settings['ontology_fullname'])) {
?>
  
<h3 align="center"><a href="index.php?o=<?php echo $o?>"> <?php print($settings['ontology_fullname']); ?></a></h3>
<?php $time_start = microtime(true);

$num_queries = 0;



$vali=new Validation($_REQUEST);
$keywords = $vali->getInput('keywords', 'Kerwords', 0, 60, true);


if ($vali->getErrorMsg()=='') {
	$db = ADONewConnection($driver);
	$db->Connect($host, $username, $password, $database);
	if ($keywords!='') {
		$array_property=array();
		$array_property[]='http://www.w3.org/2000/01/rdf-schema#label';
		$array_property[]='http://purl.obolibrary.org/obo/IAO_0000111';
		$array_property[]='http://purl.obolibrary.org/obo/IAO_0000118';
		

		if (preg_match('/[a-zA-Z]+[:_]\d{7,}/', $keywords)) {
			$search_term_url='http://purl.obolibrary.org/obo/'.preg_replace('/:/', '_', $keywords);
			$querystring="SELECT distinct ?s ?o
from <{$settings['ns_main']}>
WHERE{
?s ?p ?o .
filter (?p in (<".join('>,<', $array_property).">)).
filter (?s in (<$search_term_url>)).
}
ORDER BY ?o
limit 5000
";
		}
		else {
			$querystring="SELECT  distinct ?s ?o
from <{$settings['ns_main']}>
WHERE{
?s ?p ?o .
filter (?p in (<".join('>,<', $array_property).">)).
FILTER (isIRI(?s)).
?o bif:contains \"'$keywords*'\" OPTION (score ?sc)
}
ORDER BY desc(?sc)
limit 5000
";
		}

print("<!--$querystring-->");

		$results = json_query($querystring);
?>
  <form id="form_term_search" action="index.php" method="get" style="padding-top:20px">
<div class="ui-widget">
	<strong>
	<label for="keywords">Keywords: </label>
	</strong>
<input id="keywords" name="keywords" size="30" value="<?php print($keywords);?>" />
    <input type="submit" name="Submit2" value="Search terms" />
    <input name="o" type="hidden" id="ontology" value="<?php print($o); ?>">
</div>
  </form>
<?php 
		if (!empty($results)) {
			$tkeywords=preg_replace('/\W/', ' ', $keywords);

?>
<p>
<ol>
<?php
			$results2=array();
 			foreach ($results as $result) {
				if (strtolower($result['o'])==strtolower($keywords)) {

?>
	<li><?php print(preg_replace('/('.$tkeywords.')/i', '<strong>$1</strong>', $result['o']))?>: <a href="/browser/rdf.php?o=<?php print($o); ?>&amp;iri=<?php print(myUrlEncode($result['s'])); ?>">
		<?php print($result['s'])?>
	</a></li>
<?php
				}else {
					$results2[]=$result;
				}
			}


			$results3=array();
 			foreach ($results2 as $result) {
				if (strpos(strtolower($result['o']), strtolower($keywords))===0) {

?>
	<li><?php print(preg_replace('/('.$tkeywords.')/i', '<strong>$1</strong>', $result['o']))?>: <a href="/browser/rdf.php?o=<?php print($o); ?>&amp;iri=<?php print(myUrlEncode($result['s'])); ?>">
		<?php print($result['s'])?>
	</a></li>
<?php
				}else {
					$results3[]=$result;
				}
			}


 			foreach ($results3 as $result) {
?>
	<li><?php print(preg_replace('/('.$tkeywords.')/i', '<strong>$1</strong>', $result['o']))?>: <a href="/browser/rdf.php?o=<?php print($o); ?>&amp;iri=<?php print(myUrlEncode($result['s'])); ?>">
		<?php print($result['s'])?>
	</a></li>
<?php
			}


?>
</ol>
</p>
<?php 		}
		else {
?>
<p>No terms returned, please try different keywords.</p>
<?php 		}
	}
	else {
//show table ot contents
		$strSql="select * from ontology where ontology_abbrv='$o'";
		$rs=$db->Execute($strSql);


?>
<p><b>Ontology:</b> <?php print($settings['ontology_name']); ?></p>
<div style="background-color:#EAF1F2; border:#99CCFF 1px solid; margin-top:4px; margin-bottom:12px">
<ul>	
	<li>URI: <a href="<?php print($settings['ns_main_original']); ?>"><?php print($settings['ns_main_original']); ?></a></li>
<?php 		if ($rs->Fields('foundry')!='') {
?>
<li>OBO Foundry: <?php print($rs->Fields('foundry'));?></li>
<?php 		}
 		if ($rs->Fields('download')!='') {
?>
<li>Download: <a href="<?php print($rs->Fields('download'));?>"><?php print($rs->Fields('download'));?></a></li>
<?php 		}
 		if ($rs->Fields('alternative_download')!='') {
?>
<li>Alternative Download: <a href="<?php print($rs->Fields('alternative_download'));?>"><?php print($rs->Fields('alternative_download'));?></a></li>
<?php 		}
 		if ($rs->Fields('source')!='') {
?>
<li>Source: <a href="<?php print($rs->Fields('source'));?>"><?php print($rs->Fields('source'));?></a></li>
<?php 		}
 		if ($rs->Fields('home')!='') {
			$tokens=preg_split('/\|/', $rs->Fields('home'));
			if (sizeof($tokens)==2) {
?>
<li>Home: <a href="<?php print($tokens[1]);?>"><?php print($tokens[0]);?></a></li>
<?php 			}
			else {
?>
<li>Home: <a href="<?php print($tokens[0]);?>"><?php print($tokens[0]);?></a></li>
<?php 			}
		}
 		if ($rs->Fields('documentation')!='') {
			$tokens=preg_split('/\|/', $rs->Fields('documentation'));
			if (sizeof($tokens)==2) {
?>
<li>Documentation: <a href="<?php print($tokens[1]);?>"><?php print($tokens[0]);?></a></li>
<?php 			}
			else {
?>
<li>Documentation: <a href="<?php print($tokens[0]);?>"><?php print($tokens[0]);?></a></li>
<?php 			}
		}
 		if ($rs->Fields('contact')!='') {
			$tokens=preg_split('/\t/', $rs->Fields('contact'));

?>
<li>Contact: <a href="mailto:<?php print($tokens[1]);?>@<?php print($tokens[2]);?>"><?php print($tokens[0]);?></a></li>
<?php 		}
 		if ($rs->Fields('help')!='') {
			$tokens=preg_split('/\t/', $rs->Fields('help'));

?>
<li>Help: <a href="mailto:<?php print($tokens[1]);?>@<?php print($tokens[2]);?>"><?php print($tokens[0]);?></a></li>
<?php 		}
 		if ($rs->Fields('description')!='') {
?>
<li>Description: <?php print($rs->Fields('description'));?></li>
<?php 		}
?>
</ul>
</div>
<?php 		$querystring = "
			SELECT distinct *
			FROM <{$settings['ns_main']}>
			WHERE { <{$settings['ns_main_original']}> ?p ?o}
			";

//		print("<!--$querystring-->");
		$results = json_query($querystring);
		$num_queries++;
		
//		print("<!--");
//		print_r($results);
//		print("-->");
		//var_dump($results);

		
		$a_annotation=array();
		foreach($results as $annotation) {
			$a_annotation[getShortTerm($annotation['p'])][]=$annotation['o'];
		}
		
		ksort($a_annotation);
		
		if(!empty($results)) {
?>
<div style="font-weight:bold">Annotations</div>
<div style="background-color:#EAF1F2; border:#99CCFF 1px solid; margin-top:4px; margin-bottom:12px">
	<ul>
<?php
			$a_main_annotation_type=array('versionIRI', 'title', 'description', 'subject', 'creator', 'format', 'versionInfo', 'date', 'comment');
			
			foreach ($a_main_annotation_type as $main_annotation_type) {
				if (isset($a_annotation[$main_annotation_type])) {
					if ($main_annotation_type=='creator') {
?>
		<li><span style="color:#333333">
			<?php print($main_annotation_type); ?>
			</span>: <span style="color:#006600">
				<?php print(UTF_to_Unicode(join(', ', $a_annotation[$main_annotation_type]))); ?>
			</span></li>
<?php
					}
					else {
						foreach($a_annotation[$main_annotation_type] as $annotation_value) {
?>
		<li><span style="color:#333333">
			<?php print($main_annotation_type); ?>
			</span>: <span style="color:#006600">
				<?php print(UTF_to_Unicode($annotation_value)); ?>
			</span></li>
<?php
						}
					}
				}
			}


			foreach($a_annotation as $annotation_type => $a_annotation_value) {
				if (!in_array($annotation_type, $a_main_annotation_type)) {
					foreach($a_annotation_value as $annotation_value) {
						if($annotation_value!='http://www.w3.org/2002/07/owl#NamedIndividual') {
?>
		<li><span style="color:#333333">
			<?php print($annotation_type); ?>
			</span>: <span style="color:#006600">
				<?php print(UTF_to_Unicode($annotation_value)); ?>
			</span></li>
<?php
						}
					}
				}
			}
?>
	</ul>
</div>
<?php
		}
?>

  <form id="form_term_search" action="index.php" method="get" style="padding-top:20px; padding-bottom:20px;">
<div class="ui-widget">
	<strong>
	<label for="keywords">Keywords: </label>
	</strong>
<input id="keywords" name="keywords" size="30" value="<?php print($keywords);?>" />
    <input type="submit" name="Submit2" value="Search terms" />
    <input name="o" type="hidden" id="ontology" value="<?php print($o); ?>">
</div>
  </form>

<div style="font-weight:bold">
	Number of Terms (<span class="darkred">including imported terms</span>)  <a href="../ontostat.php?ontology=<?php echo $o?>">(Detailed Statistics)</a></div>
<div style="background-color:#EAF1F2; border:#99CCFF 1px solid; margin-top:4px; margin-bottom:12px">
	<ul>
		<?php 		foreach ($ref_types as $ref_type) {
			$querystring = "
PREFIX rdf: <{$settings['ns_rdf']}>
PREFIX owl: <{$settings['ns_owl']}>
SELECT count(distinct ?s) as ?num_o
FROM <{$settings['ns_main']}>

WHERE {?s a owl:$ref_type.
FILTER (isIRI(?s)).
} 
";



//			error_log($querystring, 3, '/tmp/error.log');
			$result = json_query($querystring);
			$num_queries++;
//					var_dump($result);
		
			if(isset($result[0]['num_o']) && $result[0]['num_o']>0) {
?>
		<li><a href="term.php?o=<?php print($o); ?>&amp;iri=<?php print(myUrlEncode($settings['ns_owl']. $ref_type)); ?>&graph=<?php print(myUrlEncode($settings['ns_main'])); ?>">
			<?php print($ref_type); ?>
			</a> (
			<?php print($result[0]['num_o']); ?>
			)</li>
		<?php 			}
		}
?>
	</ul>
</div>
<?php 		if (!empty($settings['core_terms'])) {
?>
<div style="font-weight:bold">Top level terms and selected core <?php print($settings['ontology_name']); ?>
	terms</div>
<div style="background-color:#EAF1F2; border:#99CCFF 1px solid; margin-top:4px; margin-bottom:12px">
	<ul>
		<?php 		foreach ($settings['core_terms'] as $term_url => $term_label) {
?>
		<li><a href="/browser/rdf.php?o=<?php print($o); ?>&amp;iri=<?php print(myUrlEncode($term_url)); ?>">
			<?php print($term_label); ?>
		</a></li>
		<?php 		}
?>
	</ul>
</div>
<?php 		}

	}
?>
<div style="color:#CCCCCC">Number of SPARQL queries:
	<?php print($num_queries); ?>
</div>
<?php }
else {
	include('../inc/input_error.php');
}
	}
	else {
?>
<p>Ontology not supported</p>
<?php 		
	}
}
else {
	include('../inc/input_error.php');
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
		<td width="300"><div id="footer_right"><a href="http://www.umich.edu" target="_blank"><img src="../images/wordmark_m_web.jpg" alt="UM Logo" width="166" height="20" border="0"></a></div></td>
	</tr>
</table>
</div>
</body>
<!-- InstanceEnd --></html>
