<?php
require_once 'header.php';
$provided_email = '';
$provided_password = '';
$errors = null;
$message = null;




if (isset($_POST['email']) && !empty($_POST['email'])) {
    $provided_email = $_POST['email'];
}
if (isset($_POST['email']) && empty($_POST['email'])) {
    $errors = 'email should\'nt be empty';

}


if (isset($_POST['password']) && !empty($_POST['password'])) {
    $provided_password = $_POST['password'];
}
if (isset($_POST['password']) && empty($_POST['password'])) {
    $errors = 'password is required';
}


if (!empty($_POST['email']) && !empty($_POST['password'])) {

    $query = "select * from users where email='$provided_email';";
    $result = mysqli_fetch_assoc($obj->executeQuery($query));

    if (!empty($result)) {

        // hashed password 
        $hashed_password = password_hash($provided_password, PASSWORD_BCRYPT);


        $token = hash('sha256', time() . $provided_email . 'BX');

        // signup token 
        $token = hash('sha256', time() . $provided_email . 'BX');


        $message = ['email' => $provided_email, 'token' => $token];

        $verification_token = ['message' => $message, 'reset' => 1, 'hash_of_message' => $obj->encrypt(json_encode($message))];


        $encrypted_token = $obj->encrypt(json_encode($verification_token));


        $query = "update users set email_verified= 0 , password='$hashed_password', password_reset_token = '$token', verified_at = current_timestamp where email='$provided_email';";
        $result = $obj->executeQuery($query);
        if ($result) {
            $body = '
            <p>click on this link below to reset password</p>
            <p><b><a href="localhost/verifyAcount.php?token=' . $encrypted_token . '">confirm reset password</a></b></p>
        ';

            $sended = $obj->sendMail($provided_email, 'confirm reset password', $body);

            if ($sended) {
                $message = "your password has been changed please verify your email";
            }
        }

    } else {
        $errors = 'email doesn\'t exists ';
    }



}
?>

<div class="forgot_password">
    <h4 class='text-center'>Reset Password</h4>
    <text class='text-danger text-center'>
        <?php
        if ($errors != null) {
            echo $errors;
        }
        ?>
    </text>

    <text class='text-success text-center'>
        <?php
        if ($message != null) {
            echo $message;
        }
        ?>
    </text>
    <form action='' method='POST'>
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Email address</label>
            <input type="text" name="email" value="<?php echo $provided_email ?>" class="form-control"
                id="exampleInputEmail1" aria-describedby="emailHelp">
        </div>

        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">new password</label>
            <input type="password" name="password" value="<?php echo $provided_password ?>" class="form-control"
                id="exampleInputPassword1">
        </div>

        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-primary">Reset</button>
        </div>
    </form>
</div>


<?php
include_once 'footer.php';
?>