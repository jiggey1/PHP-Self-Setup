<?php

// Includes
include_once($_SERVER['DOCUMENT_ROOT'] . "/api/general/init.php");
global $con;

class User
{

    // All User data
    public String $user_email;
    public String $user_uname;
    public String $uid;
    public String $is_moderator;
    public String $is_admin;
    public bool $is_support;

    // This takes a user ID to search the database and retrieve the needed files.
    public function __construct($uid) {
        global $con;

        if($stmt = $con->con->prepare("SELECT * FROM users WHERE uid = ?")) {
            $stmt->bind_param("s", $uid);
            $stmt->execute();
            $result = $stmt->get_result();

            if($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Setup the user variables using the database code.
                    $this->user_email = $row['email'];
                    $this->user_uname = $row['username'];
                    $this->uid = $row['uid'];
                    $this->is_admin = $row['is_admin'];

                    // Update / Set the session variables.
                    $_SESSION['loggedin'] = True;
                    $_SESSION['email'] = $this->user_email;
                    $_SESSION['username'] = $this->user_uname;
                    $_SESSION['uid'] = $this->uid;
                    $_SESSION['is_admin'] = $this->is_admin;

                    // Find out if the user is a support team member or not
                    if($_SESSION['is_admin'] == 1) {
                        // Set the classes is_support variable to the is_support session, which is then set to 1 (true)
                        $this->is_admin = $_SESSION['is_admin'] = 1;
                    }
                }
            } else {
                // Error here
            }
        }
    }

    // Is the user a support member?
    // We check if is_moderator OR is_support is true.
    // We return true if one/both are, else we return false.
    public function is_support():bool {
        if($this->is_moderator == 1 || $this->is_support == 1) {
            return true;
        } else {
            return false;
        }
    }

    // Is the user an admin?
    // We check if is_admin is true.
    // We return true if it is, else we return false.
    public function is_admin():bool {
        if($this->is_admin == 1) {
            return true;
        } else {
            return false;
        }
    }

}