<?php

/**
 * This file is used in the main DB.php class.
 * This is how the data is retrieved from database.json, decoded,
 * and then used to connect to a database.
 *
 * @since 1.0.0
 * @last_update 1.0.0
 */

include_once($_SERVER['DOCUMENT_ROOT'] . '/api/fileManager/SetupFileManager.php');
$setup = new SetupFileManager();

function isDBReady():bool
{
    global $setup;

    if($setup->getDatabaseStatus()) {
        if($setup->isDatabaseReady()) {
            return true;
        }
        return false;
    }

    return false;
}

function updateDB(bool $value) {
    $homePath = $_SERVER['DOCUMENT_ROOT'] . '/files/';
    $confFile = $homePath . 'config.json';

    $confRead = file_get_contents($confFile);
    $confData = json_decode($confRead, true);

    $confData['db_configured'] = $value;

    $newJSON = json_encode($confData, JSON_PRETTY_PRINT);
    file_put_contents($confFile, $newJSON);
}

function getDBCredentials(): array
{
    $homePath = $_SERVER['DOCUMENT_ROOT'] . '/files/';
    $dbFile = $homePath . 'database.json';

    $dbRead = file_get_contents($dbFile);
    $dbData = json_decode($dbRead, true);

    $returnMe = [];

    array_push($returnMe, $dbData['db_address'], $dbData['db_port'], $dbData['db_user'], $dbData['db_pass']);

    return $returnMe;
}