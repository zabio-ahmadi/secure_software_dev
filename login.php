<?php

require_once 'header.php';
$provided_email = NULL;
$provided_password = NULL;
$errors = null;

if ($obj->loggedin($obj)) {
    header("Location: index.php");
}

if (isset($_POST['email'])) {
    $provided_email = $_POST['email'];
}

if (isset($_POST['password'])) {
    $provided_password = $_POST['password'];
}


if (isset($provided_email) && isset($provided_password)) {

    // check if user exists with provided email 
    $query = "SELECT * FROM users where email = '$provided_email';";

    $result = $obj->executeQuery($query);
    $email = '';
    $password = '';
    while ($query_row = mysqli_fetch_assoc($result)) {
        $email = $query_row['email'];
        $password = $query_row['password'];
    }

    if (password_verify($provided_password, $password)) {
        // Password is correct

        $target_user = $obj->getUserByEmail($obj, $email);

        if ($target_user['active'] == 0) {
            header("Location: account_disabled.php");
        } else if ($target_user['email_verified'] == 0) {
            header("Location: verifyemail.php");
        } else {

            $_SESSION['logged_user'] = $email;
            $_SESSION['valid_until'] = $obj->USER_SESSION_DURATION;
            header("Location: index.php");
        }





    } else {
        // Password is incorrect
        $errors = "invalid email or password";
    }

}



?>

<div class="container m-3 d-flex justify-content-start flex-wrap">

    <div class="content">
        <div class="login">
            <h2 class='text-center'>login</h2>
            <text class='text-danger text-center'>
                <?php
                if ($errors != null) {
                    echo $errors;
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
                    <label for="exampleInputPassword1" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" id="exampleInputPassword1">
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary btn-sm">Login</button>
                    <a class="btn btn-outline-info btn-sm" href="register.php">Sign up</a>
                </div>
                <div class="mt-3">
                    <a class="btn btn-outline-danger btn-sm" href="forgotpassword.php">forgot password</a>
                </div>
            </form>
        </div>
    </div>

</div>
<?php
require_once 'footer.php';
?>