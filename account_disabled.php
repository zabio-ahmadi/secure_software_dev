<?php
require_once 'header.php';
if ($obj->acountActive($obj) && $obj->acountVerified($obj)) {
    header("Location: index.php");
}
$loged_user_email = $_SESSION['logged_user'];
?>

<div class="verify_email">
    <p>your account has been disabled for some reason </p>
    <p>please contact the admin to solve this problem !</p>
</div>