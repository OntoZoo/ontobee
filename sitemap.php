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


 echo('<?xml version="1.0" encoding="UTF-8"?>') ;?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"
        xmlns:video="http://www.google.com/schemas/sitemap-video/1.1">
<?php 
/**
* Author: Zuoshuang Xiang
* The University Of Michigan
* He Group
* Date: 2011-10-28
*
* Provide template based on selected ontology 
*/
ini_set('memory_limit', '2048M');

require_once('inc/Classes.php');
$vali=new Validation($_REQUEST);
$o = $vali->getInput('o', 'Ontology', 2, 60, true);
?>
<url><loc>http://www.ontobee.org/browser/index.php?o=<?php echo $o?></loc></url>

<?php

$a_signature_term_type=array();
$a_signature_term_type[]='http://www.w3.org/2002/07/owl#Class';
$a_signature_term_type[]='http://www.w3.org/2002/07/owl#ObjectProperty';
$a_signature_term_type[]='http://www.w3.org/2002/07/owl#DatatypeProperty';
$a_signature_term_type[]='http://www.w3.org/2002/07/owl#TransitiveProperty';
$a_signature_term_type[]='http://www.w3.org/2002/07/owl#SymmetricProperty';
$a_signature_term_type[]='http://www.w3.org/2002/07/owl#FunctionalProperty';
$a_signature_term_type[]='http://www.w3.org/2002/07/owl#InverseFunctionalProperty';

if ($vali->getErrorMsg()=='') {
	
	$strSql= "select * from ontology where  ontology_abbrv='$o'";
	$db = ADONewConnection($driver);
	$db->Connect($host, $username, $password, $database);
	
	$rs = $db->Execute($strSql);
	foreach ($rs as $row) {
		$end_point=$row['end_point'];
		$graph_url=$row['ontology_graph_url'];
	
	
		$querystring = "
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX owl: <http://www.w3.org/2002/07/owl#>

SELECT *

FROM <$graph_url>

WHERE
{
?s rdf:type owl:Class .
FILTER (isIRI(?s)).
}

limit 50000
";
	
	
	//print($querystring);
	
		$tmp_results = json_query($querystring, $end_point);
		
		$terms=array();
		foreach($tmp_results as $result) {
			$terms[$result['s']]=1;
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
FILTER (?o in(<".join('>, <', $a_signature_term_type).">)).
FILTER (isIRI(?s)).
}
limit 5000
";
	
		$tmp_results = json_query($querystring, $end_point);
		
		foreach($tmp_results as $result) {
			$terms[$result['s']]=1;
		}
		
		$i=1;
		foreach ($terms as $term_url => $tmp_value) {
			if ($i<50000) {
?>
<url><loc>http://www.ontobee.org/browser/rdf.php?o=<?php echo $row['ontology_abbrv'] ?>&amp;iri=<?php echo urlencode($term_url) ?></loc></url>
<?php		
			}
			$i++;
		}
	}
}
?>
</urlset>
