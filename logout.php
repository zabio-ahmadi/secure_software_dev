<?php
require_once 'header.php';

// Check if the user is logged in
if ($obj->loggedin($obj)) {
    // destroy the session data
    unset($_SESSION['logged_user']);
}
// Redirect the user to a sign-out confirmation or login page
header("Location: login.php");
exit;
?>