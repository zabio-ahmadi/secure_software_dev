<?php
require_once 'header.php';

if (!$obj->loggedin($obj)) {
    header("Location: login.php");
}
if (!$obj->acountVerified($obj)) {
    header("Location: verifyemail.php");
}

$logged_user = NULL;
$user = NULL;

// if logged user is admin 
if (isset($_POST["profile_email"])) {

    $logged_user = $_POST["profile_email"];
    $user = $obj->getUserByEmail($obj, $logged_user);

} else {
    $logged_user = $_SESSION['logged_user'];
    $user = $obj->getUserByEmail($obj, $logged_user);
}





$provided_user_name = NULL;
$provided_password = NULL;
$provided_age = NULL;
$provided_bio = NULL;
$errors = NULL;
$message = NULL;

$profileImageName = null;
$profileImageTmpName = null;
$fileSize = null;
$fileError = null;

if (isset($_POST['user_name'])) {
    $provided_user_name = $_POST['user_name'];
}


if (isset($_FILES["profile_image"]["name"])) {
    $profileImageName = $_FILES["profile_image"]["name"];
    $profileImageTmpName = $_FILES["profile_image"]["tmp_name"];
    $fileSize = $_FILES["profile_image"]["size"];
    $fileError = $_FILES["profile_image"]["error"];
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


if (
    isset($provided_user_name)
    && isset($provided_age)
    && isset($provided_bio)
    && !empty($profileImageName)
    && !empty($provided_password)
) {
    // hashed password 
    $hashed_password = password_hash($provided_password, PASSWORD_BCRYPT);
    // errors during upload
    if ($fileError === 0) {
        // delete pervious image_profile 

        if (!unlink($user['profile_image'])) {
            $errors = "can't delete old image";
        }

        // upload directory 
        $uploadDir = "uploads/";

        // Generate a unique name for the uploaded file
        $uniqueFileName = $uploadDir . uniqid() . "_" . $profileImageName;
        // Move the file from the temporary location to the desired directory
        if (move_uploaded_file($profileImageTmpName, $uniqueFileName)) {
            $query = "UPDATE `users` SET user_name= '$provided_user_name',password='$hashed_password', profile_image='$uniqueFileName', age='$provided_age',bio='$provided_bio' where email='$logged_user'";
            $result = $obj->executeQuery($query);
            if ($result) {
                $message = 'profile updated successfuly';
                $user = $obj->getUserByEmail($obj, $logged_user);
            }
        } else {
            $errors = "Error uploading file.";
        }
    }


} else if (
    isset($provided_user_name)
    && isset($provided_age)
    && isset($provided_bio)
) {

    if (empty($profileImageName) && empty($provided_password)) {
        $query = "UPDATE `users` SET user_name= '$provided_user_name', age='$provided_age',bio='$provided_bio' where email='$logged_user'";
        $result = $obj->executeQuery($query);

        if ($result) {
            $message = 'profile updated successfuly';
            $user = $obj->getUserByEmail($obj, $logged_user);
        }
    }

    // don't want change profile_image 
    else if (empty($profileImageName) && !empty($provided_password)) {
        // hashed password 
        $hashed_password = password_hash($provided_password, PASSWORD_BCRYPT);

        $query = "UPDATE `users` SET user_name= '$provided_user_name',password='$hashed_password', age='$provided_age',bio='$provided_bio' where email='$logged_user'";
        $result = $obj->executeQuery($query);

        if ($result) {
            $message = 'profile updated successfuly';
            $user = $obj->getUserByEmail($obj, $logged_user);
        }

    } else if (!empty($profileImageName) && empty($provided_password)) {
        if ($fileError === 0) {
            // delete pervious image_profile 

            if (!unlink($user['profile_image'])) {
                $errors = "can't delete old image";
            }
            // upload directory 
            $uploadDir = "uploads/";

            // Generate a unique name for the uploaded file
            $uniqueFileName = $uploadDir . uniqid() . "_" . $profileImageName;
            // Move the file from the temporary location to the desired directory
            if (move_uploaded_file($profileImageTmpName, $uniqueFileName)) {
                $query = "UPDATE `users` SET user_name= '$provided_user_name', profile_image='$uniqueFileName', age='$provided_age',bio='$provided_bio' where email='$logged_user'";
                $result = $obj->executeQuery($query);
                if ($result) {
                    $message = 'profile updated successfuly';
                    $user = $obj->getUserByEmail($obj, $logged_user);
                }
            } else {
                $errors = "Error uploading file.";
            }
        }
    }
}

?>

<div class="container m-3 d-flex justify-content-start flex-wrap">

    <div class="content">
        <div class="signup">
            <h2 class='text-center'>Edit Profile</h2>

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
            <form action="" method="POST" enctype="multipart/form-data">

                <div class="mb-3">
                    <label for="user_name" class="form-label">User Name</label>
                    <input type="text" name="profile_email" value="<?php echo $user['email'] ?>" style="display:none">
                    <input value="<?php echo $user['user_name'] ?>" type="text" name="user_name" class="form-control"
                        id="user_name" aria-describedby="user_name">
                </div>

                <div class="mb-3">
                    <label for="age" class="form-label">Age</label>
                    <input value="<?php echo $user['age'] ?>" type="number" name="age" class="form-control" id="age"
                        aria-describedby="ageHelp">
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Profile Image </label>
                    <input type="file" name="profile_image" class="form-control" id="profile_photo" multiple>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" id="password">
                </div>

                <div class="mb-3">
                    <label for="bio" class="form-label">Bio</label>
                    <textarea name="bio" class="form-control" id="bio"
                        aria-describedby="bioHelp"><?php echo $user['bio'] ?></textarea>

                </div>



                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-danger">submit</button>
                </div>
            </form>
        </div>
    </div>

</div>
<?php
include_once 'footer.php';
?>