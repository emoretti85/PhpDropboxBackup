<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
* "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
* LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
* A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
* OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
* SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
		* LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
		* DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
* THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
* (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
* OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*
* This software consists of voluntary contributions made by many individuals
* and is licensed under the MIT license. For more information, see
* <http://www.doctrine-project.org>.
*/

/**
 * Php Dropbox Backup
*
* This class allows you to backup files, in your dropbox account.
*
* PHP version 5
*
* @license http://www.opensource.org/licenses/mit-license.html  MIT License
* @author 	Ettore Moretti <ettoremoretti27{at}gmail{dot}com>
* @copyright	Ettore Moretti 2014
* @version	1.0
* @since  	2014
*/
class Dbb {
	private $dropbox_connection;
	protected $user, $appInfo, $token, $Auth, $client;
	public function __construct() {
		// *************************************************
		// change this require if you used composer to obtain a copy of the SDK dropbox
		require __DIR__ . '/../Dropbox_sdk/autoload.php';
		// *************************************************
		
		require 'Dbb_config.php';
		
		$_SESSION ['user_id'] = Dropbox_userid;
		$this->appInfo = new Dropbox\AppInfo ( Dropbox_key, Dropbox_secret );
		$this->token = new Dropbox\ArrayEntryStore ( $_SESSION, 'dropbox-auth-csrf-token' );
		$this->Auth = new Dropbox\WebAuth ( $this->appInfo, Dropbox_appName, Dropbox_redirect, $this->token );
		$this->getUserDetail ();
	}
	public function backupStart() {
		$ret = array ();
		if (ZipFolderBeforeUpload) {
			$zip = new ZipArchive ();
			$zip_n = "Backup_" . time () . ".zip";
			$zip_name = BackupFolder . $zip_n;
			$zip->open ( $zip_name, ZipArchive::CREATE );
			
			$files = new RecursiveIteratorIterator ( new RecursiveDirectoryIterator ( BackupFolder ), RecursiveIteratorIterator::LEAVES_ONLY );
			$f_count = 0;
			
			foreach ( $files as $file ) {
				$path = $file->getRealPath ();
				$filename = $file->getFilename ();
				// check file permission
				if (fileperms ( $path ) != "16895" && ($filename != '.' || $filename = ! '..')) {
					$zip->addFromString ( basename ( $path ), file_get_contents ( $path ) );
					$f_count ++;
				}
			}
			
			// ZipPassProtection
			if (ProtectZipFile)
				$zip->setPassword ( ZipFilePassword );
			
			$zip->close ();
			if ($f_count > 0)
				$ret [] = $this->uploadToDropbox ( $zip_name, $zip_n );
			
			if (deleteFilesAfterUpload === true && $f_count > 0) {
				foreach ( $files as $file ) {
					$path = $file->getRealPath ();
					if (fileperms ( $path ) != "16895" && ($filename != '.' || $filename = ! '..'))
						unlink ( $path );
				}
			}
		} else {
			// Upload any single file inside the Backup folder
			$files = new RecursiveIteratorIterator ( new RecursiveDirectoryIterator ( BackupFolder ), RecursiveIteratorIterator::LEAVES_ONLY );
			
			foreach ( $files as $file ) {
				$path = $file->getRealPath ();
				$filename = $file->getFilename ();
				// check file permission
				if (fileperms ( $path ) != "16895" && ($filename != '.' || $filename = ! '..')) {
					$ret [] = $this->uploadToDropbox ( $path, $file->getFilename () );
					if (deleteFilesAfterUpload === true) {
						unlink ( $path );
					}
				}
			}
		}
		return $ret;
	}
	private function uploadToDropbox($BckFilePath, $BckName) {
		$file = fopen ( $BckFilePath, 'rb' );
		$filesize = filesize ( $BckFilePath );
		
		$Dropbox_uploadType = null;
		switch (Dropbox_uploadType) {
			case 'add' :
				$Dropbox_uploadType = Dropbox\WriteMode::add ();
				break;
			
			case 'force' :
				$Dropbox_uploadType = Dropbox\WriteMode::force ();
				break;
			
			case 'upload' :
				$Dropbox_uploadType = Dropbox\WriteMode::upload ();
				break;
			
			default :
				$Dropbox_uploadType = Dropbox\WriteMode::add ();
				break;
		}
		
		return $this->client->uploadFile ( Dropbox_backupPath . $BckName, $Dropbox_uploadType, $file, $filesize );
	}
	public function dropbox_isConnected() {
		return $this->dropbox_connection;
	}
	public function dropbox_auth() {
		if ($this->user->token) {
			$this->client = new Dropbox\Client ( $this->user->token, Dropbox_appName, 'UTF-8' );
			try {
				$this->client->getAccountInfo ();
			} catch ( Dropbox\Exception_InvalidAccessToken $e ) {
				$this->dropbox_connection = false;
				$url = $this->Auth->start ();
				header ( 'Location:' . $url );
				exit ();
			}
			$this->dropbox_connection = true;
		} else {
			$this->dropbox_connection = false;
			$url = $this->Auth->start ();
			header ( 'Location:' . $url );
			exit ();
		}
	}
	public function dropbox_landed($get) {
		list ( $acToken ) = $this->Auth->finish ( $get );
		$this->setUserDetail ( $acToken );
		header ( 'Location: ' . myIndex );
		exit ();
	}
	private function getUserDetail() {
		$db = new PDO ( 'mysql:host=localhost;dbname=dbb', 'root', '' );
		$stmt = $db->prepare ( 'SELECT * FROM users WHERE id= :user_id' );
		$stmt->execute ( array (
				'user_id' => $_SESSION ['user_id'] 
		) );
		$this->user = $stmt->fetchObject ();
	}
	private function setUserDetail($t) {
		$db = new PDO ( 'mysql:host=localhost;dbname=dbb', 'root', '' );
		$updStmt = $db->prepare ( 'UPDATE users SET token = :token WHERE id= :id' );
		$updStmt->execute ( array (
				'token' => $t,
				'id' => $_SESSION ['user_id'] 
		) );
	}
}
