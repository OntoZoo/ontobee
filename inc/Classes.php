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
Purpose: Ontobee utility function definition page.
*/


//ini_set("display_errors", "1"); 
//ini_set("display_startup_errors", "1"); 
//error_reporting(E_ALL);

ini_set('memory_limit', '1024M');

//Globle Varibles.
$driver = 'mysql';
$host = '123.456.789.01';
$username = 'username';
$password = 'password';
$database = 'database';

include('adodb5/adodb-errorhandler.inc.php');
include('adodb5/adodb.inc.php');

$default_end_point="http://sparql.hegroup.org/sparql";

$ref_types = array('Class', 'ObjectProperty', 'DatatypeProperty', 'AnnotationProperty');
$ontologyAnnotations = array();
$ontologyAnnotations[] = 'http://purl.org/dc/elements/1.1/title';
$ontologyAnnotations[] = 'http://purl.org/dc/elements/1.1/subject';
$ontologyAnnotations[] = 'http://purl.org/dc/elements/1.1/description';
$ontologyAnnotations[] = 'http://purl.org/dc/elements/1.1/format';
$ontologyAnnotations[] = 'http://purl.org/dc/elements/1.1/date';
$ontologyAnnotations[] = 'http://purl.org/dc/elements/1.1/creator';
$ontologyAnnotations[] = 'http://purl.org/dc/elements/1.1/contributor';
$ontologyAnnotations[] = 'http://purl.org/dc/elements/1.1/rights';

$preferred_label_url='http://purl.obolibrary.org/obo/IAO_0000589';


function getSettings($ontology_abbrv) {
	global $driver, $host, $username, $password, $database;
	$settings = array();
	$strSql= "select * from ontology where ontology_abbrv='$ontology_abbrv'";
	$db = ADONewConnection($driver);
	$db->Connect($host, $username, $password, $database);
	
	$row = $db->GetRow($strSql);
	if(!empty($row)) {
		$settings['ontology_name'] = $row['ontology_abbrv'];
		$settings['ontology_fullname'] = $row['ontology_fullname'];
		$settings['ns_main'] = $row['ontology_graph_url'];
		$settings['ns_main_original'] = $row['ontology_url'];
		$settings['remote_store_endpoint'] = $row['end_point'];
	}
	
	$settings['ns_rdf'] = 'http://www.w3.org/1999/02/22-rdf-syntax-ns#';
	$settings['ns_rdfs'] = 'http://www.w3.org/2000/01/rdf-schema#';
	$settings['ns_owl'] = 'http://www.w3.org/2002/07/owl#';
	
	$settings['base_oboInOwl'] = 'http://www.geneontology.org/formats/oboInOwl#';
	
	
	$settings['core_terms'] = array();
	$strSql= "select * from key_terms where ontology_abbrv='$ontology_abbrv' ORDER BY term_label";
	$results = $db->GetAll($strSql);
	
	if ($results!=false) {
		foreach($results as $result) {
			if ($result['is_root']==1) {
				$settings['core_terms'][$result['term_url']]=$result['term_label'] .' (root class)';
			}
			else {
				$settings['core_terms'][$result['term_url']]=$result['term_label'];
			}
		}
	}
	
	
	return($settings);
}

function makeLink($input) {
	return(preg_replace('/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/', '<a href="$0">$0</a>', $input));
}


