<?php
session_start();

// Check if the user is logged in
if (isset($_SESSION['logged_user'])) {
    // destroy the session data
    unset($_SESSION['logged_user']);
}
// Redirect the user to a sign-out confirmation or login page
header("Location: login.php");
exit;
?>