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
 * @file compare.php
 * @author Edison Ong
 * @since Mar 28, 2017
 * @comment 
 */
 
if ( !$this ) {
	exit( header( 'HTTP/1.0 403 Forbidden' ) );
}

?>

<?php require TEMPLATE . 'header.ontobeep.php'; ?>

<h3><span class="head3_darkred">Ontobeep</span></h3>

<div><a href="javascript:itemTree.expandAll();">Expand One Level Down</a> <span style="margin-left:120px">
<a href="<?php echo SITEURL; ?>ontobeep/statistic?ontologies=<?php echo join(',', $ontologies)?>">Statistics</a></span></div>

<div dojoType="custom.TreeQueryReadStore" url="<?php echo SITEURL; ?>api/ontobeep" jsId="treeStore"></div>

<div dojoType="custom.HtmlTree" store="treeStore" label="Root" labelAttr="label" jsId="itemTree" persist=false>
	<script type="dojo/method" event="getItemChildren" args="parentItem, onComplete">
		if (parentItem == null) {
			// get top level nodes for this term_url
			treeStore.fetch({ term_url: '', onComplete: onComplete});
		}
		else{
			treeStore.fetch({ term_url: treeStore.getValue(parentItem, 'term_url'), onComplete: onComplete});
		}
    </script>
</div>


<?php require TEMPLATE . 'footer.default.dwt.php'; ?>