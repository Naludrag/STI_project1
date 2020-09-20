<?php
require "functions/databaseConnection.php";

function authentication($username, $password){
    // Database connection
    $db = dbConnect();

    // Check user existe, si oui, password_verify avec son hash puis check si le compte est actif
    // Check that the username exist, then check the password (password_verify()) and finally
    // check that the account is active.
    $dbUser = $db->query('SELECT * FROM User WHERE username="' . $username . '"')->fetch();

    if ($dbUser && password_verify($password, $dbUser['passwordHash']) && $dbUser['validity']) {
        session_start ();

        // Saving the user's username to the session
        $_SESSION['username'] = $username;
        $_SESSION['admin'] = $dbUser['validity'];

        return true;
    }
    return false;
}
?>

