<?php

/**
 * Group:        2
 * Authors:      Bécaud Arthur & Egremy Bruno
 * Date:         05.10.2020
 * Description:  Logout file.
 */

session_start ();

// unset all variables and destroy the session
session_unset ();
session_destroy ();

// Redirected the user to the login page
header ('location: ../login.php');
?>
