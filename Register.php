<?php
require_once 'header.php';

if ($obj->loggedin($obj)) {
    if (!$obj->acountVerified($obj)) {
        header("Location: verifyemail.php");
    } else {
        header("Location: index.php");
    }
}



$provided_user_name = NULL;
$provided_email = NULL;
$provided_password = NULL;
$provided_age = NULL;
$provided_bio = NULL;
$errors = NULL;
$message = NULL;


if (isset($_POST['user_name'])) {
    $provided_user_name = $_POST['user_name'];
}

if (isset($_POST['email'])) {
    $provided_email = $_POST['email'];
}

if (isset($_POST['password'])) {
    $provided_password = $_POST['password'];
}

if (isset($_POST['age'])) {
    $provided_age = $_POST['age'];
}

if (isset($_POST['bio'])) {
    $provided_bio = $_POST['bio'];
}


if (isset($provided_email) && isset($provided_password) && isset($provided_age)) {

    // check if user already exists 
    $query = "SELECT * FROM users where email = '$provided_email';";
    $result = $obj->executeQuery($query);
    $num_rows = mysqli_num_rows($result);

    // user already exists
    if ($num_rows == 1) {
        $errors = "user with email $provided_email already exists";
    } else {
        // hashed password 
        $hashed_password = password_hash($provided_password, PASSWORD_BCRYPT);

        // signup token 
        $token = hash('sha256', time() . $provided_email . 'BX');


        $message = ['email' => $provided_email, 'token' => $token]; // we can add the valid_until time also 

        $verification_token = ['message' => $message, 'reset' => 0, 'hash_of_message' => $obj->encrypt(json_encode($message))];

        $encrypted_token = $obj->encrypt(json_encode($verification_token));


        $query = "INSERT INTO `users` VALUE (null, '$provided_user_name', '$provided_age','$provided_email',null,'$hashed_password', '$provided_bio', 0, 0, '$token', null, null);";
        $result = $obj->executeQuery($query);


        $body = '
            <p>click on this link to verify your account</p>
            <p><b><a href="localhost/verifyAcount.php?token=' . $encrypted_token . '">confirm your account</a></b></p>
        ';

        $sended = $obj->sendMail($provided_email, 'confirm your account', $body);

        if ($sended) {
            $message = 'user created successfully';
        }



    }
}





?>

<div class="container m-3 d-flex justify-content-start flex-wrap">

    <div class="content">
        <div class="signup">
            <h2 class='text-center'>Register</h2>

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


            <form action="" method="POST">
                <div class="mb-3">
                    <label for="user_name" class="form-label">user name</label>
                    <input type="text" name="user_name" class="form-control" id="user_name"
                        aria-describedby="user_name">
                </div>

                <div class="mb-3">
                    <label for="age" class="form-label">Age</label>
                    <input type="number" name="age" class="form-control" id="age" aria-describedby="ageHelp">
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" name="email" class="form-control" id="email" aria-describedby="emailHelp">
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" id="password">
                </div>

                <div class="mb-3">
                    <label for="bio" class="form-label">Bio</label>
                    <input type="text" name="bio" class="form-control" id="bio" aria-describedby="bioHelp">
                </div>



                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary btn-sm">Signup</button>
                </div>
            </form>
        </div>
    </div>

</div>
<?php
include_once 'footer.php';
?>