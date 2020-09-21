<?php
require "functions/databaseConnection.php";

function authentication($username, $password){
    // Database connection
    $db = dbConnect();

    //$db = DB::connect();

    // Check user existe, si oui, password_verify avec son hash puis check si le compte est actif
    // Check that the username exist, then check the password (password_verify()) and finally
    // check that the account is active.
    $sql = 'SELECT * FROM User WHERE username=:username';
    $sth = $db->prepare($sql);
    $sth->execute(array(':username' => $username));
    $userDetails = $sth->fetch();

    // Close connection
    $db = null;

    if ($userDetails && password_verify($password, $userDetails['passwordHash']) && $userDetails['validity']) {

        // Saving the user's username to the session
        $_SESSION['username'] = $username;
        $_SESSION['admin'] = $userDetails['validity'];

        return true;
    }
    return false;
}


