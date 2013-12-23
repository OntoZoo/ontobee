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

<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php 
/**
* Author: Zuoshuang Xiang
* The University Of Michigan
* He Group
* Date: 2011-10-28
*
* Provide template based on selected ontology 
*/

require_once('inc/Classes.php');

$strSql= "select * from ontology where  loaded='y'";
$db = ADONewConnection($driver);
$db->Connect($host, $username, $password, $database);

$rs = $db->Execute($strSql);
foreach ($rs as $row) {
?>
<sitemap>
<loc>http://www.ontobee.org/sitemap.php?o=<?php echo $row['ontology_abbrv'] ?></loc>
<?php 
	if ($row['last_update']!='') {
?>
<lastmod><?php echo str_replace(' ', 'T', $row['last_update'])?>-05:00</lastmod>
<?php 
	}
?>
</sitemap>
<?php 
}
?>
</sitemapindex>