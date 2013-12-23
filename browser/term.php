<?php 
/*
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
*/
/*
Author: Zuoshuang Xiang
The University Of Michigan
He Group
Date: June 2008 - March 2013
Purpose: Ontobee ontology browsing section ontology terms browsing page.
*/

ini_set('memory_limit', '2048M');
include_once('../inc/Classes.php');
$vali=new Validation($_REQUEST);

$o = $vali->getInput('o', 'Ontology', 1, 60, true);
$l = $vali->getInput('l', 'First Letter', 0, 60, true);
$currPage = $vali->getNumber('currPage', 'Current Page', 0, 5);
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
<link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/dojo/1.5/dijit/themes/tundra/tundra.css">
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/dojo/1.5/dojo/dojo.xd.js" djConfig="parseOnLoad: true"></script>
<script type="text/javascript">
	dojo.require("dijit.form.FilteringSelect");
	dojo.require("dojo.data.ItemFileReadStore");

	function termchange1 () {
		var termids=document.getElementsByName("termid1_0");
		document.getElementById("termid1").value= termids[0].value;
	}
	
	function checkOntology1() {
		dojo.byId('termsearch1').value = '';
		dojo.byId('termid1').value = '';
		dojo.byId("termid1").onchange();
	}

	dojo.addOnLoad(function() {document.body.className="tundra"});


	dojo.addOnLoad(function() {

            var filteringSelect = new dijit.form.FilteringSelect({
                id: "termsearch1",
                name: "termid1_0",
                value: "",
                store: new dojo.data.ItemFileReadStore({
					url: '../getTerm.php'
					}),
				autoComplete: true,
				style: "width: 270px;",
				onKeyUp: function(key) {
						if(dojo.byId('termsearch1').value.trim().length>=3) {
							dijit.byId('termsearch1').attr('store', new dojo.data.ItemFileReadStore({url: '../getTerm.php?keywords='+dojo.byId('termsearch1').value+'&ontology='+dojo.byId('ontology').value}));
						}
					},
				onChange: function () {
					var termids=document.getElementsByName("termid1_0");
					var tokens=termids[0].value.split(/[\/#]/);
					dojo.byId("termid1").value= tokens[tokens.length-1];
					dojo.byId("termid1").onchange();
					},
                searchAttr: "name"
            },
            "termsearch1");
	});
	
	function toggleBtn(index) {
		if (dojo.byId("termid"+index).value=='') {
			dojo.byId("btnShowterm"+index).disabled="true";
		}
		else {
			dojo.byId("btnShowterm"+index).disabled=false;
		}
	}

	function showterm (index) {
		var termids=document.getElementsByName("termid"+ index +"_0");
		var tokens = termids[0].value.split(";");
		
		var term_to_add = tokens[2].replace(/#/, "%23");
		
		var o = tokens[1];
		
		var url='rdf.php?o='+o+'&iri='+term_to_add;
		
		window.open(url,'','');
	}
	

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

	$db = ADONewConnection($driver);
	$db->Connect($host, $username, $password, $database);
	$strSql="select * from ontology where loaded='y' order by ontology_fullname";
	$rs=$db->Execute($strSql);

	
?>
  
<h3 align="center"><a href="index.php?o=<?php echo $o?>"> <?php print($settings['ontology_fullname']); ?></a></h3>


<?php 	$time_start = microtime(true);
	
	$num_queries = 0;
	
	
	$iri = $vali->getInput('iri', 'Term IRI', 2, 100, true);
	$graph = $vali->getInput('graph', 'Graph', 0, 100, true);
	
	if ($vali->getErrorMsg()=='') {
		$params="&o=$o&iri=".myUrlEncode($iri);
		$iri = myUrlDecode($iri);
		
		$first_letter_filter='';
		if (preg_match('/[A-Z]/', $l)) {
			$first_letter_filter='.
FILTER regex(?o, "^'.$l.'")';
		}
		elseif ($l=='*') {
			$first_letter_filter='.
FILTER regex(?o, "^[^A-Z]")';
		}
		$querystring = "
PREFIX rdf: <{$settings['ns_rdf']}>
SELECT distinct ?s ?o
";	

		if ($graph!='') {
			$querystring .= "
FROM <$graph>
";
		}
		else {
			$querystring .= "
FROM <{$settings['ns_main']}>
";
		}
		
		$querystring .= "
WHERE {
	{ ?s rdf:type <$iri> .
	FILTER (isIRI(?s)).
	OPTIONAL { ?s rdfs:label  ?o } 
	}
";




		if ($iri==$settings['ns_owl'].'ObjectProperty') {
			$querystring .= "
	UNION {?s rdf:type owl:TransitiveProperty.
	FILTER (isIRI(?s)).
	OPTIONAL { ?s rdfs:label  ?o } 
	} 
	UNION {?s rdf:type owl:SymmetricProperty.
	FILTER (isIRI(?s)).
	OPTIONAL { ?s rdfs:label  ?o } 
	}
";
		}
		
		$querystring .= "

}

LIMIT 10000
";

//	print($querystring);
		$results = json_query($querystring);
		
	
		$final_results = array();
	
		if(!empty($results)){
			foreach ($results as $result) {
				$sub=$result['s'];
				
				$obj=isset($result['o']) ? $result['o'] : '';
	
				$final_results[$sub] = $obj!='' ? $obj : getShortTerm($sub);
				if ($final_results[$sub]=='') $final_results[$sub]='NA';
			}
		}
		else {
		}
		
		asort($final_results);
	
		if(!empty($final_results)) {
			$numOfRecords = sizeof($final_results);
			
			$results_by_letter=array();
			if ($numOfRecords>500) {
				foreach ($final_results as $sub => $obj) {
					$first_letter=substr($obj, 0,1);
					if (preg_match('/[a-z]/i', $first_letter)) {
						$results_by_letter[strtoupper($first_letter)][$sub]=$obj;
					}
					else {
						$results_by_letter['*'][$sub]=$obj;
					}
				}
			}
			
			$recordsPerPage = 500;
			$numOfPage = ceil($numOfRecords / $recordsPerPage);
			
			if ($currPage == '' || $currPage > $numOfPage || $numOfPage < 1) {
				$currPage = 1;
			}
?>

<p> <?php echo $numOfRecords?> terms(s) returned. <?php if (sizeof($results)==10000) {?>
  <span class="darkred">There are still more terms in this ontology.</span>
  <?php }?> Please click a term for detail information. </p>
<table border="0">
  <tr>
    <td bgcolor="#F5FAF7" class="tdData" style="padding-left:20px; padding-right:20px ">
<strong>Term Type:</strong> <?php print(getShortTerm($iri)); ?>
    </td>
    <td bgcolor="#F5FAF7" class="tdData" style="padding-left:20px; padding-right:20px ">
<strong>Record:</strong> <?php echo (($currPage-1) * $recordsPerPage + 1)?> to <?php echo ($currPage * $recordsPerPage) < $numOfRecords? ($currPage * $recordsPerPage) :$numOfRecords?> of <?php echo $numOfRecords?> Records.	</td>
    <td bgcolor="#F5FAF7" class="tdData" style="padding-left:20px; padding-right:20px ">
<strong>Page:</strong>
<?php echo $currPage?> of
<?php echo $numOfPage?>,
<?php 
			if ($currPage > 1) {
?>
<a href="?currPage=1<?php echo $params?>">First</a> <?php 
			}
			else {
?>
First
<?php 
			}
?>,
<?php 
			if ($currPage > 1) {
?>
<a href="?currPage=<?php echo $currPage-1?><?php echo $params?>">Previous </a><?php 
			}
			else {
?>
Previous
<?php 
			}
?>,
<?php 
			if ($currPage < $numOfPage) {
?>
<a href="?currPage=<?php echo $currPage+1?><?php echo $params?>">Next</a> <?php 
			}
			else {
?>
Next
<?php 
			}
?>,
<?php 
			if ($currPage < $numOfPage) {
?>
<a href="?currPage=<?php echo $numOfPage?><?php echo $params?>">Last</a> <?php 
			}
			else {
?>
Last
<?php 
			}
?>	</td>
    </tr>
</table>

<?php 
			if ($numOfRecords>500) {
				foreach ($results_by_letter as $key => $tmp) {
					if ($l!=$key) {
			
?>
<a href="term.php?l=<?php echo $key?><?php echo $params?>" style="font-size:14px; font-weight:bold; margin-right:12px;"><?php echo $key?></a> 
<?php 
					}
					else {
?>
<span style="font-size:14px; font-weight:bold; margin-right:12px;"><?php echo $key?></span> 
<?php 
					}
				}
			}
?>
<div style="background-color:#EAF1F2; border:#99CCFF 1px solid; margin-top:4px; margin-bottom:12px">
	<ul>
	<?php 			if ($l!='') {
				$final_results=$results_by_letter[$l];
			}
			else {
				$final_results=array_slice($final_results, $recordsPerPage*($currPage-1), $recordsPerPage);
			}
			foreach($final_results as $sub => $obj) {
?>
	<li><a href="/browser/rdf.php?o=<?php print($o); ?>&amp;iri=<?php print(myUrlEncode($sub)); ?>"><?php print(htmlentities($obj)); ?></a></li>
	<?php 			}
?>
	</ul>
</div>
<?php 		}
	}
	else {
		include('../inc/input_error.php');
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
