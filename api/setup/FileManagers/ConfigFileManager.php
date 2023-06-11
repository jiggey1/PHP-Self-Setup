<?php

/**
 * This class is only used when setting up the website.
 * This handles the getting and setting for the data
 * to be placed inside of config.json (such as updating configured + setup status)
 *
 * @author jiggey
 * @since 1.0.0
 * @last_update 2.0.0
 */
class ConfigFileManager
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
    public function doesConfigFileExist(): bool
    {
        if(file_exists($this->path . 'config.json')) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isDatabaseSetup():bool {
        // Check if the website has gone through the setup or not.
        $homePath = $_SERVER['DOCUMENT_ROOT'] . '/files/';
        $confFile = $homePath . 'config.json';

        $confRead = file_get_contents($confFile);
        $confData = json_decode($confRead, true);

        return $confData['db_setup'];
    }

    /**
     * @return bool
     */
    public function isDatabaseConfigured():bool {
        // Check if the website has gone through the setup or not.
        $homePath = $_SERVER['DOCUMENT_ROOT'] . '/files/';
        $confFile = $homePath . 'config.json';

        $confRead = file_get_contents($confFile);
        $confData = json_decode($confRead, true);

        return $confData['db_configured'];
    }

    /**
     * @return bool
     */
    public function isConfigFileFormatted():bool
    {
        // Check if the website has gone through the setup or not.
        $confFile = $this->path . 'config.json';

        $confRead = file_get_contents($confFile);
        $confData = json_decode($confRead, true);

        if(isset($confData['db_setup']) || isset($confData['db_configured'])) {
            return true;
        }

        return false;
    }

    /**
     * @param bool $arg
     * @return void
     */
    public function updateDbSetup(bool $arg):void
    {
        // Check if the website has gone through the setup or not.
        $homePath = $_SERVER['DOCUMENT_ROOT'] . '/files/';
        $confFile = $homePath . 'config.json';

        $confRead = file_get_contents($confFile);
        $confData = json_decode($confRead, true);

        $confData['db_setup'] = $arg;

        file_put_contents($confFile, json_encode($confData, JSON_PRETTY_PRINT));
    }

    /**
     * @param bool $arg
     * @return void
     */
    public function updateDbConfig(bool $arg):void
    {
        // Check if the website has gone through the setup or not.
        $homePath = $_SERVER['DOCUMENT_ROOT'] . '/files/';
        $confFile = $homePath . 'config.json';

        $confRead = file_get_contents($confFile);
        $confData = json_decode($confRead, true);

        $confData['db_configured'] = $arg;

        file_put_contents($confFile, json_encode($confData, JSON_PRETTY_PRINT));
    }

    /**
     * @return void
     */
    public function formatConfigFile(): void
    {
        // Check if the website has gone through the setup or not.
        $homePath = $_SERVER['DOCUMENT_ROOT'] . '/files/';
        $confFile = $homePath . 'config.json';

        $confRead = file_get_contents($confFile);
        $confData = json_decode($confRead, true);

        $confData['db_setup'] = false;
        $confData['db_configured'] = false;

        file_put_contents($confFile, json_encode($confData, JSON_PRETTY_PRINT));
    }

}