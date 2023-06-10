<?php

include_once ($_SERVER['DOCUMENT_ROOT'] . "/api/fileManager/DBFileManager.php");
include_once($_SERVER['DOCUMENT_ROOT'] . '/api/crypt/Encryption.php');
$crypt = new Encryption();

$dbArray = getDBCredentials();

class DB
{

    private $DATABASE_NAME;
    private $DATABASE_ADDRESS;
    private $DATABASE_PORT;
    private $DATABASE_USER;
    private $DATABASE_PASS;

    public mysqli|false $con;

    public function __construct()
    {
        global $crypt;
        global $dbArray;
        global $DATABASE_NAME;
        global $DATABASE_ADDRESS;
        global $DATABASE_PORT;
        global $DATABASE_PASS;

        $DATABASE_NAME = "tick_system";
        $DATABASE_ADDRESS = $crypt->decrypt($dbArray[0]);
        $DATABASE_PORT = $crypt->decrypt($dbArray[1]);
        $DATABASE_USER = $crypt->decrypt($dbArray[2]);
        $DATABASE_PASS = $crypt->decrypt($dbArray[3]);

        $this->con = mysqli_connect($DATABASE_ADDRESS, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $DATABASE_PORT);
    }

}