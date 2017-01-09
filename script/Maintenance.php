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
 * @file Api.php
 * @author Edison Ong
 * @since Oct 1, 2015
 * @comment 
 */

if ( !defined( 'MAINCLASS' ) ) {
	DEFINE( 'MAINCLASS', __FILE__ );
}

# Define path constant
$tokens = explode( DIRECTORY_SEPARATOR, __DIR__ );
$dir = implode( DIRECTORY_SEPARATOR, array_splice( $tokens, 0, -1 ) );
DEFINE( 'SCRIPTPATH',  $dir . DIRECTORY_SEPARATOR );
DEFINE( 'APPPATH', SCRIPTPATH . 'application' . DIRECTORY_SEPARATOR );
DEFINE( 'SYSTMP', sys_get_temp_dir() . DIRECTORY_SEPARATOR );
DEFINE( 'TMPPATH', SCRIPTPATH . 'tmp' . DIRECTORY_SEPARATOR );
DEFINE( 'LOGPATH', SCRIPTPATH . 'log' . DIRECTORY_SEPARATOR );

# Require dependencies
require_once APPPATH . 'Config/DB.php';
require_once APPPATH . 'Config/OntologyConfig.php';
require_once APPPATH . 'Config/MailConfig.php';
require_once SCRIPTPATH . 'vendor/autoload.php';

abstract class Maintenance {
	protected $argList;
	protected $argName;
	protected $args;
	
	protected $optionList;
	protected $optionAbbrv;
	protected $options;
	
	protected $db;
	protected $logger;
	protected $logFile;
	protected $logMail;
	
	private $mailList;
	
	# Define Error status constant
	const ERROR = 0;
	const WARN = 1;
	
	public function __construct() {
		# Increase Memory
		ini_set( 'memory_limit', '4096M' );
		
		# Set Timezone
		date_default_timezone_set('America/New_York');
		
		# Initialize options
		$this->addOption( 'help', 'Display this help message', 'h', false );
		
		# Set Logger
		$this->setLogger();
		
		# Set Mail List
		$this->mailList = $GLOBALS['mail_list'];
	}
	
	public function __destruct() {
		if ( file_exists( $this->logMail ) ) {
			unlink( $this->logMail );
		};
	}
	
	public function loadParameter() {
		global $argv;
		$pars = $argv;
		array_shift($pars);
		if ( sizeof( $pars ) >= 1 ) {
			if ( substr( $pars[0], 0, 2 ) == '--' ) {
				$name = substr( $pars[0], 2 );
				if ( array_key_exists( $name, $this->optionList ) ) {
					$this->options[$name] = $pars[1];
				}
				
				array_shift($pars);
				array_shift($pars);
			} else if ( substr( $pars[0], 0, 1 ) == '-' ) {
				$abbrv = substr( $pars[0], 1 );
				$name = $this->optionAbbrv[$abbrv];
				if ( array_key_exists( $name, $this->optionList ) ) {
					$this->options[$name] = $pars[1];
				}
				
				array_shift($pars);
				array_shift($pars);
			} else {
				$this->args[] = $pars[0];
				array_shift($pars);
			}
		} else {
			# TODO: Show help
		}
		$this->checkArgAndOption();
	}
	
	abstract public function execute();
	
	abstract protected function setup();
	
	protected function addArg( $name, $desc, $require = true ) {
		$this->argList[] = array(
				'name' => $name,
				'desc' => $desc,
				'require' => $require
		);
		$this->argName[] = $name;
	}
	
	protected function getArgByID( $id ) {
		return $this->args[$id];
	}
	
	protected function getArgByName( $name ) {
		$id = array_search( $name, $this->argName );
		return $this->args[$id];
	}
	
	protected function addOption( $name, $desc, $abbrv = false, $require = false ) {
		$this->optionList[$name] = array(
				'desc' => $desc,
				'require' => $require,
				'abbrv' => $abbrv
		);
	
		if ( $abbrv ) {
			$this->optionAbbrv[$abbrv] = $name;
		}
	}
	
	protected function hasOption( $name ) {
		return isset( $this->options[$name] );
	}
	
	protected function getOption( $name ) {
		return $this->options[$name];
	}
	
	protected function checkArgAndOption() {
		# TODO
	}
	
