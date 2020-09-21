<?php
require_once "functions/databaseConnection.php";

function retrieveUser($username) {
    // Database connection
    $db = dbConnect();

    return $db->query('SELECT username, validity, admin FROM User WHERE username="' . $username . '"');
}

function retrieveUsers($includeInactiveUsers) {
    // Database connection
    $db = dbConnect();

    return $db->query('SELECT username, validity, admin FROM User WHERE validity=' . $includeInactiveUsers);
}
