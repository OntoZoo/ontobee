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
Purpose: List the term result for the query.
-->

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html><!-- InstanceBegin template="/Templates/default.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<!-- InstanceBeginEditable name="doctitle" -->
<title>Ontodog: Ontodog-based Community View Generator</title>
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

<p><span class="header_darkred">Retrieving Results</span></p>
<?php 
/**
 * Author: Zuoshuang Xiang
 * The University Of Michigan
 * He Group
 * Date: 2011-10-28
 *
 * Provide template based on selected ontology 
 */
 
require_once('inc/Classes.php');
require_once('inc/PHPExcel.php');


$vali=new Validation($_REQUEST);

$ontology= $vali->getInput('ontology', 'Ontology', 2, 128);


$a_signature_term_type=array();
$a_signature_term_type[]='http://www.w3.org/2002/07/owl#ObjectProperty';
$a_signature_term_type[]='http://www.w3.org/2002/07/owl#DatatypeProperty';
$a_signature_term_type[]='http://www.w3.org/2002/07/owl#TransitiveProperty';
$a_signature_term_type[]='http://www.w3.org/2002/07/owl#SymmetricProperty';
$a_signature_term_type[]='http://www.w3.org/2002/07/owl#FunctionalProperty';
$a_signature_term_type[]='http://www.w3.org/2002/07/owl#InverseFunctionalProperty';



if ($vali->getErrorMsg()=='') {
	$strSql= "select * from ontology where ontology_abbrv='$ontology'";
	$db = ADONewConnection($driver);
	$db->Connect($host, $username, $password, $database);
	
	$rs = $db->Execute($strSql);
	if (!$rs->EOF) {
		$filename=$ontology;
		
//		print(filemtime("userfiles/$filename.xls") - time());
		
		if (!file_exists("userfiles/$filename.xls") || time() -filemtime("userfiles/$filename.xls")  > 60*60*8) {
	
			$end_point=$rs->Fields('end_point');
			$graph_url=$rs->Fields('ontology_graph_url');
		
		
			$querystring = "
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX owl: <http://www.w3.org/2002/07/owl#>

SELECT *

FROM <$graph_url>

WHERE
{
	?s rdf:type owl:Class .
	?s rdfs:label ?l .
	FILTER (isIRI(?s)).
	OPTIONAL {?s <http://purl.obolibrary.org/obo/IAO_0000118> ?alt_names}.
	OPTIONAL {?s <http://purl.obolibrary.org/obo/IAO_0000115> ?definition}.
	OPTIONAL {?s rdfs:subClassOf ?pTerm.
		FILTER (isIRI(?pTerm)).
		?pTerm rdfs:label ?pLabel}.
	
}

limit 10000
";


//print($querystring);

			$tmp_results = json_query($querystring, $end_point);
			
			$terms=array();
			foreach($tmp_results as $result) {
				$terms[$result['s']]=$result;
			}
			
	
			$querystring = "
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX owl: <http://www.w3.org/2002/07/owl#>

SELECT *

FROM <$graph_url>

WHERE
{
	?s rdf:type ?o .
	?s rdfs:label ?l .
	FILTER (?o in(<".join('>, <', $a_signature_term_type).">)).
	FILTER (isIRI(?s)).
	OPTIONAL {?s <http://purl.obolibrary.org/obo/IAO_0000118> ?alt_names}.
	OPTIONAL {?s <http://purl.obolibrary.org/obo/IAO_0000115> ?definition}.
	OPTIONAL {?s rdfs:subPropertyOf ?pTerm.
		FILTER (isIRI(?pTerm)).
		?pTerm rdfs:label ?pLabel}.
	
}

limit 10000
";
	
			$tmp_results = json_query($querystring, $end_point);
			
			foreach($tmp_results as $result) {
				$terms[$result['s']]=$result;
			}
			
	
	
			/** PHPExcel */
			require_once 'inc/PHPExcel.php';
			
			/** PHPExcel_Cell_AdvancedValueBinder */
			require_once 'inc/PHPExcel/Cell/AdvancedValueBinder.php';
			
			/** PHPExcel_IOFactory */
			require_once 'inc/PHPExcel/IOFactory.php';
			
			// Set value binder
			PHPExcel_Cell::setValueBinder( new PHPExcel_Cell_AdvancedValueBinder() );
			
			// Create new PHPExcel object
			$objPHPExcel = new PHPExcel();
			
			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(45);
			$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(45);
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(45);
			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(45);
			$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(45);
			$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(100);
			
			
			$styleArray = array('font' => array('bold' => true));
	
			$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray);
			$objPHPExcel->getActiveSheet()->getStyle('B1')->applyFromArray($styleArray);
			$objPHPExcel->getActiveSheet()->getStyle('C1')->applyFromArray($styleArray);
			$objPHPExcel->getActiveSheet()->getStyle('D1')->applyFromArray($styleArray);
			$objPHPExcel->getActiveSheet()->getStyle('E1')->applyFromArray($styleArray);
			$objPHPExcel->getActiveSheet()->getStyle('F1')->applyFromArray($styleArray);
	
			
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0, 1)->setValue("Term IRI");
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1, 1)->setValue("Term label");
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2, 1)->setValue("Parent term IRI");
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(3, 1)->setValue("Parent term label");
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(4, 1)->setValue("Alternative term");
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(5, 1)->setValue("Definition");
	
			$i=2;
			foreach($terms as $term_url => $term) {
				$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0, $i)->setValue($term_url);
				$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1, $i)->setValue($term['l']);
				$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2, $i)->setValue($term['pTerm']);
				$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(3, $i)->setValue($term['pLabel']);
				$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(4, $i)->setValue($term['alt_names']);
				$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(5, $i)->setValue($term['definition']);
				$i++;
			}
			
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel5");
			$objWriter->save('userfiles/'.$filename.'.xls');
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
			$objWriter->save('userfiles/'.$filename.'.xlsx');
		}
		?>
<p style="font-size:larger">File generated.</p>
<p style="font-size:larger">Download the  file in <a href="<?php echo 'userfiles/'.$filename.'.xls'?>">Excel 5 format</a> or <a href="<?php echo 'userfiles/'.$filename.'.xlsx'?>">Excel 2007 format</a>.</p>
		<?php 
	}
}

if ($vali->getErrorMsg()!='') {
?>
<p style="color:#FF1F55; font-size:larger">Error: <?php echo $vali->getErrorMsg()?></p>
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
