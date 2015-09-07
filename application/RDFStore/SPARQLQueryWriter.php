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
 * @file SPARQLQueryWriter.php
 * @author Edison Ong
 * @since Sep 6, 2015
 * @comment 
 */
 
namespace RDFStore;


class SPARQLSyntaxWritter {
	public static function writeSelectBlankNode( $graph, $subjectIRI, $propertyIRI ) {
		$query =
<<<END
SELECT ?node FROM <$graph> WHERE {
    {
        <$subjectIRI> <$propertyIRI> ?node .
        FILTER ( isBLANK( ?node ) )
    }
}
END;
		return $query;
	}
	
	public static function writeSelectAxiom( $prefixNS, $graph, $termIRI ) {
		$prefix = self::writePrefix( $prefixNS );
		$query =
<<<END
DEFINE sql:describe-mode "CBD"
$prefix
DESCRIBE ?axiom FROM <$graph> WHERE {
    {
        <$termIRI> rdfs:subClassOf ?axiom .
        FILTER ( isBLANK( ?axiom ) )
    } UNION {
        <$termIRI> owl:equivalentClass ?axiom .
        FILTER ( isBLANK( ?axiom ) )
    }
}
END;
		return $query;
	}

	protected static function writePrefix( $prefixNS ) {
		$query = '';
		foreach ( $prefixNS as $key => $namespace ) {
			$query .= "PREFIX $key: <$namespace>" . PHP_EOL;
		}
		return $query;
	}

}

?>