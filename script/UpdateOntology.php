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

if ( !defined( 'MAINCLASS' ) ) {
	DEFINE( 'MAINCLASS', __FILE__ );
}

require_once( 'Maintenance.php' );

Class UpdateOntology extends Maintenance {
	private $ontID;
	
	private $tmpDir;
	private $ontology;
	
	private $fileName;
	private $file;
	
	public function __construct() {
		parent::__construct();
		
		$this->addArg( 'ontID', 'Ontology ID to be updated' );
		
		$this->connectDB();
	}
	
	public function setOntID( $ontID ) {
		$this->ontID = $ontID;
	}
	
	public function execute() {
		$this->setup();
		$this->update();
	}
	
	protected function setup() {
		$this->logger->info( "Setting up update process" );
		
		if ( !isset( $this->ontID ) ) {
			$this->ontID = $this->getArgByName( 'ontID' );
		}
		
		$this->logger->debug( 'Creating temporary RDF directory' );
		$this->tmpDir = SYSTMP . 'rdf' . DIRECTORY_SEPARATOR;
		if ( !file_exists( $this->tmpDir ) ) {
			mkdir( $this->tmpDir );
			chmod( $this->tmpDir, 0777 );
		}
		$this->logger->debug( 'Complete');
		
		$this->logger->debug( "Querying $this->ontID from MySQL ontology table" );
		$sql = "SELECT * FROM ontology WHERE id = '$this->ontID'";
		$query = $this->db->prepare( $sql );
		$query->execute();
		$this->ontology = $query->fetch();
		$this->fileName = $this->ontology->ontology_abbrv;
		$this->logger->debug( 'Complete' );
		
		$this->logger->info( 'Setup complete' );
	}
	
	public function update() {
		$this->logger->info( "Starting $this->fileName update process" );
		
		$this->logger->info( "Downloading $this->fileName" );
		
		$this->logger->debug( "Trying to download $this->fileName from ontology URL" );
		if ( $this->ontology->ontology_url != '' ) {
			$this->download( $this->ontology->ontology_url );
		}
		if ( !file_exists( $this->file ) && $this->ontology->download != '' ) {
			$this->logger->debug( "Trying to download $this->fileName from given download link" );
			$this->download( $this->ontology->download );
		}
		
		if ( !file_exists( $this->file ) && $this->ontology->source != '' ) {
			$this->logger->debug( "Trying to download $this->fileName from given source link" );
			$this->download( $this->ontology->source );
		}
		
		if ( !file_exists( $this->file ) && $this->ontology->alternative_download != '' ) {
			$this->logger->debug( "Trying to download $this->fileName from given alternative download link" );
			$this->download( $this->ontology->alternative_download );
		}
		
		if ( !file_exists( $this->file ) ) {
			$this->logger->warn( "Fail to download $this->file owl file" );
			if ( $this->ontology->loaded == 'y' ) {
				$msg =
<<<END
$this->fileName download failed.
Previous version will be used.
END;
			} else {
				$msg =
<<<END
$this->fileName download failed.
No Previous version was found.
This ontology will be hidden from home page.
END;
			}
			$status = $this->warn( $msg );
		} else {
			$this->logger->info( 'Download complete' );
			
			$this->logger->info( "Processing $this->fileName" );
			
			$this->logger->debug( 'Encoding ontology' );
			$md5 =  md5_file( $this->file );
			$path = pathinfo( $this->file );
			
			$this->logger->debug( "Copying $this->fileName to Ontobee ontology location" );
			copy( $this->file, SCRIPTPATH . 'ontology' . DIRECTORY_SEPARATOR . $path['basename'] );
			
			$this->logger->info( 'Process complete' );
			
			$this->logger->info( "Checking $this->fileName version" );
			
			if ( $md5 == $this->ontology->md5 && $this->ontology->loaded == 'y' ) {
				$this->logger->info( 'Ontology already up-to-date' );
			} else {
				$this->logger->info( 'Newer version is found' );
				
				$this->logger->debug( 'Setting MySQL ontology table to loaded=\'n\'' );
				$sql = "UPDATE ontology SET loaded='n' where id = '{$this->ontology->id}'";
				$this->db->query( $sql );
				
				$this->logger->info( 'Starting Virtuoso RDF upload' );
				$usr = RDF_USERNAME;
				$pwd = RDF_PASSWORD;
				$isql = RDF_ISQL_COMMAND;
				$cmd =
<<<END
$isql 1111 $usr $pwd verbose=on banner=off prompt=off echo=ON errors=stdout exec="log_enable(3,1);
sparql clear graph <{$this->ontology->ontology_graph_url}>;
DB.DBA.RDF_LOAD_RDFXML_MT (file_to_string_output ('$this->file'), '', '{$this->ontology->ontology_graph_url}');"
END;
				
				$this->logger->debug( 'Executing isql shell command' );
				exec( $cmd, $output);
				$output = join( "\n", $output );
				
				$this->logger->debug( " Command output:\n$output " );
				$sql = "UPDATE ontology SET log=" . $this->db->quote( $output ) . " where id = '{$this->ontology->id}'";
				$this->db->query( $sql );
				
				if ( !preg_match( '/Error/', $output ) ) {
					$this->logger->info( 'Virtuoso RDF upload complete' );
					
					$this->logger->debug( 'Setting MySQL ontology table to loaded=\'y\' and update md5' );
					$sql = "UPDATE ontology SET loaded='y', md5='$md5', last_update=now() where id = '{$this->ontology->id}'";
					$this->db->query( $sql );
				} else if ( preg_match( '/Error S2801/', $output ) ) {
					$this->logger->debug( "Unable to connect Virtuoso RDF" );
					$this->logger->error( 'Virtuoso RDF upload fail' );
					
					if ( $this->ontology->loaded == 'y' ) {
						$this->logger->debug( 'Setting MySQL ontology table to loaded=\'y\'' );
						$sql = "UPDATE ontology SET loaded='y' where id = '{$this->ontology->id}'";
						$this->db->query( $sql );
						$this->logger->info( 'Previous version will be used' );
						$msg =
<<<END
$this->fileName update failed.
Previous version will be used.
END;
					} else {
						$msg =
<<<END
$this->fileName update failed.
No Previous version was found.
This ontology will be hidden from home page.
END;
					}
					$this->error( $msg );
				} else if ( preg_match( '/Error 28000/', $output ) ) {
					$this->logger->debug( "Unable to login Virtuoso RDF" );
					$this->logger->error( 'Virtuoso RDF upload fail' );
					
					if ( $this->ontology->loaded == 'y' ) {
						$this->logger->debug( 'Setting MySQL ontology table to loaded=\'y\'' );
						$sql = "UPDATE ontology SET loaded='y' where id = '{$this->ontology->id}'";
						$this->db->query( $sql );
						$this->logger->info( 'Previous version will be used' );
						$msg =
<<<END
$this->fileName update failed.
Previous version will be used.
END;
					} else {
						$msg =
<<<END
$this->fileName update failed.
No Previous version was found.
This ontology will be hidden from home page.
END;
					}
					$this->error( $msg );
				} else {
					$this->logger->error( 'Virtuoso RDF upload fail' );
					$msg =
<<<END
$this->fileName update failed.
This ontology will be hidden from home page.
END;
					$this->error( $msg );
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
		
		$this->logger->info( 'Removing temporary file' );
		array_map( 'unlink', glob( "$this->tmpDir$this->fileName*.*" ) );
		
		$this->logger->info( 'Update process complete' );
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
		
		if ( strpos( $downloadURL, 'file:' ) != false ) {
			$header = get_headers( $downloadURL, 1 );
			if ( !strpos( $header[0], '200 OK' ) ) return;
		}
		if ( in_array( $downloadURL, $GLOBALS['download']['exclude_url'] ) ) return;
		
		echo "$this->fileName: loading data from $downloadURL\n";
		if ( preg_match( '/\.zip/', $downloadURL ) != false ) {
			exec( "wget '$downloadURL' -O $this->tmpDir$this->fileName.zip" );
			$this->decompress( 'zip' );
		} else {
			exec( "wget -q '$downloadURL' -O $this->tmpDir$this->fileName.owl" );
		}
		if ( filesize( "$this->tmpDir$this->fileName.owl" ) == 0 ) {
			unlink( "$this->tmpDir$this->fileName.owl" );
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
				exec( "unzip -l $this->tmpDir$this->fileName.zip | awk '/.owl/ {cmd=\"mv $this->tmpDir$this->fileName" .
					DIRECTORY_SEPARATOR .
					"\" $4 \" $this->fileName.owl\"; system(cmd); close(cmd);}'");
				unlink( "$this->tmpDir$this->fileName.zip" );
				$this->remove( "$this->tmpDir$this->fileName" );
				break;
		}
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

if ( MAINCLASS == __FILE__ ) {
	$update = new UpdateOntology();
	$update->loadParameter();
	$update->execute();
}

?>
