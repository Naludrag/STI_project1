<?php
    require "functions/databaseConnection.php";

function retrieveMail($username) {
    // Database connection
    $db = dbConnect();

    return $db->query('SELECT * FROM Message WHERE fk_receiver="' . $username . '"');
}