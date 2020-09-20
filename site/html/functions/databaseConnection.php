<?php
function dbConnect(){
    // Set default timezone
    date_default_timezone_set('UTC');

    try {
        /**************************************
         * Open connections                    *
         **************************************/

        // Connect to SQLite database in file
        $connection = new PDO('sqlite:/usr/share/nginx/databases/database.sqlite');
        // Set errormode to exceptions
        $connection->setAttribute(PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION);

        echo "Returning connection from databaseConnection.php<br>";
        return $connection;

    } catch(PDOException $e) {
        // Print PDOException message
        echo $e->getMessage();

        return null;
    }
}

