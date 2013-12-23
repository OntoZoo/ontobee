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
Authors: Zuoshuang Allen Xiang, Yongqun Oliver He
The University Of Michigan
He Group
Date: 2008 - 2013
Purpose: The cover page of the Ontobee project.  
-->

<?php include_once('inc/Classes.php');
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html><!-- InstanceBegin template="/Templates/default.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<!-- InstanceBeginEditable name="doctitle" -->
<title>Ontobee</title>
<!-- InstanceEndEditable --><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="shortcut icon" href="/favicon.ico" />
<link href="css/styleMain.css" rel="stylesheet" type="text/css">
<!-- InstanceBeginEditable name="head" -->
<meta charset="utf-8">
<link rel="stylesheet" href="js/jquery/themes/base/jquery.ui.all.css">
<script src="js/jquery/jquery-1.7.1.js"></script>
<script src="js/jquery/ui/jquery.ui.core.js"></script>
<script src="js/jquery/ui/jquery.ui.widget.js"></script>
<script src="js/jquery/ui/jquery.ui.position.js"></script>
<script src="js/jquery/ui/jquery.ui.autocomplete.js"></script>
<style>
.ui-autocomplete-loading { background: white url('images/ui-anim_basic_16x16.gif') right center no-repeat; }
</style>
<script>
$(function() {
		function split( val ) {
			return val.split( /,\s*/ );
		}
		
		function extractLast( term ) {
			return split( term ).pop();
		}
		
		$( "#keywords" ).autocomplete({
		source: function( request, response ) {
					$.getJSON( "getTerm.php?ontology=" + $( "#ontology" ).val(), {
						term: extractLast( request.term )
					}, response );
				},								  
		minLength: 3,
		select: function( event, ui ) {
			var params = ui.item.id.split( /:::/ );
			window.location = "/browser/rdf.php?o=" + params.shift() + "&iri=" + params.shift();
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
<div id="topbanner"><a href="/index.php" style="font-size:36px; color:#111144; text-decoration:none"><img src="images/logo.gif" alt="Logo" width="280" height="49" border="0"></a></div>
<div id="topnav"><a href="index.php" class="topnav">Home</a><a href="introduction.php" class="topnav">Introduction</a><a href="ontostat/index.php" class="topnav">Statistics</a><a href="sparql/index.php" class="topnav">SPARQL</a><a href="ontobeep/index.php" class="topnav">Ontobeep</a><a href="tutorial/index.php" class="topnav">Tutorial</a><a href="faqs.php" class="topnav">FAQs</a><a href="references.php" class="topnav">References</a><a href="links.php" class="topnav">Links</a><a href="contactus.php" class="topnav">Contact</a><a href="acknowledge.php" class="topnav">Acknowledge</a><a href="news.php" class="topnav">News</a></div>
<div id="mainbody">
<!-- InstanceBeginEditable name="Main" -->
<h3 class="head3_darkred">Welcome to Ontobee!</h3>
<?php 
$vali=new Validation($_REQUEST);

$GALAXY_URL = $vali->getInput('GALAXY_URL', 'GALAXY_URL', 0, 100, true);

if ($GALAXY_URL!='') {
	if (isset($_SESSION)) {
		$_SESSION['GALAXY_URL']=$GALAXY_URL;
	}
}


$db = ADONewConnection($driver);
$db->Connect($host, $username, $password, $database);

$strSql="select * from ontology where loaded='y' and to_list='y' order by ontology_abbrv";
$rs=$db->Execute($strSql);


?>

<p><strong>Ontobee: </strong>A <a href="http://www.w3.org/DesignIssues/LinkedData.html">linked data</a> server designed for ontologies. Ontobee is aimed to  facilitate ontology data sharing, visualization, query, integration, and analysis. Ontobee dynamically <a href="http://www.w3.org/2001/tag/doc/httpRange-14/2007-05-31/HttpRange-14">dereferences</a> and presents individual ontology term URIs by   generating RDF/XML source code for <a href="http://en.wikipedia.org/wiki/Semantic_Web">Semantic Web</a> applications, and providing a  well-structured HTML web page for user-friendly visualization in a web browser. Ontobee is the default linked data server for most <a href="http://obofoundry.org/">OBO Foundry and OBO Library ontologies</a>. </p>

<form action="browser/search.php" method="get" id="form_term_search">
<select name="ontology" id="ontology">
<option value="" selected>Please select an ontology</option>
<?php 
foreach ($rs as $row){

?>
<option value="<?php echo $row['ontology_abbrv']?>"><?php echo $row['ontology_fullname']?> (<?php echo $row['ontology_abbrv']?>)</option>
<?php 
}
?>
</select>
<div class="ui-widget">
	<strong>
	<label for="keywords">Keywords: </label>
	</strong>
<input id="keywords" name="keywords" size="30" />
    <input type="submit" name="Submit2" value="Search terms" />
</div>
</form>

<form action="redirect.php" method="get" id="form_term_redirect" style="margin-top:16px;">
  <div class="ui-widget">Jump to http://purl.obolibrary.org/obo/
    <input id="shortid" name="shortid" size="20" />
    <input type="submit" name="Submit" value="Go" />
</div>
</form>
<p>Currently Ontobee has been tested for the following ontologies: </p>
<table border="0" cellpadding="2" style="border:1px #A0A0A4 solid">
<tr>
<td bgcolor="#158AFF" align="center"><strong>No.</strong></td>
<td bgcolor="#158AFF" align="center"><strong>Ontology Prefix</strong></td>
<td bgcolor="#158AFF" align="center"><strong>Ontology Full Name</strong></td>
<td bgcolor="#158AFF" align="center"><strong>List of Terms</strong></td>
</tr>
<?php 
	$i=0;
	$bgcolor = 'bgcolor="#BBDDFF"';
	foreach ($rs as $row){
		$bgcolor = $bgcolor=='' ? 'bgcolor="#BBDDFF"' : '';
		$i++;
?>
<tr>
<td <?php echo $bgcolor?> align="center"><strong><?php echo $i?></strong></td>
<td <?php echo $bgcolor?>><a href="browser/index.php?o=<?php echo $row['ontology_abbrv']?>"><?php echo $row['ontology_abbrv']?></a></td>
<td <?php echo $bgcolor?>><?php echo $row['ontology_fullname']?></td>
<td align="center" <?php echo $bgcolor?>><a href="listTerms.php?ontology=<?php echo $row['ontology_abbrv']?>"><img src="images/Excel_2010_Logo.png" alt="Excel format" width="16" height="16" border="0"></a></td>
</tr>
<?php 
	}
?>
</table>
<p><strong>Please cite the following reference for Ontobee: </strong></p>
<p>Xiang Z, Mungall C, Ruttenberg A, He Y. <a href="Ontobee_ICBO-2011_Proceeding.pdf">Ontobee: A Linked Data Server and Browser for Ontology Terms</a>. <em>Proceedings of the 2nd International Conference on Biomedical Ontologies (ICBO)</em>, July 28-30, 2011, Buffalo, NY, USA. Pages 279-281. URL: <a href="http://ceur-ws.org/Vol-833/paper48.pdf">http://ceur-ws.org/Vol-833/paper48.pdf</a>. </p>
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
