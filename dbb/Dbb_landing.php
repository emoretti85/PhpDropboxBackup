<?php
session_start();
require_once 'Dbb.php';
$Dbb = new Dbb();
$Dbb->dropbox_landed($_GET);
