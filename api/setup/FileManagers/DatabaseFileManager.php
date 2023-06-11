<?php

include_once($_SERVER['DOCUMENT_ROOT'] . "/api/setup/Crypt/Encryption.php");
// Create encryption object
$crypt = new Encryption();

/**
 * This class is only used for the setup. It handles the
 * information going in and being taken out of the database.json
 * file.
 *
 * @author jiggey
 * @since 1.0.0
 * @last_update 2.0.0
 */
class DatabaseFileManager
{
    // Path
    public mixed $path;

    // Class Constructor
    public function __construct()
    {
        $this->path =  $_SERVER['DOCUMENT_ROOT'] . '/files/';
    }

    /**
     * @return bool
     */
    public function doesDbFileExist(): bool
    {
        if(file_exists($this->path . 'database.json')) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isDbFileFormatted():bool
    {
        // Check if the website has gone through the setup or not.
        $confFile = $this->path . 'database.json';

        $confRead = file_get_contents($confFile);
        $confData = json_decode($confRead, true);

        if(isset($confData['db_host']) || isset($confData['db_port']) || isset($confData['db_user']) || isset($confData['db_pass'])) {
            return true;
        }

        return false;
    }

    /**
     * @return void
     */
    public function formatDbFile(): void
    {
        // Check if the website has gone through the setup or not.
        $dbFile = $this->path . 'database.json';

        $dbRead = file_get_contents($dbFile);
        $dbData = json_decode($dbRead, true);

        $dbData['db_host'] = "";
        $dbData['db_port'] = 3306;
        $dbData['db_user'] = "";
        $dbData['db_pass'] = "";

        file_put_contents($dbFile, json_encode($dbData, JSON_PRETTY_PRINT));
    }

    /**
     * @param $DB_ADDR
     * @param $DB_PORT
     * @param $DB_USER
     * @param $DB_PASS
     * @return bool
     */
    function testDatabase($DB_ADDR, $DB_PORT, $DB_USER, $DB_PASS):bool
    {
        try {
            $conTest = mysqli_connect($DB_ADDR, $DB_USER, $DB_PASS, null, $DB_PORT);
            if (!$conTest) {
                http_response_code(500);
                echo "Invalid Database Credentials -- Couldn't establish a connection to the database provided.";
                return false;
            }
            mysqli_close($conTest);
        } catch (mysqli_sql_exception $e) {
            http_response_code(500);
            echo "Database Error. Please ensure you have the correct database credentials!";
            return false;
        }

        return true;
    }

    /**
     * @param $DB_HOST
     * @param $DB_PORT
     * @param $DB_USER
     * @param $DB_PASS
     * @return bool
     */
    public function setupDatabase($DB_HOST, $DB_PORT, $DB_USER, $DB_PASS): bool
    {
        global $crypt;

        try {
            $dbFile = $this->path . 'database.json';

            $dbRead = file_get_contents($dbFile);
            $dbData = json_decode($dbRead, true);

            $dbData['db_host'] = $crypt->encrypt($DB_HOST);
            $dbData['db_port'] = $crypt->encrypt($DB_PORT);
            $dbData['db_user'] = $crypt->encrypt($DB_USER);
            $dbData['db_pass'] = $crypt->encrypt($DB_PASS);

            $newJSON = json_encode($dbData, JSON_PRETTY_PRINT);
            file_put_contents($dbFile, $newJSON);

            return true;
        } catch (Exception $e) {
            echo "Could not configure database.json. No permission?";
            return false;
        }
    }

    /**
     * @return array
     */
    public function getDbInfo(): array
    {
        $newArray = [];

        $dbFile = $this->path . 'database.json';

        $dbRead = file_get_contents($dbFile);
        $dbData = json_decode($dbRead, true);

        array_push($newArray, $dbData['db_host'], $dbData['db_port'], $dbData['db_user'], $dbData['db_pass']);

        return $newArray;
    }

}