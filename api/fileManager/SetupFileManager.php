<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/api/crypt/Encryption.php');
$crypt = new Encryption();

/**
 *
 * This class is the main File Handler for the setup part of the website.
 * These functions can only be executed successfully once, since being able
 * to do it whenever is pointless and a huge security flaw.
 *
 * @author jiggey1
 * @since 1.0.0
 * @last_update 1.0.0
 */
class SetupFileManager {

    // This constructor creates the correct files + directories,
    // if they are not already present (they shouldn't be)
    public function __construct() {
        $fileContent = "";

        // Specify the file path and name in the root directory
        $homePath = $_SERVER['DOCUMENT_ROOT'] . '/files/';

        // Un-encrypted file
        $configFile = $homePath . 'config.json';
        // Encrypted file
        $databaseFile = $homePath . 'database.json';

        // The files already exist, therefore don't proceed.
        if((file_exists($configFile) || file_exists($databaseFile)) && file_exists($homePath)) {
            // Debugging
            //echo "Files Exist";
            return;
        } // Else
        else {
            // Create the main files.

            // /files/ directory
            mkdir($homePath, 0755, true);
        }

        $config_create = file_put_contents($configFile, $fileContent);
        $db_create = file_put_contents($databaseFile, $fileContent);

        // Check to make sure file creation worked.
        if ($config_create !== false && $db_create !== false) {
            //echo "File created successfully.";
            // Create Main File Structure
            $this->setupDatabaseFile();
            $this->setupConfigFile();
        } else {
            http_response_code(500);
            exit("Error! Could not create your files. Make sure this setup has permissions to create files in the webserver directory.");
        }
    }

    private function setupConfigFile() {
        // Check if the website has gone through the setup or not.
        $homePath = $_SERVER['DOCUMENT_ROOT'] . '/files/';
        $confFile = $homePath . 'config.json';

        $confRead = file_get_contents($confFile);
        $confData = json_decode($confRead, true);

        $confData['db_setup'] = false;
        $confData['db_configured'] = false;

        $newJSON = json_encode($confData, JSON_PRETTY_PRINT);
        file_put_contents($confFile, $newJSON);

        // The rest is not done for now since we don't have a website to configure
    }

    public function updateDatabaseStatus(bool $status) {
        $homePath = $_SERVER['DOCUMENT_ROOT'] . '/files/';
        $confFile = $homePath . 'config.json';

        $confRead = file_get_contents($confFile);
        $confData = json_decode($confRead, true);

        $confData['db_setup'] = $status;

        $newJSON = json_encode($confData, JSON_PRETTY_PRINT);
        file_put_contents($confFile, $newJSON);
    }

    public function getDatabaseStatus() {
        $homePath = $_SERVER['DOCUMENT_ROOT'] . '/files/';
        $confFile = $homePath . 'config.json';

        $confRead = file_get_contents($confFile);
        $confData = json_decode($confRead, true);

        return $confData['db_setup'];
    }

    public function doesAdminExist():bool
    {
        global $crypt;
        include_once($_SERVER['DOCUMENT_ROOT'] . '/api/fileManager/DBFileManager.php');
        $dbArray = getDBCredentials();
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

    public function isDatabaseReady() {
        $homePath = $_SERVER['DOCUMENT_ROOT'] . '/files/';
        $confFile = $homePath . 'config.json';

        $confRead = file_get_contents($confFile);
        $confData = json_decode($confRead, true);

        return $confData['db_configured'];
    }

    private function setupDatabaseFile() {
        // Create the following JSON Layout:
        /* database.json
         * {
         *   'db_address': 'encryptedAddress',
         *   'db_port': port,
         *   'db_user': 'encryptedUser',
         *   'db_pass': 'encryptedPass'
         * }
         */
        $homePath = $_SERVER['DOCUMENT_ROOT'] . '/files/';
        $dbFile = $homePath . 'database.json';

        $dbRead = file_get_contents($dbFile);
        $dbData = json_decode($dbRead, true);

        $dbData['db_address'] = '';
        $dbData['db_port'] = 0;
        $dbData['db_user'] = '';
        $dbData['db_pass'] = '';

        $newJSON = json_encode($dbData, JSON_PRETTY_PRINT);
        file_put_contents($dbFile, $newJSON);
    }

}