	protected function error( $msg = '' ) {
		$this->sendReport( self::ERROR, $msg );
		die;
	}
	
	protected function warn( $msg = '' ) {
		$this->sendReport( self::WARN, $msg );
	}
	
	protected function connectDB() {
		$options = array(
			\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
			\PDO::ATTR_ERRMODE => \PDO::ERRMODE_WARNING,
		);
		$this->db = new \PDO( DB_DRIVER . ':host=' . DB_HOST . ';dbname=' . DB_SCHEMA, DB_USERNAME, DB_PASSWORD, $options );
	}
	
	protected function getFinalURL( $url ) {
		$request = curl_init ( $url );
		curl_setopt ( $request, CURLOPT_FOLLOWLOCATION, true );
		curl_setopt ( $request, CURLOPT_RETURNTRANSFER, true );
	
		curl_exec ( $request );
	
		if ( !curl_errno ( $request ) )
			$url = curl_getinfo ($request, CURLINFO_EFFECTIVE_URL );
	
		curl_close ( $request );
	
		return $url;
	}
	
	private function setLogger() {
		$this->logger = Logger::getLogger( 'maintenance' );
	
		$layout = new LoggerLayoutPattern();
		$layout->setConversionPattern("%d{m/d/y H:i:s} [%c] %C %p - %m%n");
		$layout->activateOptions();
	
		/*
			$tmpFile = tmpfile();
			$tmpMeta = stream_get_meta_data( $tmpFile );
			$this->logMail = $tmpMeta['uri'];
		*/
		$this->logMail = TMPPATH . 'maintenance.mail';
		$appMail = new LoggerAppenderFile( 'maintenanceMail' );
		$appMail->setFile( $this->logMail );
		$appMail->setAppend( true );
		$appMail->setThreshold( 'debug' );
		$appMail->setLayout( $layout );
		$appMail->activateOptions();
		$this->logger->addAppender( $appMail );
	
	
		$logFile = LOGPATH . 'maintenance.log';
		if ( file_exists( $logFile ) ) {
			if ( date( 'Y', filectime( $logFile ) ) != date( 'Y', time() ) ||
					date( 'M', filectime( $logFile ) ) != date( 'M', time() ) ) {
						unlink( $logFile );
					}
		}
		touch( $logFile );
		$this->logFile = $logFile;
		$appFile = new LoggerAppenderFile( 'maintenanceFile' );
		$appFile->setFile( $this->logFile );
		$appFile->setAppend( true );
		$appFile->setThreshold( 'all' );
		$appFile->setLayout( $layout );
		$appFile->activateOptions();
		$this->logger->addAppender( $appFile );
	}
	
	private function sendReport( $status, $errorMsg ) {
		$mail = new PHPMailer;
	
		$mail->isSMTP();
		$mail->SMTPDebug = 1;
		$mail->SMTPAuth = true;
		$mail->SMTPSecure = 'tls';
		$mail->Host = MAIL_HOST;
		$mail->Port = 587;
		$mail->Username = MAIL_USERNAME;
		$mail->Password = MAIL_PASSWORD;
	
		foreach ( $this->mailList as $recipient ) {
			$mail->addAddress( $recipient );
		}
		$mail->setFrom( MAIL_USERNAME );
		$time = date("Y-m-d H:i:s", time());
	
		if ( $status == self::ERROR ) {
			$title = 'Ontobee Server: ' . basename( MAINCLASS, '.php' ) . ' Execution Error';
		} else if ( $status == self::WARN ) {
			$title = 'Ontobee Server: ' . basename( MAINCLASS, '.php' ) . ' Execution Warning';
		}
		$mail->Subject = $title;
		
		$log = file_get_contents( $this->logMail );
		
		$mail->Body =
<<<END
Ontobee Maintenance Script Report

==================================================
Report Time
==================================================
$time

==================================================
Report Message
==================================================
$errorMsg

==================================================
Report Log
==================================================
$log
END;
		
		if( !$mail->send() ) {
			$this->logger->warn( 'Report could not be sent.' );
			$this->logger->debug( 'Mailer Error: ' . $mail->ErrorInfo );
		} else {
			$this->logger->info( 'Report has been sent' );
		}
	}
}

?>
