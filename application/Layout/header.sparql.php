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
 * @file header.sparql.php
 * @author Yongqun Oliver He
 * @author Zuoshuang Allen Xiang
 * @author Edison Ong
 * @since Sep 9, 2015
 * @comment 
 */

if ( !$this ) {
	exit( header( 'HTTP/1.0 403 Forbidden' ) );
}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<?php 
if ( !isset( $title ) ) {
	echo '<title>Ontobee</title>';
} else {
	echo "<title>$title</title>";
}
?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
<link rel="shortcut icon" href="/favicon.ico" />
<link href="<?php echo SITEURL; ?>public/css/main.css" rel="stylesheet" type="text/css"/>
<script src="<?php echo SITEURL; ?>public/js/sparql/toolkit/loader.js"></script>

<style>
.ui-autocomplete-loading { background: white url('<?php echo SITEURL; ?>public/images/ui-anim_basic_16x16.gif') right center no-repeat; }
</style>
<script type="text/javascript">
var toolkitPath = "<?php echo SITEURL; ?>public/js/sparql/toolkit"; 
var featureList = ["tab", "ajax2", "combolist", "window", "tree", "grid", "dav", "xml"];
</script>
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
<div id="topbanner">
<a href="<?php echo SITEURL; ?>" style="font-size:36px; color:#111144; text-decoration:none">
<img src="<?php echo SITEURL; ?>public/images/logo.gif" alt="Logo" width="280" height="49" border="0">
</a>
</div>
<div id="topnav">
<a href="<?php echo SITEURL; ?>" class="topnav">Home</a>
<a href="<?php echo SITEURL; ?>introduction" class="topnav">Introduction</a>
<a href="<?php echo SITEURL; ?>ontostat" class="topnav">Statistics</a>
<a href="<?php echo SITEURL; ?>sparql" class="topnav">SPARQL</a>
<a href="<?php echo SITEURL; ?>ontobeep" class="topnav">Ontobeep</a>
<a href="<?php echo SITEURL; ?>annotate" class="topnav">Annotator</a>
<a href="<?php echo SITEURL; ?>tutorial" class="topnav">Tutorial</a>
<a href="<?php echo SITEURL; ?>faqs" class="topnav">FAQs</a>
<a href="<?php echo SITEURL; ?>references" class="topnav">References</a>
<a href="<?php echo SITEURL; ?>links" class="topnav">Links</a>
<a href="<?php echo SITEURL; ?>contactus" class="topnav">Contact</a>
<a href="<?php echo SITEURL; ?>acknowledge" class="topnav">Acknowledge</a>
<a href="<?php echo SITEURL; ?>news" class="topnav">News</a>
</div>
<div id="mainbody">