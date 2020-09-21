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
        return 'grey';
    }
}

function adaptRoleText($role){
    if($role == 1){
        return 'Administrator';
    } else {
        return 'Collaborator';
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