function UTF_to_Unicode($input, $array=False) {

 $bit1  = pow(64, 0);
 $bit2  = pow(64, 1);
 $bit3  = pow(64, 2);
 $bit4  = pow(64, 3);
 $bit5  = pow(64, 4);
 $bit6  = pow(64, 5);
 
 $value = '';
 $val   = array();
 
 for($i=0; $i< strlen( $input ); $i++){
 
     $ints = ord ( $input[$i] );
    
     $z = ord ( $input[$i] );

     if( $ints >= 0 && $ints <= 127 ){
        // 1 bit
        //$value .= '&#'.($z * $bit1).';';
		$value .= htmlentities($input[$i]);
        $val[]  = $value;
     }
     if( $ints >= 192 && $ints <= 223 ){
        $y = ord ( $input[$i+1] ) - 128;
        // 2 bit
        $value .= '&#'.(($z-192) * $bit2 + $y * $bit1).';';
        $val[]  = $value;
     }   
     if( $ints >= 224 && $ints <= 239 ){
        $y = ord ( $input[$i+1] ) - 128;
        $x = ord ( $input[$i+2] ) - 128;
        // 3 bit
        $value .= '&#'.(($z-224) * $bit3 + $y * $bit2 + $x * $bit1).';';
        $val[]  = $value;
     }    
     if( $ints >= 240 && $ints <= 247 ){
        $y = ord ( $input[$i+1] ) - 128;
        $x = ord ( $input[$i+2] ) - 128;
        $w = ord ( $input[$i+3] ) - 128;
        // 4 bit
        $value .= '&#'.(($z-240) * $bit4 + $y * $bit3 + $x * $bit2 + $w * $bit1).';';
        $val[]  = $value;       
     }    
     if( $ints >= 248 && $ints <= 251 ){
        $y = ord ( $input[$i+1] ) - 128;
        $x = ord ( $input[$i+2] ) - 128;
        $w = ord ( $input[$i+3] ) - 128;
        $v = ord ( $input[$i+4] ) - 128;
        // 5 bit
        $value .= '&#'.(($z-248) * $bit5 + $y * $bit4 + $x * $bit3 + $w * $bit2 + $v * $bit1).';';
        $val[]  = $value;  
     }
     if( $ints == 252 && $ints == 253 ){
        $y = ord ( $input[$i+1] ) - 128;
        $x = ord ( $input[$i+2] ) - 128;
        $w = ord ( $input[$i+3] ) - 128;
        $v = ord ( $input[$i+4] ) - 128;
        $u = ord ( $input[$i+5] ) - 128;
        // 6 bit
        $value .= '&#'.(($z-252) * $bit6 + $y * $bit5 + $x * $bit4 + $w * $bit3 + $v * $bit2 + $u * $bit1).';';
        $val[]  = $value;
     }
     if( $ints == 254 || $ints == 255 ){
       echo 'Wrong Result!<br>';
     }
    
 }
 
 if( $array === False ){
    return $unicode = $value;
 }
 if($array === True ){
     $val     = str_replace('&#', '', $value);
     $val     = explode(';', $val);
     $len = count($val);
     unset($val[$len-1]);
    
     return $unicode = $val;
 }
 
}

function myUrlDecode ($url) {
	return(urldecode($url));
	//return(preg_replace('/_p_/', '#', $url));
}

function myUrlEncode ($url) {
//	return(urlencode($url));
	return(preg_replace('/#/', '%23', $url));
}


function getRDFjsonDetail($results, $term_iri, $tmp_iri, $a_label, $link = false) {
	global $o, $settings;

	$array_return = array();
	$result_main=$results[$term_iri];
	if(isset($result_main[$tmp_iri])) {
		foreach ($result_main[$tmp_iri] as $result) {
			if ($link && $result['type']=='uri') {
				$array_return[]="<a oncontextmenu=\"return false;\" href=\"/browser/rdf.php?o=$o&amp;iri=" . myUrlEncode($result['value']) . "\">" . $a_label[$result['value']] . "</a>";
			}
			elseif ($result['type']=='uri'){
				if ($tmp_iri=='http://xmlns.com/foaf/0.1/depicted_by') {
						$array_return[]=$result['value'];
				}
				elseif (isset($a_label[$result['value']])) {
					$array_return[]=$a_label[$result['value']];
				}
				else {
					$array_return[]=getShortTerm($result['value']);
				}
			}
			elseif ($result['type']=='bnode') {
				$tmp_result=$results[$result['value']];
				foreach ($tmp_result['http://www.w3.org/2000/01/rdf-schema#label'] as $tmp_result) {
					if (preg_match('/\w+/', $tmp_result['value'])) {
						$array_return[]=UTF_to_Unicode($tmp_result['value']);
					}
				}
			}
			else{
				if (preg_match('/\w+/', $result['value'])) {
					$array_return[]=UTF_to_Unicode($result['value']);
				}
			}
		}
	}
	
	return join(', ', array_unique($array_return));
}

