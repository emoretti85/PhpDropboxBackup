<?php
session_start();
require_once 'dbb/Dbb.php';

$Dbb = new Dbb();
	$Dbb->dropbox_auth();

if($Dbb->dropbox_isConnected()){	
	//Start Backup
	$uploadResult=$Dbb->backupStart();
	echo "<pre>";
	print_r($uploadResult);	
}
