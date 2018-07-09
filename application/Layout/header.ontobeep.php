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
 * @file header.ontobeep.php
 * @author Edison Ong
 * @since Mar 28, 2017
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

<link rel="stylesheet" type="text/css" href="<?php echo SITEURL; ?>public/js/dijit/themes/tundra/tundra.css"/>

<script src="<?php echo SITEURL; ?>public/js/dojo/dojo.js" djConfig="isDebug: true, parseOnLoad: true"></script>

<script type="text/javascript">
    dojo.require("dijit.Tree");
    dojo.require("dojox.data.QueryReadStore");
	dijit.Tree._createTreeNode = function(
		/*Object*/
		args) {
			var tnode = new dijit._TreeNode(args);
			tnode.labelNode.innerHTML = args.label;
			return tnode;
		}


	dojo.provide("custom.TreeQueryReadStore");
	dojo.declare("custom.TreeQueryReadStore", dojox.data.QueryReadStore, {
		fetch: function(request) {
			//console.log('fetching plugin: ' + request.term_url);
			request.serverQuery = {termIRI: request.term_url, ontologies: '<?php echo join(',', $ontologies)?>', method: 'getChildren'};

			// Call superclasses' fetch
			return this.inherited("fetch", arguments);
		}
	});

	dojo.provide("custom.HtmlTree");
	dojo.declare("custom.HtmlTree", dijit.Tree, {
		_createTreeNode: function(args) {
			var tnode = new dijit._TreeNode(args);
			tnode.labelNode.innerHTML = args.label;
			return tnode;
		},
		
		expandAll: function() {
			// summary:
			//     Expand all nodes in the tree
			// returns:
			//     Deferred that fires when all nodes have expanded
	
			var _this = this;
	
			function expand(node) {
				_this._expandNode(node);
	
				var childBranches = dojo.filter(node.getChildren() || [], function(node) {
					return node.isExpandable;
				});
	
				var def = new dojo.Deferred();
				defs = dojo.map(childBranches, expand);
			}
			return expand(this.rootNode);
		}
	});
	
	dojo.addOnLoad( function() { document.body.className = "tundra"; });
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