//deprecated
function objectToArray( $object )
{
	if( !is_object( $object ) && !is_array( $object ) )
	{
		return $object;
	}
	if( is_object( $object ) )
	{
		$object = get_object_vars( $object );
	}
	return array_map( 'objectToArray', $object );
}


function getRDFjsonEquivalent($results, $tmp_iri, $a_label, $link = true) {
	global $o, $settings;
	$strEquivalents = '';
	
	$obj_equiv=array();
	
	$url_intersectionOf = $settings['ns_owl'] . "intersectionOf";
	$url_unionOf = $settings['ns_owl'] . "unionOf";
	$url_first = $settings['ns_rdf'] . "first";
	$url_rest = $settings['ns_rdf'] . "rest";
	$url_onclass = $settings['ns_owl'] . "onClass";
	$url_onProperty = $settings['ns_owl'] . "onProperty";
	$url_someValuesFrom = $settings['ns_owl'] . "someValuesFrom";
	$url_allValuesFrom = $settings['ns_owl'] . "allValuesFrom";
	$url_complementOf = $settings['ns_owl'] . "complementOf";
	$url_hasValue = $settings['ns_owl'] . "hasValue";
	$url_qualifiedCardinality = $settings['ns_owl'] . "qualifiedCardinality";
	
	
	if(isset($results[$tmp_iri])) {
		$tmp_results = $results[$tmp_iri];
		if (isset($tmp_results[$url_intersectionOf])) {
			$obj_equiv['r']='and';
			$obj_equiv['v'][]=getRDFjsonEquivalent($results, $tmp_results[$url_intersectionOf][0]['value'], $a_label, $link);
			
		}elseif (isset($tmp_results[$url_unionOf])) {
			$obj_equiv['r']='or';
			$obj_equiv['v'][]=getRDFjsonEquivalent($results, $tmp_results[$url_unionOf][0]['value'], $a_label, $link);
		}
		elseif (isset($tmp_results[$url_complementOf])) {
			$obj_equiv['r']='not';
			if ($tmp_results[$url_complementOf][0]['type']=='uri') {
				$obj_equiv['v'][]=$tmp_results[$url_complementOf][0]['value'];
			}
			else {
				$obj_equiv['v'][]=getRDFjsonEquivalent($results, $tmp_results[$url_complementOf][0]['value'], $a_label, $link);
			}
		}
		elseif (isset($tmp_results[$url_first])) {
			if ($tmp_results[$url_first][0]['type']=='uri') {
				$obj_equiv['v'][]=$tmp_results[$url_first][0]['value'];
			}
			else {
				$obj_equiv['v'][]=getRDFjsonEquivalent($results, $tmp_results[$url_first][0]['value'], $a_label, $link);
			}
		}
		elseif (isset($tmp_results[$url_onProperty])) {
			$obj_equiv['v'][]=$tmp_results[$url_onProperty][0]['value'];
		}
		
		if (isset($tmp_results[$url_rest])) {
			if ($tmp_results[$url_rest][0]['type']=='uri') {
				$obj_equiv['v'][]=$tmp_results[$url_rest][0]['value'];
			}
			else {
				$obj_equiv['v'][]=getRDFjsonEquivalent($results, $tmp_results[$url_rest][0]['value'], $a_label, $link);
			}
		}
		elseif (isset($tmp_results[$url_onclass])) {
			$obj_equiv['r']='some';
			if ($tmp_results[$url_rest][0]['type']=='uri') {
				$obj_equiv['v'][]=$tmp_results[$url_onclass][0]['value'];
			}
			else {
				$obj_equiv['v'][]=getRDFjsonEquivalent($results, $tmp_results[$url_onclass][0]['value'], $a_label, $link);
			}
		}
		elseif (isset($tmp_results[$url_someValuesFrom])) {
			$obj_equiv['r']='some';
			if ($tmp_results[$url_someValuesFrom][0]['type']=='uri') {
				$obj_equiv['v'][]=$tmp_results[$url_someValuesFrom][0]['value'];
			}
			else {
				$obj_equiv['v'][]=getRDFjsonEquivalent($results, $tmp_results[$url_someValuesFrom][0]['value'], $a_label, $link);
			}
		}
		elseif (isset($tmp_results[$url_allValuesFrom])) {
			$obj_equiv['r']='only';
			if ($tmp_results[$url_allValuesFrom][0]['type']=='uri') {
				$obj_equiv['v'][]=$tmp_results[$url_allValuesFrom][0]['value'];
			}
			else {
				$obj_equiv['v'][]=getRDFjsonEquivalent($results, $tmp_results[$url_allValuesFrom][0]['value'], $a_label, $link);
			}
		}
		elseif (isset($tmp_results[$url_hasValue])) {
			$obj_equiv['r']='value';
			if ($tmp_results[$url_hasValue][0]['type']=='uri') {
				$obj_equiv['v'][]=$tmp_results[$url_hasValue][0]['value'];
			}
			else {
				$obj_equiv['v'][]=getRDFjsonEquivalent($results, $tmp_results[$url_hasValue][0]['value'], $a_label, $link);
			}
		}
		
		if (isset($tmp_results[$url_qualifiedCardinality])) {
		}
		
	}
	
//	print("<!--");
//	print_r($obj_equiv);
//	print("-->");

	return($obj_equiv);
	
}

