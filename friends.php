<?php
require_once 'header.php';

if (!$obj->loggedin($obj)) {

    header("Location: login.php");
}
if (!$obj->acountVerified($obj)) {
    header("Location: verifyemail.php");
}

?>

<div class="freind-list">
    <div class="title">
        Connections
    </div>
    <div class="friend-list-box">
        <?php
        for ($i = 0; $i < 10; $i++) {
            echo '<div class="friends">
                <div class="friends-image">
                    <img src="uploads/profile.jpg" alt="">
                </div>
                <div class="friend-details">
                    <p>ahmadi zabiullah</p>
                    <p>@USER</p>
                </div>
            </div>
        ';
        }
        ?>
    </div>
</div>

<?php
include_once 'footer.php';
?>