<?php
/**
 * Dropbox App Configuration
 * 
 */

//The user ID belonging to the user with which to upload, this must be inserted in the users table db DBB
define('Dropbox_userid','1');
//the key of your application dropbox
define('Dropbox_key','<Dropbox key>');
//the secret key of your application dropbox
define('Dropbox_secret','<Dropbox secret>');
//the app name of your application dropbox
define('Dropbox_appName','PhpDbb');
//the link redirects set in the application dropbox
define('Dropbox_redirect','http://<your host path>/DropboxBackup/dbb/Dbb_landing.php');
//the link to the main php page that instantiates Dbb class
define('myIndex','http://<your host path>/DropboxBackup/index.php');
//the DROPBOX path where the file will be loaded
define('Dropbox_backupPath','/DbbBackup/');
//Select the dropbox upload type in case of equality in filenames, use one of this ( add;force;upload; )
define('Dropbox_uploadType','add');

/**
 * Backup Configuration
 * 
 */

//the local path where are the backup files
define('BackupFolder',__DIR__.'/../Backup/');
//you want to delete all the files after upload?
define('deleteFilesAfterUpload',true);
//you want all the files to be zipped before uploading?
define('ZipFolderBeforeUpload',true);

//Note : to protect your zip with password you need php 5.6.0 or higher.
//want to protect the zip file with a password?
define('ProtectZipFile',false);
//the zip password
define('ZipFilePassword','!MyIncredibleHardPassword#1');