function printRDFjsonEquivalent($obj_equiv, $a_label, $relation = 'and', $last_re='and', $first_re=true) {
	global $o;
	$strOut='';
	
	$bracket=false;
	if (isset($obj_equiv['r'])) {
		if ($first_re) {
			$relation=$obj_equiv['r'];
			$last_re=$relation;
		}
		else {
			$last_re=$relation;
			$relation=$obj_equiv['r'];
		}
		
		$first_re=false;
		
		if ($relation!=$last_re) $bracket=true;
	}
	
	if (isset($obj_equiv['v'])) {
		$v=$obj_equiv['v'];
		
		if ($bracket) $strOut.='(';

		if ($relation=='not') {
			$strOut .= "$relation ";
		}
		
		if(!is_array($v[0])) {
			if ($v[0]!='http://www.w3.org/1999/02/22-rdf-syntax-ns#nil') $strOut .= '<a oncontextmenu="return false;" href="/browser/rdf.php?o='.$o.'&amp;iri=' . myUrlEncode($v[0]) . '">' . UTF_to_Unicode($a_label[$v[0]]) . '</a>';
		}
		else $strOut .= printRDFjsonEquivalent($v[0], $a_label, $relation, $last_re, $first_re);
		
		if (isset($v[1])) {
			if(!is_array($v[1])) {
				if ($v[1]!='http://www.w3.org/1999/02/22-rdf-syntax-ns#nil') {
					$strOut .= " $relation " . '<a oncontextmenu="return false;" href="/browser/rdf.php?o='.$o.'&amp;iri=' . myUrlEncode($v[1]) . '">' . UTF_to_Unicode($a_label[$v[1]]) . '</a>';
				}
			}
			else $strOut .= " $relation ". printRDFjsonEquivalent($v[1], $a_label, $relation, $last_re, $first_re);
	
		}
		
		if ($bracket) $strOut.=')';
	}
	
	return($strOut);
}

function getShortTerm($term) {
	if (preg_match('/^http/', $term)) {
		$tmp_array = preg_split('/[#\/]/', $term);
		return(UTF_to_Unicode(array_pop($tmp_array)));
	}
	else {
		return(UTF_to_Unicode($term));
	}
}
	


