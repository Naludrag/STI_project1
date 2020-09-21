<?php
session_start ();

// unset all variables and destroy the session
session_unset ();
session_destroy ();

// Redirected the user to the login page
header ('location: ../login.php');
?>