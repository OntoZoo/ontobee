<?php

/**
 * Copyright © 2015 The Regents of the University of Michigan
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 * http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and limitations under the License.
 * 
 * For more information, questions, or permission requests, please contact:
 * Yongqun “Oliver” He - yongqunh@med.umich.edu
 * Unit for Laboratory Animal Medicine, Center for Computational Medicine & Bioinformatics
 * University of Michigan, Ann Arbor, MI 48109, USA
 * He Group:  http://www.hegroup.org
 */

/**
 * @file term.list.php
 * @author Edison Ong
 * @since Sep 8, 2015
 * @comment 
 */

use View\Helper;
 
if ( !$this ) {
	exit(header('HTTP/1.0 403 Forbidden'));
}

$termURL = SITEURL . "ontology/$ontAbbr?iri=";
$pageURL = SITEURL . "{$GLOBALS['controller']}/catalog/$ontAbbr?";
$letterURL = SITEURL ."{$GLOBALS['controller']}/catalog/$ontAbbr?";

if ( isset( $termIRI ) && !is_null( $termIRI ) && $termIRI != '' ) {
	$pageURL .= '&iri=' . Helper::encodeURL( $termIRI );
	$letterURL .= '&iri=' . Helper::encodeURL( $termIRI );
}

if ( isset( $prefix ) && !is_null( $prefix ) && $prefix != '' ) {
	$pageURL .= '&prefix=' . $prefix;
	$letterURL .= '&prefix=' . $prefix;
}

if ( isset( $listMaxTerms ) && !is_null( $listMaxTerms ) && $listMaxTerms != '' ) {
	$pageURL .= '&max=' . $listMaxTerms;
	$letterURL .= '&max=' . $listMaxTerms;
}

if ( isset( $letter ) && !is_null( $letter ) && $letter != '' ) {
	$pageURL .= '&letter=' . urldecode( $letter );
}

$pageURL .= '&page=';
$letterURL .= '&letter=';


$html =
<<<END
<p>{$GLOBALS['call_function']( sizeof( $terms ) )} terms(s) returned
END;

if ( sizeof( $terms ) >= 10000 ) {
	$html .=
<<<END
</br>
<span class="darkred">There are still more terms in this ontology.  Please click a term for detail information. </p></span>
END;
}

$html .=
<<<END
<table border="0">
<tr>
<td bgcolor="#F5FAF7" class="tdData" style="padding-left:20px; padding-right:20px ">
<strong>Term Type:</strong>
{$GLOBALS['call_function']( array_search( $termIRI, $GLOBALS['ontology']['type'] ) )}
</td>
<td bgcolor="#F5FAF7" class="tdData" style="padding-left:20px; padding-right:20px ">
<strong>Record:</strong>
{$GLOBALS['call_function']( ( ( $page - 1 ) * $listMaxTerms + 1 ) )} to 
END;

if ( ( $page * $listMaxTerms ) < sizeof( $terms ) ) {
	$html .= $page * $listMaxTerms;
} else {
	$html .= sizeof( $terms );
}

$html .=
<<<END
 of {$GLOBALS['call_function']( sizeof( $terms ) )} Records</td>
<td bgcolor="#F5FAF7" class="tdData" style="padding-left:20px; padding-right:20px ">
<strong>Page:</strong> $page of $pageCount, 
END;

if ( $page > 1 ) {
	$html .=
<<<END
<a href="{$pageURL}1">First</a> <a href="$pageURL{$GLOBALS['call_function']( ( $page - 1 ) )}">Previous</a> 
END;
} else {
	$html .=
<<<END
First Previous 
END;
}

if ( $page < $pageCount ) {
	$html .=
	<<<END
<a href="$pageURL{$GLOBALS['call_function']( ( $page + 1 ) )}">Next</a> <a href="{$pageURL}$pageCount">Last</a> 
END;
} else {
	$html .=
	<<<END
Next Last 
END;
}

$html .=
<<<END
</td>
<td>Show 
<select id="list-max" name="max">
END;

foreach ( $GLOBALS['ontology']['term_max_per_page'] as $max ) {
	if ( $max  == $listMaxTerms ) {
		$html .=
<<<END
<option value="$max" selected>$max</option>
END;
	} else {
		$html .=
		<<<END
<option value="$max">$max</option>
END;
	}
}

$html .=
<<<END
</select>
 Records Per Page
</td>
</tr>
</table>
END;

foreach ( array_keys( $letters ) as $l ) {
	if ( $l == $letter ) {
		$html .=
<<<END
<span style="font-size:14px; font-weight:bold; margin-right:12px;">$l</span>
END;
	} else {
		$html .=
<<<END
<a href=
"$letterURL{$GLOBALS['call_function']( urlencode( $l ) )}" 
style="font-size:14px; font-weight:bold; margin-right:12px;">$l</a>
END;
	}
}

$html .=
<<<END
<div class="section">
<ul>
END;

$termsToShow = array_slice(
	$terms,
	( $page - 1 ) * $listMaxTerms,
	$listMaxTerms
);

foreach ( $termsToShow as $showIRI => $showLabel ) {
	$html .=
<<<END
<li><a href="$termURL{$GLOBALS['call_function']( Helper::encodeURL( $showIRI ) )}">
{$GLOBALS['call_function']( htmlentities( $showLabel ) )}</a></li>
END;
}

$html .=
<<<END
</ul>
</div>
END;

echo Helper::tidyHTML( $html );

?>