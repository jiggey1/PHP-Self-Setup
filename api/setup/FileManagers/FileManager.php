<?php

// Includes
include_once($_SERVER['DOCUMENT_ROOT'] . "/api/setup/FileManagers/DatabaseFileManager.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/api/setup/FileManagers/ConfigFileManager.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/api/setup/Crypt/Encryption.php");

$fm = new FileManager();
$crypt = new Encryption();
/**
 * This class is only used when setting up the website.
 * This is used to retrieve information from DatabaseFileManager and ConfigFileManager.
 *
 * @author jiggey
 * @since 1.0.0
 * @last_update 2.0.0
 */
class FileManager
{

    // Path
    public mixed $path;
    public $dbFile;
    public ConfigFileManager $configFile;

    // Class Constructor
    public function __construct() {

        // [webroot]/files/ dir
        $this->path =  $_SERVER['DOCUMENT_ROOT'] . '/files/';

        // Create /files/ if not present
        if(!file_exists($this->path)) {
            mkdir($this->path, 0755, true);
        }

        // Define variables
        $this->dbFile = new DatabaseFileManager();
        $this->configFile = new ConfigFileManager();

        // Run various checks to ensure minimal errors on pre/post setup.
        if(!$this->configFile->doesConfigFileExist()) {
            $content = '';

            file_put_contents($this->path . 'config.json', $content);
        }

        if(!$this->configFile->isConfigFileFormatted()) {
            $this->configFile->formatConfigFile();
        }

        if(!$this->dbFile->doesDbFileExist()) {
            $content = '';

            file_put_contents($this->path . 'database.json', $content);
        }

        if(!$this->dbFile->isDbFileFormatted()) {
            $this->dbFile->formatDbFile();
        }

    }

    /**
     * @return boolean
     */
    public function isDbSetup():bool
    {
        return $this->configFile->isDatabaseSetup();
    }

    /**
     * @return bool
     */
    public function adminAccount():bool
    {
        global $crypt;

        $dbArray = $this->dbFile->getDbInfo();
        $con = mysqli_connect($crypt->decrypt($dbArray[0]), $crypt->decrypt($dbArray[2]), $crypt->decrypt($dbArray[3]), 'tick_system', $crypt->decrypt($dbArray[1]));

        if($stmt = $con->prepare("SELECT * FROM users WHERE is_admin = 1")) {
            $stmt->execute();
            $stmt->store_result();

            if($stmt->num_rows > 0) {
                return true;
            }
        }

        return false;
    }

}