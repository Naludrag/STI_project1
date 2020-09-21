<?php
require_once "functions/databaseConnection.php";

function retrieveUser($username) {
    // Database connection
    $db = dbConnect();

    $sql = 'SELECT * FROM User WHERE username=:username';
    $sth = $db->prepare($sql);
    $sth->execute(array(':username' => $username));

    return $sth->fetch();
}

function retrieveUsers($includeInactiveUsers) {
    // Database connection
    $db = dbConnect();

    $sql = 'SELECT username, validity, admin FROM User WHERE validity=:validity';
    $sth = $db->prepare($sql);
    $sth->execute(array(':validity' => $includeInactiveUsers));

    return $sth->fetchAll();
}
