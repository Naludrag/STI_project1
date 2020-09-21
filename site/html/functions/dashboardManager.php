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

function changeValidity($username, $currentValidity){
    echo "Current validity : " . $currentValidity . "<br>";
    if($currentValidity == 1){
        $newValidity = 0;
    } else {
        $newValidity = 1;
    }

    echo "Validity after flip : " . $newValidity . "<br>";

    $db = dbConnect();

    $sql = 'UPDATE User SET validity=:validity WHERE username=:username';
    $sth = $db->prepare($sql);
    $sth->execute(array('validity' => $newValidity, ':username' => $username));

    header("Refresh:0");
}
