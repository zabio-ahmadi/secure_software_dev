<?php
require_once 'header.php';

if ($obj->loggedin($obj) && $obj->acountVerified($obj)) {
    header("Location: index.php");
}

$email = $_GET['email'];
$reset = $_GET['reset'];
$token = $_GET['token'];


if ((isset($_GET['email']) && !empty($email)) && (isset($_GET['token']) && !empty($token))) {

    $target_user = $obj->getUserByEmail($obj, $email);
    $verify_token = (isset($_GET['reset']) && !empty($reset) && $reset == 1) ? $target_user['password_reset_token'] : $target_user['verify_token'];




    if ($token == $verify_token) {

        $query = "update users set email_verified =1,password_reset_token=null,verify_token=null, verified_at = CURRENT_TIMESTAMP where email = '$email'";

        $result = $obj->executeQuery($query);

        $verifed = $obj->connection->affected_rows;
        if ($verifed == 1) {
            echo '
                <div class="verify_account">
                    <div class="alert alert-success" role="alert">
                        your email: ' . $email . ' has been verified
                    </div>
                    <a href="login.php" class="btn btn-primary">login</a>
                </div>
            ';
        }

    } else {
        echo '
            <div class="verify_account">
                <div class="alert alert-danger" role="alert">
                   invalid email or token
                </div>
                
            </div>
        ';
    }

} else {
    echo '
        <div class="verify_account">
            <div class="alert alert-danger" role="alert">
               invalid email or token
            </div>
            
        </div>
    ';
}
?>