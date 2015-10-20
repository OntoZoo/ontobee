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
 * @file OntologyRemap.php
 * @author Edison Ong
 * @author Bin Zhao
 * @since Oct 19, 2015
 * @comment 
 */
 
namespace Hook;

use PDO;
use SimpleXMLElement;

class OntologyRemap {
	public static function ogg( &$ontAbbr, &$termIRI ) {
		if ( !is_null( $termIRI ) ) {
			if ( substr ( $termIRI, 0, 36 ) == 'http://purl.obolibrary.org/obo/OGG_2' ||
				substr ( $termIRI, 0, 36 ) == 'http://purl.obolibrary.org/obo/OGG_1' ) {
				$geneID = substr ( $termIRI, - 6 );
				$geneID = intval ( $geneID );
				
				$options = array(
						PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
						PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
				);
				$db = new PDO( DB_DRIVER . ':host=' . DB_HOST . ';dbname=' . DB_SCHEMA, DB_USERNAME, DB_PASSWORD, $options );
				
				$sql = "SELECT * FROM ontobee.term_mapping WHERE c_taxonID = $geneID";
				$query = $db->prepare( $sql );
				$query->execute();
				$results = $query->fetchAll();
				foreach( $results as $result ) {
					if ( isset( $result->c_target_ontology ) && $result->c_target_ontology != '') {
						if ( $ontAbbr == $result->c_origin_ontology ) {
							$ontAbbr = $result->c_target_ontology;
						}
					}
				}				
			} else if ( substr ( $termIRI, 0, 36 ) == 'http://purl.obolibrary.org/obo/OGG_3' ) {
				$geneID = substr ( $termIRI, - 9 );
				$geneID = intval ( $geneID );
				
				$xml = new SimpleXMLElement( file_get_contents ( "http://eutils.ncbi.nlm.nih.gov/entrez/eutils/efetch.fcgi?db=gene&id=$geneID&retmode=xml" ) );
				$taxonResult = $xml->xpath( 'Entrezgene/Entrezgene_source/BioSource/BioSource_org/Org-ref/Org-ref_db/Dbtag/Dbtag_tag/Object-id/Object-id_id' );
				
				$taxonID = $taxonResult [0];
				$taxonID = intval ( $taxonID );
				
				$options = array(
						PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
						PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
				);
				$db = new PDO( DB_DRIVER . ':host=' . DB_HOST . ';dbname=' . DB_SCHEMA, DB_USERNAME, DB_PASSWORD, $options );
				
				$sql = "SELECT * FROM ontobee.term_mapping WHERE c_taxonID = $taxonID";
				$query = $db->prepare( $sql );
				$query->execute();
				$results = $query->fetchAll();
				foreach( $results as $result ) {
					if ( isset( $result->c_target_ontology ) && $result->c_target_ontology != '') {
						if ( $ontAbbr == $result->c_origin_ontology ) {
							$ontAbbr = $result->c_target_ontology;
						}
					}
				}
			} else if (substr ( $termIRI, 0, 36 ) == 'http://purl.obolibrary.org/obo/OGG_0') {
				$ontAbbr = "OGG";
			}
		}
	}
}



?>