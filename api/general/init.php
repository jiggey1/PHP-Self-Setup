<?php

$site_ver = '2.0.0';

include_once($_SERVER['DOCUMENT_ROOT'] . "/api/setup/FileManagers/FileManager.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/api/database/DB.php");
global $fm;

if(!$fm->configFile->isDatabaseSetup()) {
    http_response_code(500);
    header("Location: ../../setup.php");
}

$con = new DB();

