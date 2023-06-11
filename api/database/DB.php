<?php

// Includes + Objects
include_once($_SERVER['DOCUMENT_ROOT'] . "/api/setup/Crypt/Encryption.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/api/setup/FileManagers/FileManager.php");
$crypt = new Encryption();
$fm = new FileManager();

/**
 * This file gets the database connection information from
 * database.json (configured in setup.php) and decrypts the
 * encrypted strings to attempt a database connection.
 *
 * @author jiggey
 * @since 1.0.0
 * @last_update 2.0.0
 */
class DB
{

    // Variables
    private $DB_HOST;
    private $DB_PORT;
    private $DB_USER;
    private $DB_PASS;

    // How we access the database in other files ($example->con->prepare([SQL])).
    public mysqli $con;

    // Class constructor
    public function __construct() {
        // globals :(
        global $crypt;
        global $fm;

        // Retrieve credentials from dbFile
        $creds = $fm->dbFile->getDbInfo();

        // DB name won't change and is pre-configured before this file is
        // reached (avoids db not found / no db selected errors).
        $DB_NAME = "tick_system";
        // Defining variables
        $this->DB_HOST = $crypt->decrypt($creds[0]);
        $this->DB_PORT = $crypt->decrypt($creds[1]);
        $this->DB_USER = $crypt->decrypt($creds[2]);
        $this->DB_PASS = $crypt->decrypt($creds[3]);

        // Using the variables to connect.
        $this->con = mysqli_connect($this->DB_HOST, $this->DB_USER, $this->DB_PASS, $DB_NAME, $this->DB_PORT);
    }

}