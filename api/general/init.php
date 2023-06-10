<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/api/fileManager/SetupFileManager.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/api/database/DB.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/api/user/User.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/api/general/Session.php');

$setup = new SetupFileManager();

if(!$setup->getDatabaseStatus() || !$setup->isDatabaseReady()) {
    echo "<script>window.location='./setup.php'</script>";
    exit();
}

$con = new DB();
$session = new Session();

if(isset($_SESSION['uid'])) {
    $user = new User($_SESSION['uid']);
}