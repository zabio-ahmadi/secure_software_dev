<?php
require_once 'header.php';

if (!$obj->loggedin($obj)) {
    header("Location: login.php");
}
if (!$obj->acountVerified($obj)) {
    header("Location: verifyemail.php");
}

$logged_user_mail = $_SESSION['logged_user'];
$logged_user_id = $obj->getUserIdByEmail($obj, $logged_user_mail);

$target_user = null;
$target_user_mail = null;
$target_user_id = null;
$messages = NULL;

function getMessages($obj, $logged_user_id, $target_user_id)
{
    $query = "SELECT * FROM user_has_messages
    WHERE user_id1 = $logged_user_id AND user_id2 = $target_user_id OR (user_id1 = $target_user_id  AND user_id2 = $logged_user_id) order by sended_at;";
    $messages = $obj->executeQuery($query);
    return $messages;
}
if (isset($_POST["target_user_mail"]) && !empty($_POST["target_user_mail"])) {
    $target_user_mail = $_POST["target_user_mail"];
    $target_user_id = $obj->getUserIdByEmail($obj, $target_user_mail);
    $target_user = $obj->getUserByEmail($obj, $target_user_mail);

    $messages = getMessages($obj, $logged_user_id, $target_user_id);




} else {
    header("Location: " . $_SERVER['HTTP_REFERER']);
}





if (isset($_POST["message"]) && !empty($_POST["message"]) && isset($_POST["target_user_mail"]) && !empty($_POST["target_user_mail"])) {

    // send message 
    $message = htmlentities($_POST["message"]);


    $message = mysqli_escape_string($obj->getConnection(), $message);

    $query = "INSERT INTO user_has_messages VALUE ($logged_user_id , $target_user_id, '$message', CURRENT_TIMESTAMP);";
    $result = $obj->executeQuery($query);

    $messages = getMessages($obj, $logged_user_id, $target_user_id);


}

?>

<div class="messages">
    <div class="message-dist">
        <div class="message-dist-image">
            <img src="uploads/profile.jpg" alt="">
        </div>
        <div class="message-dist-username">
            <p>
                <span>
                    <?php echo $target_user['user_name'] ?>
                </span>
                <span class="material-icons post__badge"> verified</span>
            </p>
            <p>
                <?php
                if ($user['isAdmin'] == false) {
                    echo '@USER';
                } else {
                    echo '@ADMIN';
                }
                ?>
            </p>
        </div>

    </div>
    <div class="message-box">

        <?php

        while ($row = mysqli_fetch_assoc($messages)) {
            if ($row['user_id1'] == $logged_user_id) {
                echo '
                    <div class="me">
                        <p>' . $row['message'] . '</p>
                    </div>
                ';
            } else {
                echo '
                    <div class="target">
                        <p>' . $row['message'] . '</p>
                    </div>
                ';
            }
        }
        ?>
    </div>
    <div class="input-message">
        <form action="messages.php" method="POST">
            <input type="text" name="target_user_mail" value="<?php echo $target_user_mail; ?>" style="display:none">
            <input type="text" name="message" placeholder="write your message">
            <button type="submit">
                <span>send</span>
                <span>
                    <i class="fa-solid fa-paper-plane"></i>
                </span>
            </button>
        </form>
    </div>
</div>
<?php
include_once 'footer.php';
?>