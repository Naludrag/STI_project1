<?php
require "databaseConnection.php";
require "humanResources.php";

function authentication($username, $password){
    // Database connection
    $db = dbConnect();

    // Check that the username exist, then check the password (password_verify()) and finally
    // check that the account is active.
    $userDetails = retrieveUser($username);

    // Close connection
    $db = null;

    if ($userDetails && password_verify($password, $userDetails['passwordHash']) && $userDetails['validity']) {

        // Saving the user's username to the session
        $_SESSION['username'] = $username;
        $_SESSION['admin'] = $userDetails['admin'];

        return true;
    }
    return false;
}


