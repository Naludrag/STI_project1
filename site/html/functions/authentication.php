
<?php

/**
 * Group:        2
 * Authors:      Bécaud Arthur & Egremy Bruno
 * Date:         05.10.2020
 * Description:  Collection of authentication functions.
 */

require "databaseConnection.php";
require "humanResources.php";
require "securityUtils.php";

/**
 * Try to authenticate a user from given username and password
 * and set session variables 'username' and 'admin'.
 * @param $username Username used for the authentication.
 * @param $password Password used fot the authentication.
 * @return bool Return true if authentication was successful.
 */
function authentication($username, $password) {
    // Database connection
    $db = dbConnect();

    // Check that the username exist, then check the password (password_verify()) and finally
    // check that the account is active.
    $userDetails = retrieveUser($username);

    // Close connection
    $db = null;

    // NEW : Added constant time authentification
    if (SecurityUtils::constant_time_authentication($password, $userDetails) && $userDetails['validity']) {
        // Saving the user's username to the session
        $_SESSION['username'] = $username;
        $_SESSION['admin'] = $userDetails['admin'];

        return true;
    }
    return false;
}


