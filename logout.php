<?php
session_start();
 
// Unset all of the session variables
$_SESSION = array();
 
// Destroy the session.
//session_destroy();
 $_SESSION['alerts']['danger'] = "You have signed out.";
 
// Redirect to login page
header("location: login.php");
exit;
?>