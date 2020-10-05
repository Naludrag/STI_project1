<?php

/**
 * Group:        2
 * Authors:      Bécaud Arthur & Egremy Bruno
 * Date:         05.10.2020
 * Description:  Collection of functions for the dashboard manager (admin page).
 */

/**
 * Return the corresponding string of a given int value of a validity.
 * @param $validity Int value of a validity.
 * @return string $validity=0 => 'Inactive', $validity=1 => 'Active'.
 */
function adaptValidityText($validity) {
    if ($validity == 1) {
        return 'Active';
    } else {
        return 'Inactive';
    }
}

/**
 * Return the corresponding color role of a given int value of a validity.
 * @param $validity Int value of a validity.
 * @return string $validity=0 => 'green', $validity=1 => 'yellow'.
 */
function adaptValidityColor($validity) {
    if ($validity == 1) {
        return 'green';
    } else {
        return 'yellow';
    }
}

/**
 * Return the corresponding string of a given int value of a role.
 * @param $role Int value of a role.
 * @return string role=0 => 'Collaborator', role=1 => 'Administrator'.
 */
function adaptRoleText($role) {
    if ($role == 1) {
        return 'Administrator';
    } else {
        return 'Collaborator';
    }
}

/**
 * Return the corresponding color as a string of a given int value of a role.
 * @param $role Int value of a role.
 * @return string role=0 => 'gray', role=1 => 'purple'.
 */
function adaptRoleColor($role) {
    if ($role == 1) {
        return 'purple';
    } else {
        return 'gray';
    }
}

/**
 * Add a user in the database.
 * @param $username User's name.
 * @param $hash User's hashed password.
 * @param $validity User's validity (0|1).
 * @param $role User's role (0=collaborator|1=administrator).
 */
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

/**
 * Delete a user identified by a username from the database.
 * @param $username
 */
function deleteUser($username) {
    if ($_SESSION['admin'] == 1) {
        $db = dbConnect();

        $sql = 'DELETE FROM User WHERE username=:username';
        $sth = $db->prepare($sql);
        $sth->execute(array(':username' => $username));

        header("Refresh:0");
    }
}

/**
 * Change the validity of a user identified by a username from the database.
 * @param $username
 * @param $currentValidity The validity of the current user.
 */
function changeValidity($username, $currentValidity) {
    $db = dbConnect();

    $sql = 'UPDATE User SET validity=:validity WHERE username=:username';
    $sth = $db->prepare($sql);
    $sth->execute(array('validity' => ($currentValidity + 1) % 2, ':username' => $username));

    header("Refresh:0");
}

/**
 * Change the role of a user identified by a username from the database.
 * @param $username
 * @param $currentRole The role of the current user.
 */
function changeRole($username, $currentRole) {
    $db = dbConnect();

    $sql = 'UPDATE User SET admin=:admin WHERE username=:username';
    $sth = $db->prepare($sql);
    $sth->execute(array('admin' => ($currentRole + 1) % 2, ':username' => $username));

    header("Refresh:0");
}
