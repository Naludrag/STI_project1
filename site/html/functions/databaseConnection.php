<?php

/**
 * Group:        2
 * Authors:      Bécaud Arthur & Egremy Bruno
 * Date:         05.10.2020
 * Description:  Main function to connect to the database.
 */

/**
 * Initialize the connection with the database and in case of success return a PDO instance.
 * @return PDO|null
 */
function dbConnect() {
    // Set default timezone
    date_default_timezone_set('UTC');

    try {
        // Connect to SQLite database in file
        $connection = new PDO('sqlite:/usr/share/nginx/databases/database.sqlite', null, null, array(PDO::ATTR_PERSISTENT => true));
        // Set errormode to exceptions
        $connection->setAttribute(PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION);

        return $connection;

    } catch(PDOException $e) {
        // Print PDOException message
        echo $e->getMessage();

        return null;
    }
}

