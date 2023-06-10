<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/api/fileManager/SetupFileManager.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/api/fileManager/DBFileManager.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/api/crypt/Encryption.php');
$crypt = new Encryption();

// Creating The Setup Functions
$setup = new SetupFileManager();
$dbArray = getDBCredentials();

if(isset($_GET['request'])) {
    $request = $_GET['request'];
} else {
    $request = 'nothing';
}

switch ($request) {
    case 'database_setup':
        if(isset($_POST['db_root_name'])) {
            if(!$setup->getDatabaseStatus()) {
                if (testDatabase($_POST['db_host'], $_POST['db_port'], $_POST['db_root_name'], $_POST['db_root_pass'])) {
                    configureDatabase($_POST['db_host'], $_POST['db_port'], $_POST['db_root_name'], $_POST['db_root_pass']);

                    header("Location: ../../setup.php");
                }
            } else {
                http_response_code(404);
                header("Location: ../../setup.php");
                exit();
            }
        } else {
            exit("Error. No database credentials presented.");
        }

        break;
    case 'database_configure':
        if(isDBReady()) {
            header("Location: ../../setup.php");
            exit();
        }

        $tempCon = mysqli_connect($crypt->decrypt($dbArray[0]), $crypt->decrypt($dbArray[2]), $crypt->decrypt($dbArray[3]), null, $crypt->decrypt($dbArray[1]));

        if($stmt = $tempCon->prepare("CREATE DATABASE `tick_system`")) {
            $stmt->execute();
            $stmt->store_result();
        }

        $con = mysqli_connect($crypt->decrypt($dbArray[0]), $crypt->decrypt($dbArray[2]), $crypt->decrypt($dbArray[3]), 'tick_system', $crypt->decrypt($dbArray[1]));

        $query = "CREATE TABLE users
(
    id       int unsigned auto_increment
        primary key,
    username varchar(18)   not null,
    email    varchar(125)  not null,
    password varchar(300)  not null,
    is_admin int default 0 not null,
    uid int(4) not null
);";

        if($con->query($query) === TRUE) {
            updateDB(true);
        }

        header("Location: ../../setup.php");

        break;
    case 'account_setup':
        if(!isset($_POST['admin_name']) || !isset($_POST['admin_email']) || !isset($_POST['admin_pass']) || !isset($_POST['admin_pass_conf'])) {
            header("Location: ../../setup.php?e=true&msg=Error.");
            exit();
        }

        $name = $_POST['admin_name'];
        $email = $_POST['admin_email'];
        $pass = $_POST['admin_pass'];
        $pass_conf = $_POST['admin_pass_conf'];
        $admin = 1;

        $con = mysqli_connect($crypt->decrypt($dbArray[0]), $crypt->decrypt($dbArray[2]), $crypt->decrypt($dbArray[3]), 'tick_system', $crypt->decrypt($dbArray[1]));

        $uid = random_int(1000, 9999);

        if($stmt = $con->prepare("SELECT * FROM users WHERE is_admin = 1")) {
            $stmt->execute();
            $stmt->store_result();

            if($stmt->num_rows > 0) {
                http_response_code(500);
                exit("Admin account exists already, aborting.");
            }
        }

        if($stmt = $con->prepare("INSERT INTO users (username, email, password, is_admin, uid) VALUES (?, ?, ?, ?, ?) ")) {
            $stmt->bind_param("sssss", $name, $email, $pass, $admin, $uid);
            $stmt->execute();
            $result = $stmt->get_result();

            include_once($_SERVER['DOCUMENT_ROOT'] . '/api/user/User.php');
            $user = new User($uid);

            header("Location: ../../setup.php");
        } else {
            header("Location: ../../setup.php?e=true&msg=Error.");
            exit();
        }

        header("Location: ../../setup.php");
    break;

    default:
        return;
}

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

function configureDatabase($DB_ADDR, $DB_PORT, $DB_USER, $DB_PASS) {
    global $setup;
    global $crypt;

    $homePath = $_SERVER['DOCUMENT_ROOT'] . '/files/';
    $dbFile = $homePath . 'database.json';

    $dbRead = file_get_contents($dbFile);
    $dbData = json_decode($dbRead, true);

    $dbData['db_address'] = $crypt->encrypt($DB_ADDR);
    $dbData['db_port'] = $crypt->encrypt($DB_PORT);
    $dbData['db_user'] = $crypt->encrypt($DB_USER);
    $dbData['db_pass'] = $crypt->encrypt($DB_PASS);

    $newJSON = json_encode($dbData, JSON_PRETTY_PRINT);
    file_put_contents($dbFile, $newJSON);

    $setup->updateDatabaseStatus(true);
}