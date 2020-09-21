<?php
    require_once "functions/databaseConnection.php";

function retrieveMail($username) {
    // Database connection
    $db = dbConnect();
    return $db->query('SELECT * FROM Message WHERE fk_receiver="' . $username . '"');
}

function sendMail($sender, $receiver, $object, $body) {
    // Database connection
    $db = dbConnect();
    $db->exec("INSERT INTO Message (object, body, receptionDate, fk_sender, fk_receiver) VALUES ('OBJECT', 'BODY', '2001-03-10 17:16:18', 'richard', 'patrick')");

    return;

    $db->exec("INSERT INTO Message (object, body, receptionDate, fk_sender, fk_receiver) 
                VALUES ('" . $object ."', '" . $body ."', CURRENT_TIMESTAMP , '" . $sender . "', '" . $receiver . "')");
}