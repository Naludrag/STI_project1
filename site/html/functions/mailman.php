<?php

require_once "functions/databaseConnection.php";

function updateMailbox(){
    header("Refresh:0");
}

function retrieveMail($username) {
    // Database connection
    $db = dbConnect();

    $sql = 'SELECT * FROM Message WHERE fk_receiver=:receiver';
    $sth = $db->prepare($sql);
    $sth->execute(array(':receiver' => $username));

    //close connection
    $db = null;

    return $sth->fetchAll();
}

function sendMail($sender, $receiver, $object, $body) {
    // Database connection
    $db = dbConnect();

    $sql = 'INSERT INTO Message (object, body, receptionDate, fk_sender, fk_receiver) VALUES (:object , :body , :date , :sender, :receiver)';
    $sth = $db->prepare($sql);
    $sth->execute(array(':object' => $object,
                        ':body' => $body,
                        ':date' => date("Y-m-d H:i:s"),
                        ':sender' => $sender,
                        ':receiver' => $receiver));

    //close connection
    $db = null;

    updateMailbox();
}

function checkOwnership($mailId, $user){
    $db = dbConnect();

    $sql = 'SELECT fk_receiver FROM Message WHERE id=:id';
    $sth = $db->prepare($sql);
    $sth->execute(array(':id' => $mailId));

    return $user == $sth->fetch()['fk_receiver'];
}

function deleteMail($mailId){
    if(checkOwnership($mailId, $_SESSION['username'])){
        $db = dbConnect();

        $sql = 'DELETE FROM Message WHERE id=:id';
        $sth = $db->prepare($sql);
        $sth->execute(array(':id' => $mailId));

       updateMailbox();
    }
}