//Reformat a construction SPARQL query to make it shorter by using prefix.
function formatQuery($strSparql) {
	list($str_prefix, $str_body) = preg_split('/CONSTRUCT/', $strSparql);
	$array_prefix=array();
	preg_match_all('/<(http:\/\/.+?)>/', $str_body, $matches);
	if (sizeof($matches[1])>0) {
		$i=0;
		foreach ($matches[1] as $match) {
			$pos = strrpos($match, '#');
			if ($pos===false) {
				$pos = strrpos($match, '/');
			}
			if($pos!==false) {
				$tmp_prefix = substr($match, 0, $pos+1);
				if (!in_array($tmp_prefix, $array_prefix)) {
					$i++;
					$array_prefix['p_'. $i]=$tmp_prefix;
				}
			}
		}
		
		arsort($array_prefix);
		foreach($array_prefix as $prefix => $tmp_iri) {
			$str_prefix = "prefix $prefix: <$tmp_iri>\n" . $str_prefix;
			$str_body = str_replace('<'.$tmp_iri, $prefix.':', $str_body);
			
		}
		
		$str_body = preg_replace('/(p_\d+:\S*)>/', '$1', $str_body);
		
	}
	return(preg_replace('/[\r\n]+/', "\n", $str_prefix.'CONSTRUCT'.$str_body));
}

//Use curl to do a post request
function curl_post_contents($url, $fields) {
	//open connection
	$ch = curl_init();
	$fields_string = http_build_query($fields);
	
	//set the url, number of POST vars, POST data
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_POST,count($fields));
	curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
	
	//execute post
	$result = curl_exec($ch);
	
	if ($result===false) {
		error_log("curl error: " . curl_error($ch));
	}
	
	//close connection
	curl_close($ch);
	
	return($result);
}

//Use curl to do multithreading post requests
function curl_multi_post_contents($url, $a_fields) {
	// create the multi curl handle
	$mh = curl_multi_init();
	$handles = array();
	$a_result = array();
	
	foreach($a_fields as $fields_key => $fields){
		// create a new single curl handle
		$ch = curl_init();
		
		$fields_string = http_build_query($fields);
		// setting several options like url, timeout, returntransfer
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, count($fields));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		
		// add this handle to the multi handle
		curl_multi_add_handle($mh,$ch);
		
		// put the handles in an array to loop this later on
		$handles[$fields_key] = $ch;
	}
	
	// execute the multi handle
	$running=NULL;
	do {
		curl_multi_exec($mh,$running);
		// added a usleep for 0.01 seconds to reduce load
		usleep (1000);
	} while ($running > 0);
	
	// get the content of the urls (if there is any)
	foreach($handles as $fields_key => $handle)	{
		// get the content of the handle
		$a_result[$fields_key] = curl_multi_getcontent($handle);
		
		// remove the handle from the multi handle
		curl_multi_remove_handle($mh,$handle);
	}
	
	// close the multi curl handle to free system resources
	curl_multi_close($mh);	
	
	return($a_result);
}

function parse_json_query($str_json){
	$json = json_decode($str_json, true);
	$results = array();
	if (isset($json['results']['bindings'])){
		foreach ($json['results']['bindings'] as $binding) {
			$result = array();
			foreach ($binding as $key=>$value) {
				$result[$key] = $value['value'];
			}
			$results[] = $result;
		}
	}

	return($results);
}



function json_query($querystring, $endpoint=NULL){
	global $settings;
	$fields = array();
	$fields['default-graph-uri'] = '';
	$fields['format'] = 'application/sparql-results+json';
	$fields['debug'] = 'on';
	$fields['query'] = $querystring;
	
	if ($endpoint==NULL) {$endpoint = $settings['remote_store_endpoint']; }

//	print($querystring);
	
	//error_log($querystring, 3, '/tmp/error.log');
	$json = curl_post_contents($endpoint, $fields);
//	print($json);
	return (parse_json_query($json));
}

//Generate a random string of certain length
function createRandomPassword($chars = "abcdefghijkmnopqrstuvwxyz023456789", $length=8) {
	srand((double)microtime()*1000000);
	$i = 0;
	$pass = '' ;
	while ($i < $length) {
		$num = rand() % strlen($chars);
		$tmp = substr($chars, $num, 1);
		$pass = $pass . $tmp;
		$i++;    
	}    
	return $pass;
}

class Validation{
	var $request;
	var $strErrorMsg;
	
	function Validation($request){
		$this->request=$request;
	}
	
