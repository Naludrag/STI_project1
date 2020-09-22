<?php

function adaptValidityText($validity){
    if($validity == 1){
        return 'Active';
    } else {
        return 'Inactive';
    }
}

function adaptValidityColor($validity){
    if($validity == 1){
        return 'green';
    } else {
        return 'yellow';
    }
}


function adaptRoleText($role){
    if($role == 1){
        return 'Administrator';
    } else {
        return 'Collaborator';
    }
}

function adaptRoleColor($role){
    if($role == 1){
        return 'purple';
    } else {
        return 'gray';
    }
}

function deleteUser($username){
    if($_SESSION['admin'] == 1){
        $db = dbConnect();

        $sql = 'DELETE FROM User WHERE username=:username';
        $sth = $db->prepare($sql);
        $sth->execute(array(':username' => $username));

        header("Refresh:0");
    }
}

function changeValidity($username, $currentValidity){
    $db = dbConnect();

    $sql = 'UPDATE User SET validity=:validity WHERE username=:username';
    $sth = $db->prepare($sql);
    $sth->execute(array('validity' => ($currentValidity + 1) % 2, ':username' => $username));

    header("Refresh:0");
}

function changeRole($username, $currentRole) {
    $db = dbConnect();

    $sql = 'UPDATE User SET admin=:admin WHERE username=:username';
    $sth = $db->prepare($sql);
    $sth->execute(array('admin' => ($currentRole + 1) % 2, ':username' => $username));

    header("Refresh:0");
}
