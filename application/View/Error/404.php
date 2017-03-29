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
 * @file 404.php
 * @author Yongqun Oliver He
 * @author Zuoshuang Allen Xiang
 * @author Edison Ong
 * @since Sep 3, 2015
 * @comment 
 */

use Controller\ErrorController;

if (!$this) {
	exit(header('HTTP/1.0 403 Forbidden'));
}

header('HTTP/1.0 404 Not Found');

?>

<?php 
switch ( $code ) {
	case ErrorController::INVALID_URL:
		require TEMPLATE . 'header.default.dwt.php';
		echo "Invalid URL.";
		require TEMPLATE . 'footer.default.dwt.php';
		break;
	case ErrorController::ONTOLOGY_NOT_FOUND:
		require TEMPLATE . 'header.default.dwt.php';
		echo
<<<END
<h1>Ontology Not Found</h1>
<p>We are sorry that your request did not return any expected result.  Here is a list of possible reasons: </p>
<ul>
  <li> Your requested ontology is not listed in OBO Foundry </li>
  <li> Your requested ontology has not been updated from OBO Foundry </li>
  <li> Your requested ontology is not submitted to Ontobee ontology list </li>
  <li> Whoops! Our program has an unhandled and unexpected error in processing your request. </li>
</ul>
<p>If you think that you reached this page in error, or you have any request or concern, you may contact us by: </p>
<ul>
  <li>Submit a bug report or request a new feature at Github: <a href="https://github.com/OntoZoo/ontobee/issues">https://github.com/OntoZoo/ontobee/issues</a> <strong>(Recommended)</strong></li>
 		
  <li> Raise the issue  in the <a href="https://groups.google.com/forum/#!forum/ontobee-discuss">Ontobee-discuss</a> Google Group:
    <ul>
      <li>Leave a message at <a href="https://groups.google.com/forum/#%21forum/ontobee-discuss">https://groups.google.com/forum/#!forum/ontobee-discuss</a>, or </li>
      <li>Directly  send an email to: <a href="mailto:ontobee-discuss@googlegroups.com" target="welcomeMsg" rel="nofollow">ontobee-discuss@googlegroups.com</a>&nbsp;</li>
      <li><strong>Note</strong>:   You are welcome to join the group, and it is open to everyone:-)</li>
    </ul>
  </li>
  
  <li>For OBO Foundry related questions, contact:&nbsp;<a href="http://obofoundry.org/docs/OperationsCommittee.html">http://obofoundry.org/docs/OperationsCommittee.html</a></li>
</ul>
<p>Thank you. </p>
<p>&nbsp;</p>
END;
		require TEMPLATE . 'footer.default.dwt.php';
		break;
	case ErrorController::TERM_NOT_FOUND:
		require TEMPLATE . 'header.default.dwt.php';
		echo
<<<END
<h1>Term Not Found</h1>
<p>We are sorry that your request did not return any expected result.  Here is a list of possible reasons: </p>
<ul>
  <li> Your requested term has been removed from the ontology </li>
  <li> The ontology that contains the term has not been updated </li>
  <li> Whoops! Our program has an unhandled and unexpected error in processing your request. </li>
</ul>
<p>If you think that you reached this page in error, or you have any request or concern, you may contact us by: </p>
<ul>
  <li>Submit a bug report or request a new feature at Github: <a href="https://github.com/OntoZoo/ontobee/issues">https://github.com/OntoZoo/ontobee/issues</a> <strong>(Recommended)</strong></li>
 		
  <li> Raise the issue  in the <a href="https://groups.google.com/forum/#!forum/ontobee-discuss">Ontobee-discuss</a> Google Group:
    <ul>
      <li>Leave a message at <a href="https://groups.google.com/forum/#%21forum/ontobee-discuss">https://groups.google.com/forum/#!forum/ontobee-discuss</a>, or </li>
      <li>Directly  send an email to: <a href="mailto:ontobee-discuss@googlegroups.com" target="welcomeMsg" rel="nofollow">ontobee-discuss@googlegroups.com</a>&nbsp;</li>
      <li><strong>Note</strong>:   You are welcome to join the group, and it is open to everyone:-)</li>
    </ul>
  </li>
  
  <li>For OBO Foundry related questions, contact:&nbsp;<a href="http://obofoundry.org/docs/OperationsCommittee.html">http://obofoundry.org/docs/OperationsCommittee.html</a></li>
</ul>
<p>Thank you. </p>
<p>&nbsp;</p>
END;
		require TEMPLATE . 'footer.default.dwt.php';
		break;
	case ErrorController::INVALID_INPUT:
		require TEMPLATE . 'header.default.dwt.php';
		echo "Invalid input.";
		require TEMPLATE . 'footer.default.dwt.php';
		break;
}
?>