	function getInput($strInput,$strCName, $intMin, $intMax, $toTrim = true) {
		$blflag=True;
		if (array_key_exists($strInput, $this->request)) {
			$strTmp=$this->request[$strInput]; 
		}
		else {
			$strTmp='';
		}
		
		if ($toTrim) {
			$strTmp=trim($strTmp);
		}


		if (strlen($strTmp)>$intMax) {
			$blflag=False;
			$this->strErrorMsg=$this->strErrorMsg . $strCName . " too long (maximum length: $intMax)<br>" ;
		}
		
		if (strlen($strTmp)<$intMin) {
			if ($strTmp=='') {
				$blflag=False;
				$this->strErrorMsg=$this->strErrorMsg . $strCName . " is required<br>" ;
			}
			else {
				$blflag=False;
				$this->strErrorMsg=$this->strErrorMsg . $strCName . " too short (minimum length: $intMin)<br>" ;
			}
		}

		return $strTmp;
	}

	function getArray($strInput ,$strCName, $isRequired = true) {
		$blflag=True;
		if (array_key_exists($strInput, $this->request)) {
			$strTmp=$this->request[$strInput]; 
		}
		else {
			$strTmp=array();
		}

		if (empty($strTmp) && $isRequired==True)
			$this->strErrorMsg=$this->strErrorMsg . $strCName . " is required<br>" ;
		return $strTmp;
	}

	function getAccount($strInput,$strCName, $isRequired = true) {
		$intMax=20;
		$intMin=3;
		$blflag=True;
		if (array_key_exists($strInput, $this->request)) {
			$strTmp=$this->request[$strInput]; 
		}
		else {
			$strTmp='';
		}
		$strTmp=trim($strTmp);

		if (strlen($strTmp)>$intMax) {
			$blflag=False;
			$this->strErrorMsg=$this->strErrorMsg . $strCName . " too long (maximum length: $intMax)<br>" ;
		}
		
		if (strlen($strTmp)<$intMin) {
			$blflag=False;
			$this->strErrorMsg=$this->strErrorMsg . $strCName . " too short (minimum length: $intMin)<br>" ;
		}

		$regex = '/^[^ ,\']+$/';
		if (preg_match($regex, $strTmp)) {
			$blflag = true;
		}
		else {
			$blflag = false;
			$this->strErrorMsg=$this->strErrorMsg . $strCName . " contains illegal charactors (Space, comma or apostrophe) <br>" ;
		}

		return $strTmp;
	}

	function getPhone($strInput, $strCName, $isRequired = false) {
		$blflag=True;
		$intMax=20;
		$intMin=5;

		if (array_key_exists($strInput, $this->request)) {
			$strTmp=$this->request[$strInput]; 
		}
		else {
			$strTmp='';
		}
		$strTmp=trim($strTmp);


		$strAllow="0123456789-,.() ";

		if ($isRequired==True || strlen($strTmp)>0 ){
			if (strlen($strTmp)>$intMax) {
				$blflag=False;
				$this->strErrorMsg=$this->strErrorMsg . $strCName . " too long (maximum length: $intMax)<br>" ;
			}
			
			if (strlen($strTmp)<$intMin) {
				$blflag=False;
				$this->strErrorMsg=$this->strErrorMsg . $strCName . " too short (minimum length: $intMin)<br>" ;
			}
	
			$regex = '/^[\d -_,\(\)\.]+$/';
			if (preg_match($regex, $strTmp)) {
				$blflag = true;
			}
			else {
				$blflag = false;
				$this->strErrorMsg=$this->strErrorMsg . $strCName . " contains illegal charactors (allowed charactors are: \"0123456789 -,.()\") <br>" ;
			}
		}

		return $strTmp;
	}

