<?php

// Includes
include_once($_SERVER['DOCUMENT_ROOT'] . "/api/setup/FileManagers/FileManager.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/api/setup/Crypt/Encryption.php");
$fm = new FileManager();
$crypt = new Encryption();

/**
 * This file is a self-contained API. It is to be used
 * when NONE of the website has been configured yet.
 * This is the main class to set up the project to work in any environment.
 *
 * @author jiggey
 * @since 1.0.0
 * @last_update 2.0.0
 */

if(isset($_GET['request'])) {
    $case = $_GET['request'];
} else {
    $case = '';
}

switch($case) {

    case 'setupDatabase':

        if (!isset($_POST['db_host']) || !isset($_POST['db_port']) || !isset($_POST['db_user']) || !isset($_POST['db_pass'])) {
            http_response_code(500);
            exit("This is currently not available. Please provide valid database information.");
        }

        if($fm->dbFile->testDatabase($_POST['db_host'], $_POST['db_port'], $_POST['db_user'], $_POST['db_pass'])) {
            if($fm->dbFile->setupDatabase($_POST['db_host'], $_POST['db_port'], $_POST['db_user'], $_POST['db_pass'])) {
                $fm->configFile->updateDbSetup(true);
                http_response_code(200);
                header("Location: ../../setup.php?e=false&msg=Database Linked. Ready to proceed!");
                exit();
            } else {
                http_response_code(500);
                exit("Error occurred somewhere. Please try again later.");
            }
        } else {
            http_response_code(500);
            exit("Incorrect Database Configuration. Make sure your MySQL server is running and you have provided VALID credentials.");
        }

    break;

    case 'setupDatabaseEnvironment':
        if(!$fm->isDbSetup()) {
            http_response_code(500);
            exit();
        }

        $dbInfo = $fm->dbFile->getDbInfo();
        $tempCon = mysqli_connect($crypt->decrypt($dbInfo[0]), $crypt->decrypt($dbInfo[2]), $crypt->decrypt($dbInfo[3]), null, $crypt->decrypt($dbInfo[1]));

        if($stmt = $tempCon->prepare("CREATE DATABASE `tick_system`")) {
            $stmt->execute();
            $stmt->store_result();
        }

        $tempCon->close();

        $con = mysqli_connect($crypt->decrypt($dbInfo[0]), $crypt->decrypt($dbInfo[2]), $crypt->decrypt($dbInfo[3]), 'tick_system', $crypt->decrypt($dbInfo[1]));

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
            $fm->configFile->updateDbConfig(true);
        }

        header("Location: ../../setup.php");

        break;

    case 'createAdminUser':
        if(!$fm->configFile->isDatabaseConfigured()) {
            http_response_code(500);
            exit();
        }

        include_once($_SERVER['DOCUMENT_ROOT'] . "/api/general/init.php");
        global $con;

        if($fm->adminAccount()) {
            http_response_code(500);
            exit("Admin account already exists.");
        }

        if(!isset($_POST['admin_name']) || !isset($_POST['admin_email']) || !isset($_POST['admin_pass']) || !isset($_POST['admin_pass_conf'])) {
            header("Location: ../../setup.php?e=true&msg=Bad Admin Credentials.");
            exit();
        }

        $name = $_POST['admin_name'];
        $email = $_POST['admin_email'];
        $pass = $_POST['admin_pass'];
        $pass_conf = $_POST['admin_pass_conf'];
        $admin = 1;

        if($pass !== $pass_conf) {
            http_response_code(401);
            exit("Passwords Didn't Match.");
        }

        $pass_prot = password_hash($pass, PASSWORD_DEFAULT);

        $uid = random_int(1000, 9999);

        if($stmt = $con->con->prepare("INSERT INTO users (username, email, password, is_admin, uid) VALUES (?, ?, ?, ?, ?) ")) {
            $stmt->bind_param("sssss", $name, $email, $pass_prot, $admin, $uid);
            $stmt->execute();
            $result = $stmt->get_result();

            //include_once($_SERVER['DOCUMENT_ROOT'] . '/api/user/User.php');
            //$user = new User($uid);

            header("Location: ../../setup.php?e=false&msg=You wouldve been logged in and the setup is done.");
        } else {
            header("Location: ../../setup.php?e=true&msg=Error.");
            exit();
        }

        header("Location: ../../setup.php");
        break;


    default:
        // Nothing -- no errors.
    break;
}