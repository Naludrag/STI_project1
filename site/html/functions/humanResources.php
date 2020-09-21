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

function checkIfPasswordsMatch($password, $newPassword) {
    return $password == $newPassword;
}
function isUsernameUsed($username) {
    $users = retrieveUsers(0);

    // Check if username already exist
    foreach ($users as $user) {
        if($user['username'] == $username) {
            return true;
        }
    }
    return false;
}

function addUser($username, $hash, $validity, $role) {
    // Database connection
    $db = dbConnect();

    $sql = 'INSERT INTO User (username, passwordHash, validity, admin) VALUES (:username , :hash , :validity , :role)';
    $sth = $db->prepare($sql);
    $sth->execute(array(':username' => $username,
        ':hash' => $hash,
        ':validity' => $validity,
        ':role' => $role));

    //close connection
    $db = null;

    header("Refresh:0");
}
