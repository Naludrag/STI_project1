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

function retrieveUsers($onlyActiveUsers) {
    // Database connection
    $db = dbConnect();
    $sth = null;

    if($onlyActiveUsers){
        $sql = 'SELECT username, validity, admin FROM User WHERE validity=:validity';
        $sth = $db->prepare($sql);
        $sth->execute(array(':validity' => 1));
    } else{
        $sql = 'SELECT username, validity, admin FROM User';
        $sth = $db->prepare($sql);
        $sth->execute();
    }

    return $sth->fetchAll();
}

function changeUserPassword($username, $hash) {
    // Database connection
    $db = dbConnect();

    $sql = 'UPDATE User SET passwordHash=:hash WHERE username=:username';
    $sth = $db->prepare($sql);
    $sth->execute(array(':hash'     => $hash,
                        ':username' => $username));

    //close connection
    $db = null;
}
