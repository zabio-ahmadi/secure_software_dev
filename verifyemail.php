<?php
require_once 'header.php';
if (!$obj->loggedin($obj)) {
    header("Location: login.php");
}
if ($obj->acountVerified($obj)) {
    header("Location: index.php");
}
$loged_user_email = $_SESSION['logged_user'];
?>

<div class="verify_email">
    <p>an email has been send to
        <b>
            <?php echo $loged_user_email;
            ?>
        </b>
    </p>
    <p>please confirm your account !</p>
</div>