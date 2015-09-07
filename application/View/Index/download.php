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
 * @file download.php
 * @author Edison Ong
 * @since Sep 3, 2015
 * @comment 
 */
 
if (!$this) {
	exit(header('HTTP/1.0 403 Forbidden'));
}

?>

<?php require TEMPLATE . 'header.default.dwt.php'; ?>

<h3 class="head3_darkred">Ontobee Download</h3>
<p><strong>Ontobee Source Code at SourceForge (before Dec 2013): </strong></p>
<ul>
  <li>Ontobee project site at SourceForge at  <a href="http://sourceforge.net/projects/ontobee/">http://sourceforge.net/projects/ontobee/</a></li>
  <li>SourceForge Ontobee SVN repository: <a href="http://sourceforge.net/p/ontobee/code/">http://sourceforge.net/p/ontobee/code/</a> </li>
  <li>License: <a href="http://www.apache.org/licenses/LICENSE-2.0.html">Apache License version 2.0</a>.</li>
</ul>
<p><strong>Ontobee Source Code at Github (since Dec 2013): </strong></p>
<ul>
  <li>Ontobee at Github:<a href="https://github.com/ontoden/ontobee"> https://github.com/ontoden/ontobee</a> </li>
  <li>License: <a href="http://www.apache.org/licenses/LICENSE-2.0.html">Apache License version 2.0</a>. </li>
  </ul>
<p><strong>About the License for Ontobee Source Code: </strong></p>
<ul>
  <li>The license owner of the Ontobee source code  is the University of Michigan (UM). Oliver He and his laboratory at UM developed all the Ontobee source code. </li>
  <li>Oliver He is the contact person for this license. Please contact Oliver if you have any questions regarding the  license.</li>
  </ul>
<p><strong>About Apache License:  </strong></p>
<ul>
  <li>Apache License version 2.0: <a href="http://www.apache.org/licenses/LICENSE-2.0.html">http://www.apache.org/licenses/LICENSE-2.0.html</a> </li>
  <li>Wiki information about Apache license: <a href="http://en.wikipedia.org/wiki/Apache_License">http://en.wikipedia.org/wiki/Apache_License</a></li>
  <li>Reference: &quot;The Apache License (v2) - An Overview&quot; (by Rowan Wilson) : <a href="http://oss-watch.ac.uk/resources/apache2">http://oss-watch.ac.uk/resources/apache2</a></li>
  <li>Reference: &quot;Why we chose the Apache License&quot;: <a href="http://www.opscode.com/blog/2009/08/11/why-we-chose-the-apache-license/">http://www.opscode.com/blog/2009/08/11/why-we-chose-the-apache-license/</a></li>
  <li>Apache License is one of the Open Source Licenses:   <a href="http://opensource.org/licenses"> http://opensource.org/licenses</a>. </li>
</ul>

<?php require TEMPLATE . 'footer.default.dwt.php'; ?>