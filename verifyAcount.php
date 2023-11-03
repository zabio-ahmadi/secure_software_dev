<?php
require_once 'header.php';

if ($obj->loggedin($obj) && $obj->acountVerified($obj)) {
    header("Location: index.php");
}

$email = $_GET['email'];
$reset = $_GET['reset'];
$token = $_GET['token'];


if ((isset($_GET['token']) && !empty($token))) {


    $decrypted_token = $obj->decrypt("$token");

    $json_obj = json_decode($decrypted_token);

    $calculated_hash_of_message = $obj->encrypt(json_encode($json_obj->message));

    // check the generated hash : here we are not sure that the message is not altered 
    if (($json_obj->hash_of_message == $calculated_hash_of_message)) {


        $target_user = $obj->getUserByEmail($obj, $json_obj->message->email);

        $verify_token = ($json_obj->reset == 1) ? $target_user['password_reset_token'] : $target_user['verify_token'];


        // verify_token and sended token is the same : means that the token is not changed 
        if ($verify_token == $json_obj->message->token && $target_user['email'] == $json_obj->message->email) {

            // message is not altered and 
            $email = $target_user['email'];

            $query = "UPDATE users SET active=1, email_verified =1,password_reset_token=null,verify_token=null, verified_at = CURRENT_TIMESTAMP where email = '$email'";

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


            } else {
                echo '
                    <div class="verify_account">
                        <div class="alert alert-danger" role="alert">
                           error email verification
                        </div>

                    </div>
                ';
            }

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