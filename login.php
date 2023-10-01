<?php

require_once 'header.php';
$provided_email = NULL;
$provided_password = NULL;
$errors = null;


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

        $_SESSION['logged_user'] = $email;
        $_SESSION['valid_until'] = time() + (20);

        header("Location: posts.php");

    } else {
        // Password is incorrect
        $errors = "invalid email or password";
    }

}



?>

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
                <button type="submit" class="btn btn-primary">Login</button>
                <a href="signup.php">Sign up</a>
            </div>
        </form>
    </div>
</div>

</div>
<?php
require_once 'footer.php';
?>