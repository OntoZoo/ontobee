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
 * @file UpdateOntology.php
 * @author Edison Ong
 * @since Oct 1, 2015
 * @comment 
 */

if ( PHP_SAPI == 'cli' ) {
	require( 'Maintenance.php' );
}

Class UpdateOntology extends Maintenance {
	private $tmpDir;
	private $ontology;
	
	public $fileName;
	public $file;
	public $options;
	
	public function __construct( $ontID, $options = array() ) {
		$this->setup();
		$this->openPDOConnection();
		
		$this->tmpDir = SYSTMP . 'rdf' . DIRECTORY_SEPARATOR;
		if ( !file_exists( $this->tmpDir ) ) {
			mkdir( $this->tmpDir );
			chmod( $this->tmpDir, 0777 );
		}
		
		$sql = "SELECT * FROM ontology WHERE id = '$ontID'";
		$query = $this->db->prepare( $sql );
		$query->execute();
		$this->ontology = $query->fetch();
		$this->fileName = $this->ontology->ontology_abbrv;
		$this->options = $options;
	}
	
	public function doUpdate() {
		
		if ( $this->ontology->ontology_url != '' ) {
			$this->download( $this->ontology->ontology_url );
		}
		
		if ( !file_exists( $this->file ) && $this->ontology->download != '' ) {
			$this->download( $this->ontology->download );
		}
		
		if ( !file_exists( $this->file ) && $this->ontology->source != '' ) {
			$this->download( $this->ontology->source );
		}
		
		if ( !file_exists( $this->file ) ) {
			echo "Failed to download owl file.\n";
		} else {
			$md5 =  md5_file( $this->file );
			
			if ( $md5 == $this->ontology->md5 && $this->ontology->loaded == 'y' ) {
				echo "Ontology already up-to-date.\n";
			} else {
				$sql = "UPDATE ontology SET loaded='n' where id = '{$this->ontology->id}'";
				$this->db->query( $sql );
				
				$usr = RDF_USERNAME;
				$pwd = RDF_PASSWORD;
				$isql = RDF_ISQL_COMMAND;
				$cmd =
<<<END
$isql 1111 $usr $pwd verbose=on banner=off prompt=off echo=ON errors=stdout exec="log_enable(3,1);
sparql clear graph <{$this->ontology->ontology_graph_url}>;
DB.DBA.RDF_LOAD_RDFXML_MT (file_to_string_output ('$this->file'), '', '{$this->ontology->ontology_graph_url}');"
END;
				exec( $cmd, $output);
				$output = join( "\n", $output );
				$sql = "UPDATE ontology SET log=" . $this->db->quote( $output ) . " where id = '{$this->ontology->id}'";
				$this->db->query( $sql );
				
				if ( !preg_match( '/Error/', $output ) ) {
					$sql = "UPDATE ontology SET loaded='y', md5='$md5', last_update=now() where id = '{$this->ontology->id}'";
					$this->db->query( $sql );
					echo "$this->fileName loaded\n";
				}
				else {
					echo "$this->fileName failed\n";
				}
				#$this->remove( $this->tmpDir );
				
				#TODO: Special treatment of VO
				/*
				if ($id=='vaccine') {
					$reasoned_file_name = "$tmp_dir/$id"."_reason.owl";
					if(file_exists($reasoned_file_name)) unlink($reasoned_file_name);
					system("java -Xmx8g -cp .:./libs/* org.hegroup.rdfstore.OWLReason $file_name $reasoned_file_name");
					if (file_exists($reasoned_file_name)) {
						$reasoned_graph_url=str_replace('/merged/', '/inferred/', $graph_url);
				
						exec('/data/usr/local/virtuoso/bin/isql 1111 dba dJay0D2a verbose=on banner=off prompt=off echo=ON errors=stdout exec="log_enable(3,1); sparql clear graph <'.$reasoned_graph_url.'>; DB.DBA.RDF_LOAD_RDFXML_MT (file_to_string_output (\''.$reasoned_file_name.'\'), \'\', \''.$reasoned_graph_url.'\');"', $output);
					}
				}
				*/
			}
		}
	}
	
	private function remove( $dir ) {
		$it = new RecursiveDirectoryIterator( $dir, RecursiveDirectoryIterator::SKIP_DOTS );
		$files = new RecursiveIteratorIterator( $it, RecursiveIteratorIterator::CHILD_FIRST );
		foreach( $files as $file ) {
			if ( $file->isDir() ){
				rmdir( $file->getRealPath() );
			} else {
				unlink( $file->getRealPath() );
			}
		}
		rmdir( $dir );
	}
	
	private function download( $input ) {
		$tokens = explode( '|', $input );
		if ( sizeof( $tokens ) > 1 ) {
			$downloadURL = array_pop( $tokens );
		} else {
			$downloadURL = array_shift( $tokens );
		}
		
		echo "$this->fileName: getting final url $downloadURL\n";
		$downloadURL = $this->getFinalURL( $downloadURL );
		
		echo "$this->fileName: loading data from $downloadURL\n";
		if ( preg_match( '/\.zip/', $downloadURL ) != false ) {
			exec( "wget $downloadURL -O $this->tmpDir$this->fileName.zip" );
			$this->decompress( 'zip' );
		} else {
			exec( "wget -q $downloadURL -O $this->tmpDir$this->fileName.owl" );
		}
		if ( $this->ontology->do_merge == 'y' ) {
			$this->merge( $downloadURL );
		} else {
			if ( file_exists( "$this->tmpDir$this->fileName.owl" ) ) {
				$this->file = "$this->tmpDir$this->fileName.owl";
			}
		}
	}
	
# Need to support other compress format
	private function decompress( $format ) {
		switch ( $format ) {
			case 'zip':
				exec( "unzip $this->tmpDir$this->fileName.zip -d $this->tmpDir$this->fileName");
				exec( "unzip -l $this->tmpDir$this->fileName.zip | grep .owl | awk '{cmd=\"mv $this->tmpDir$this->fileName" .
					DIRECTORY_SEPARATOR .
					"\" $4 \" $this->fileName.owl\"; system(cmd); close(cmd);}");
				break;
		}
		unlink( "$this->tmpDir$this->fileName" );
	}
	
	private function merge( $downloadURL ) {
		$settings = array();
		$settings['download_url'] = $downloadURL;
		$settings['output_file'] = "$this->tmpDir$this->fileName.merged.owl";
		
		$settings['mapping'] = $this->map( $downloadURL, "$this->tmpDir/$this->fileName.mapping" );
		
		file_put_contents( "$this->tmpDir/$this->fileName.json", json_encode( $settings ) );
		
		$importDir = SCRIPTPATH . 'ontology' . DIRECTORY_SEPARATOR;
		exec( 'java -Xmx8g -cp "' . SCRIPTPATH . "library/java/OWLMerge/*\" org.hegroup.owlmerge.OWLMerge $this->tmpDir/$this->fileName.json $importDir" );
		if ( file_exists( $settings['output_file'] ) ) {
			$this->file = $settings['output_file'];
		}
	}
	
	private function map( $downloadURL, $output ) {
		$mappings = array();
		$position = strrpos( $downloadURL, '/' );
		if ( $position != false ) {
			$strFolder = substr( $downloadURL, 0, $position );
			exec( "wget -q $strFolder/catalog-v001.xml -O $output" );
			$mappingContent = file_get_contents( $output );
			
			preg_match_all( '/<uri id="[^"]+" name="([^"]+)" uri="([^"]+)"\/>/', $mappingContent, $matches, PREG_SET_ORDER );
			foreach( $matches as $match ) {
				$mapping['to'] = $match[1];
				$mapping['from'] = $strFolder . '/' . $match[2];
				$mappings[] = $mapping;
			}
		}
		foreach( $GLOBALS['alias']['ontology_url'] as $fromIRI => $toIRI ) {
			$mapping['to'] = $toIRI;
			$mapping['from'] = $fromIRI;
			$mappings[] = $mapping;
		}
		return $mappings;
	}
	
}

if ( PHP_SAPI == 'cli' ) {
	if ( sizeof( $argv ) > 1 ) {
		$args = $argv;
		unset( $args[0] );
		$args = array_values( $args );
		$ontID = $args[0];
		unset( $args[0] );
		$args = array_values( $args );
		$options = array();
		if ( sizeof( $args ) > 0 ) {
			if ( sizeof( $args ) % 2 == 0 ) {
				for ( $i = 0; $i < sizeof( $args ) / 2; $i++ ) {
					$options[$args[$i]] = $args[$i+1];
				}
			} else {
				throw new Exception( 'Invalid arguments.' );
			}
		}
	} else {
		throw new Exception( 'Invalid arguments.' );
	}
	$update = new UpdateOntology( $ontID, $options );
	$update->doUpdate();
}

?>
