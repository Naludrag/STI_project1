<?php

/**
 * Group:        2
 * Authors:      Bécaud Arthur & Egremy Bruno
 * Date:         05.10.2020
 * Description:  Collection of mail functions.
 */

require_once "functions/databaseConnection.php";

/**
 * Retrieve the mail(s) of a user.
 * @param $username
 * @return array
 */
function retrieveMail($username) {
    // Database connection
    $db = dbConnect();

    $sql = 'SELECT * FROM Message WHERE fk_receiver=:receiver ORDER BY receptionDate DESC';
    $sth = $db->prepare($sql);
    $sth->execute(array(':receiver' => $username));

    //close connection
    $db = null;

    return $sth->fetchAll();
}

/**
 * Send a email.
 * @param $sender
 * @param $receiver
 * @param $object
 * @param $body
 */
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

    header("Refresh:0");
}

/**
 * Check the ownership of a mail.
 * @param $mailId
 * @param $user
 * @return bool True if the mail's receiver equal to given user.
 */
function checkOwnership($mailId, $user){
    $db = dbConnect();

    $sql = 'SELECT fk_receiver FROM Message WHERE id=:id';
    $sth = $db->prepare($sql);
    $sth->execute(array(':id' => $mailId));

    return $user == $sth->fetch()['fk_receiver'];
}

/**
 * Delete a mail.
 * @param $mailId
 */
function deleteMail($mailId){
    if(checkOwnership($mailId, $_SESSION['username'])){
        $db = dbConnect();

        $sql = 'DELETE FROM Message WHERE id=:id';
        $sth = $db->prepare($sql);
        $sth->execute(array(':id' => $mailId));

        header("Refresh:0");
    }
}
