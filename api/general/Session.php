<?php

/**
 * This class is used to initialize a session for the user.
 *
 * @author jiggey
 * @since 1.0.0
 * @last_update 31/05/2023 (1.0.0)
 */
class Session
{

    public function __construct() {
        // Create a session that expires after x amount of time.
        ini_set("session.gc_maxlifetime", 7200); // Not sure how long this is... 2 hours?? 5??..
        session_start();
    }

}