	function getEmail($strInput,$strCName,$isRequired = true) {
		$blflag=True;
		$intMax=50;
		$intMin=5;
		if (array_key_exists($strInput, $this->request)) {
			$strTmp=$this->request[$strInput]; 
		}
		else {
			$strTmp='';
		}
		$strTmp=trim($strTmp);

		if ($isRequired==True || strlen($strTmp)>0 ){
			if (strlen($strTmp)>$intMax) {
				$blflag=False;
				$this->strErrorMsg=$this->strErrorMsg . $strCName . " too long (maximum length: $intMax)<br>" ;
			}
			
			if (strlen($strTmp)<$intMin) {
				$blflag=False;
				$this->strErrorMsg=$this->strErrorMsg . $strCName . " too short (minimum length: $intMin)<br>" ;
			}
	
			//$regex = '/^[\d-_\w\.@]{5,10}$/';
			$regex = '&^(?:                                               # recipient:
         ("\s*(?:[^"\f\n\r\t\v\b\s]+\s*)+")|                          #1 quoted name
         ([-\w!\#\$%\&\'*+~/^`|{}]+(?:\.[-\w!\#\$%\&\'*+~/^`|{}]+)*)) #2 OR dot-atom
         @(((\[)?                     #3 domain, 4 as IPv4, 5 optionally bracketed
         (?:(?:(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:[0-1]?[0-9]?[0-9]))\.){3}
               (?:(?:25[0-5])|(?:2[0-4][0-9])|(?:[0-1]?[0-9]?[0-9]))))(?(5)\])|
         ((?:[a-z0-9](?:[-a-z0-9]*[a-z0-9])?\.)*[a-z](?:[-a-z0-9]*[a-z0-9])?))  #6 domain as hostname
         $&xi';
			if (preg_match($regex, $strTmp)) {
				$blflag = true;
			}
			else {
				$blflag = false;
				$this->strErrorMsg=$this->strErrorMsg . $strCName . " does not look like an real eamil address<br>" ;
			}
		}

		return $strTmp;
	}

	function getZipCode($strInput, $strCName, $isRequired = false) {
		$blflag=True;
		$intMax=10;
		$intMin=5;
		if (array_key_exists($strInput, $this->request)) {
			$strTmp=$this->request[$strInput]; 
		}
		else {
			$strTmp='';
		}
		$strTmp=trim($strTmp);

		if ($isRequired==True || strlen($strTmp)>0 )	{
			if (strlen($strTmp)>$intMax) {
				$blflag=False;
				$this->strErrorMsg=$this->strErrorMsg . $strCName . " too long (maximum length: $intMax)<br>" ;
			}
			
			if (strlen($strTmp)<$intMin) {
				$blflag=False;
				$this->strErrorMsg=$this->strErrorMsg . $strCName . " too short (minimum length: $intMin)<br>" ;
			}

			if (preg_match('/^[\w-]+$/', $strTmp)) {
				$blflag = true;
			}
			else {
				$blflag = false;
				$this->strErrorMsg=$this->strErrorMsg . $strCName . " contains illegal charactors (allowed charactors are: \"abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-\") <br>" ;
			}
		}

		if ($blflag==False)
			$this->strErrorMsg=$this->strErrorMsg . $strCName . "<br>" ;
		return $strTmp;
	}

	function getNumber($strInput, $strCName, $intMin, $intMax, $isRequired = false) {
		$blflag=True;
		if (array_key_exists($strInput, $this->request)) {
			$strTmp=$this->request[$strInput]; 
		}
		else {
			$strTmp='';
		}
		$strTmp=trim($strTmp);
		
		if ($isRequired==True || strlen($strTmp)>0 )	{
			if (strlen($strTmp)>$intMax) {
				$blflag=False;
				$this->strErrorMsg=$this->strErrorMsg . $strCName . " too long (maximum length: $intMax)<br>" ;
			}
			elseif (strlen($strTmp)<$intMin) {
				$blflag=False;
				$this->strErrorMsg=$this->strErrorMsg . $strCName . " too short (minimum length: $intMin)<br>" ;
			}
			else {
				$strReg = '/^[\d-\.]+$/';
				if (!preg_match($strReg, $strTmp)) {
					$blflag = false;
					$this->strErrorMsg=$this->strErrorMsg . $strCName . " contains illegal charactors<br>" ;
				}
			}
		}

		return $strTmp;
	}
  
	function concatError($strError) {
		$this->strErrorMsg .= $strError . "<br>" ;
	}
  
	function getErrorMsg(){
		return $this->strErrorMsg;
	}
}
?>