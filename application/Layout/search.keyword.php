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
 * @file search.keyword.php
 * @author Edison Ong
 * @since Sep 4, 2015
 * @comment 
 */
 
if (!$this) {
	exit(header('HTTP/1.0 403 Forbidden'));
}

?>

<script src="<?php echo SITEURL; ?>public/js/ontobee.autocomplete.js"></script>

<!-- Ontobee home keyword search -->
<form action="<?php echo SITEURL; ?>search" method="get" id="keyword-search">

<?php

if ( isset( $ontologies ) && empty( $ontology ) ) {
	echo '<select name="ontology" id="ontology"><option value="" selected>Please select an ontology (optional)</option>';
END;
	foreach ( $ontologies as $ontology ) {
		echo '<option value="' .
			$ontology->ontology_abbrv .
			'">' .
			$ontology->ontology_fullname .
			' (' .
			$ontology->ontology_abbrv .
			')</option>';
	}
	echo '</select>';
} else {
	echo "<input name=\"ontology\" id=\"ontology\" type=\"hidden\" value=\"$ontology->ontology_abbrv\">";
}

?>

<div class="ui-widget">
	<strong>
	<label for="keywords">Keywords: </label>
	</strong>
<input id="keywords" name="keywords" size="30" />
    <input type="submit" name="submit" value="Search terms" />
</div>
</form>