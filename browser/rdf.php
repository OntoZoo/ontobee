<?php header('Link: <http://www.ontobee.org/browser/about.php?'.$_SERVER['QUERY_STRING'].'>; rel="canonical"');
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
Purpose: Ontobee ontology browsing section rdf query page.
*/

$show_html=false;
if (strpos($_SERVER['HTTP_ACCEPT'], 'application/rdf+xml')===false && strpos($_SERVER['HTTP_ACCEPT'], 'application/xml')===false && strpos($_SERVER['HTTP_ACCEPT'], '*/*')===false) {
	$show_html=true;
}
elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'bot') || strpos($_SERVER['HTTP_USER_AGENT'], 'spider') || strpos($_SERVER['HTTP_USER_AGENT'], 'crawl') || strpos($_SERVER['HTTP_USER_AGENT'], 'search')) {
	$show_html=true;
}

if ($show_html) {
	include('about.php');
}
else {
	 $time_start = microtime(true);
	
	include_once('../inc/Classes.php');
	
	$vali=new Validation($_REQUEST);
	
	$o = $vali->getInput('o', 'Ontology', 1, 60, true);
	if ($vali->getErrorMsg()=='') {
		$settings = getSettings($o);
	
		$num_queries = 0;
		
		if (!isset($settings['ns_main'])) {
			$vali->concatError('Invalid ontology specified.');
		}
	}
	
	if ($vali->getErrorMsg()=='') {
		$iri = $vali->getInput('iri', 'Term IRI', 2, 100, true);
		
		if (substr($iri, 0, 7)!='http://') $iri='http://purl.obolibrary.org/obo/'.$iri;
		
		if ($vali->getErrorMsg()=='') {
			$iri = myUrlDecode($iri);
			$outputNSs = array();
			$outputNSs['http://www.w3.org/1999/02/22-rdf-syntax-ns#'] = 'rdf';
			$outputNSs['http://www.w3.org/2002/07/owl#'] = 'owl';
//			$outputNSs['http://purl.obolibrary.org/obo/'] = 'obo';
			$outputNSs['http://www.w3.org/2000/01/rdf-schema#'] = 'rdfs';
			$outputNSs['http://purl.org/dc/elements/1.1/'] = 'dc';
//			$outputNSs['http://protege.stanford.edu/plugins/owl/protege#'] = 'protege';
		
			$strOutput='';
			$related_terms=array();
			
			
			$a_fields=array();
			$querystring = "
DEFINE sql:describe-mode \"CBD\" 
DESCRIBE <$iri> 

FROM <{$settings['ns_main']}>";

//print("<!--$querystring-->");
			
			$fields = array();
			$fields['default-graph-uri'] = '';
			$fields['format'] = 'application/rdf+xml';
			$fields['debug'] = 'on';
			$fields['query'] = $querystring;
	
			$a_fields['rdf_term_cbd'] = $fields;
	
			$querystring = "
DEFINE sql:describe-mode \"CBD\" 
DESCRIBE <{$settings['ns_main_original']}>

FROM <{$settings['ns_main']}>";

//print("<!--$querystring-->");
			
			$fields = array();
			$fields['default-graph-uri'] = '';
			$fields['format'] = 'application/rdf+xml';
			$fields['debug'] = 'on';
			$fields['query'] = $querystring;
	
			$a_fields['rdf_onto_cbd'] = $fields;
	
			$a_query_results = curl_multi_post_contents($settings['remote_store_endpoint'], $a_fields);
			
			$rdf_term_cbd = $a_query_results['rdf_term_cbd'];
			$rdf_onto_cbd = $a_query_results['rdf_onto_cbd'];
			//print("<!-- $rdf_term_cbd -->");
			//print("<!-- $rdf_onto_cbd -->");
	
			if (preg_match_all('/<rdf:Description.*?rdf:Description>/', $rdf_term_cbd, $matches)){
				if (preg_match_all('/<rdf:Description.*?rdf:Description>/', $rdf_term_cbd . $rdf_onto_cbd, $matches)){
					$output='';
					foreach ($matches[0] as $line) {
						if (strpos($line, '<owl:imports')===false) $output .= "$line\n";
					}
					
					$strOutput .= "\n$output";
					
					//get labels and types for related terms
					preg_match_all('/resource="(.+?)"/', $output, $matches);
					foreach ($matches[1] as $match) {
						$related_terms[$match] = 'NA';
					}
					
					//get labels and types for related terms
					preg_match_all('/<n0pred:(\S+) xmlns:n0pred="(\S+)"/', $output, $matches);
					for ($mi=0; $mi<sizeof($matches[1]); $mi++) {
						$related_terms[$matches[2][$mi].$matches[1][$mi]] = 'NA';
					}
				}
				
				
				//retrieve label & type for related terms.
				if (!empty($related_terms)) {
		
					$querystring = "
	CONSTRUCT {?s <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> ?o}
	
	FROM <{$settings['ns_main']}>
	
	WHERE { ?s <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> ?o.
	FILTER (?s in(<http://null>, <".join('>, <', array_keys($related_terms)).">))
	}
	";
			
					$fields = array();
					$fields['default-graph-uri'] = '';
					$fields['format'] = 'application/rdf+xml';
					$fields['debug'] = 'on';
					$fields['query'] = $querystring;
					
					$a_fields['query_key_related_types'] = $fields;
		
		
					$querystring = "
	CONSTRUCT {?s <http://www.w3.org/2000/01/rdf-schema#label> ?o}
	
	FROM <{$settings['ns_main']}>
	
	WHERE { ?s <http://www.w3.org/2000/01/rdf-schema#label> ?o.
	FILTER (?s in(<http://null>, <".join('>, <', array_keys($related_terms)).">))
	}
	";
		
					$fields = array();
					$fields['default-graph-uri'] = '';
					$fields['format'] = 'application/rdf+xml';
					$fields['debug'] = 'on';
					$fields['query'] = $querystring;
					
					$a_fields['query_key_related_labels'] = $fields;
		
		
					$a_query_results = curl_multi_post_contents($settings['remote_store_endpoint'], $a_fields);
			
					$related_types_results = $a_query_results['query_key_related_types'];
					$related_labels_results = $a_query_results['query_key_related_labels'];
		
		//print("<!--$related_types_results-->\n");
					if (preg_match_all('/<rdf:Description.*?rdf:Description>/', $related_types_results, $matches)){
						foreach ($matches[0] as $line) {
							$strOutput .= "\n$line";
						}
					}
					if (preg_match_all('/<rdf:Description.*?rdf:Description>/', $related_labels_results, $matches)){
						foreach ($matches[0] as $line) {
							$strOutput .= "\n$line";
						}
					}
				}
			
			
			
	//		error_log($strOutput, 3, '/tmp/error.log');
			
				
				foreach ($outputNSs as $NSTmp => $prefixTmp) {
					$strOutput=str_replace('xmlns:'.$prefixTmp.'="'.$NSTmp.'"', '', $strOutput);
					$strOutput=str_replace('rdf:resource="'.$NSTmp, 'rdf:resource="&'.$prefixTmp.';', $strOutput);
					$strOutput=str_replace('rdf:about="'.$NSTmp, 'rdf:about="&'.$prefixTmp.';', $strOutput);
					$strOutput=str_replace('rdf:datatype="'.$NSTmp, 'rdf:datatype="&'.$prefixTmp.';', $strOutput);
				}
				
				
				$rdf_header='<?xml version="1.0" encoding="utf-8" ?>
<?xml-stylesheet type="text/xsl" href="/browser/xslt.php?o='.$o.'&amp;iri='. myUrlEncode($iri) .'"?>			
  <!DOCTYPE rdf:RDF [
  <!ENTITY obo \'http://purl.obolibrary.org/obo/\'>
  <!ENTITY owl \'http://www.w3.org/2002/07/owl#\'>
  <!ENTITY rdfs \'http://www.w3.org/2000/01/rdf-schema#\'>
  <!ENTITY rdf \'http://www.w3.org/1999/02/22-rdf-syntax-ns#\'>
  ]>
  

<rdf:RDF';

				foreach ($outputNSs as $NSTmp => $prefixTmp) {
					$rdf_header .= "
  xmlns:$prefixTmp=\"$NSTmp\"" ;
				}
				
				$strOutput = "$rdf_header >
<owl:Ontology rdf:about=\"".preg_replace('/\/obo\//', "/obo/$o/about/", $iri)."\">
</owl:Ontology>
$strOutput
</rdf:RDF>";
		
				if (strpos($_SERVER['HTTP_ACCEPT'], 'application/rdf+xml')!==false) {
					header("Content-type: application/rdf+xml");
				}
				elseif(strpos($_SERVER['HTTP_ACCEPT'], 'application/xml')!==false) {
					header("Content-type: application/xml");
				}
				else {
					header("Content-type: text/xml");
				}
				
				$filename=createRandomPassword();
//				file_put_contents("/tmp/$filename.in", $strOutput);
				$strOutput = curl_post_contents("http://141.211.109.48:8080/Ontobee/Reformat.jsp", array("ifile"=>$strOutput));
//				$strOutput = file_get_contents("http://141.211.109.48:8080/Ontobee/Reformat.jsp?ifile=/tmp/$filename.in");
				
//				$strOutput = file_get_contents("/tmp/$filename.out");
				

				$strOutput = str_replace('<rdf:RDF', '<?xml-stylesheet type="text/xsl" href="/browser/xslt.php?o='.$o.'&amp;iri='. myUrlEncode($iri) .'"?>
<rdf:RDF', $strOutput);
				$strOutput = str_replace('</owl:Ontology>', '    <owl:imports rdf:resource="'.$settings['ns_main_original'].'"/>
    </owl:Ontology>', $strOutput);
				
				print($strOutput);

//<owl:imports rdf:resource=\"".$settings['ns_main_original']."\"/>


			}
			else {
				// can not find this term
				header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
				include('404.php');
			}
		
		}
		else {
			header("Content-type: text/xml");
			$strOutput = '<?xml version="1.0"?>
	<error>Incorrect ontology or term URL</error>';
			print($strOutput);
		}
		
		
	}
	else {
		header("Content-type: text/xml");
		$strOutput = '<?xml version="1.0" encoding="utf-8" ?>
	<error>Ontology not specified or not supported</error>';
		
		print($strOutput);
	}

}
?>