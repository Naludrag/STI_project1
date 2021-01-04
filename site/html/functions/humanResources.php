<?php

/**
 * Group:        2
 * Authors:      Bécaud Arthur & Egremy Bruno
 * Date:         05.10.2020
 * Description:  Collection of functions about users.
 *               Most functions used by admin page were moved to the dashboardManager.php file.
 */

require_once "functions/databaseConnection.php";

/**
 * Retrieve the data of user from the database.
 * @param $username
 * @return mixed
 */
function retrieveUser($username) {
    // Database connection
    $db = dbConnect();

    $sql = 'SELECT * FROM User WHERE username=:username';
    $sth = $db->prepare($sql);
    $sth->execute(array(':username' => $username));

    return $sth->fetch();
}

/**
 * Retrieve all users from the database.
 * @param $onlyActiveUsers Retrieve only active user if true.
 * @return array
 */
function retrieveUsers($onlyActiveUsers) {
    // Database connection
    $db = dbConnect();
    $sth = null;

    if ($onlyActiveUsers) {
        $sql = 'SELECT username, validity, admin FROM User WHERE validity=:validity';
        $sth = $db->prepare($sql);
        $sth->execute(array(':validity' => 1));
    } else {
        $sql = 'SELECT username, validity, admin FROM User';
        $sth = $db->prepare($sql);
        $sth->execute();
    }

    return $sth->fetchAll();
}

/**
 * Change user's password.
 * @param $username
 * @param $hash User's hashed password.
 */
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

/**
 * Check if the two given passwords match.
 * @param $password1
 * @param $password2
 * @return bool Return true if passwords match.
 */
function checkIfPasswordsMatch($password1, $password2) {
    return $password1 == $password2;
}

/**
 * Check if a username exist in the database.
 * @param $username User's name to search in the database.
 * @return bool Return true if the user's name exist.
 */
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
