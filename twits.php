<?php
require_once 'header.php';

if (!$obj->loggedin($obj)) {
    header("Location: login.php");
}
if (!$obj->acountVerified($obj)) {
    header("Location: verifyemail.php");
}



$errors = null;
$message = null;
$post_title = ' ';
$post_body = ' ';

$postImageName = null;
$postImageTmpName = null;
$fileSize = null;
$fileError = null;

$post_url_image = null;


if (isset($_POST['post_title'])) {
    $post_title = $_POST['post_title'];
}
if ($post_title == null) {
    $errors = 'post title must be present';
}

if (isset($_POST['post_body'])) {
    $post_body = $_POST['post_body'];
}
if ($post_body == null) {
    $errors = 'post body must be present';
}
// Get the file details
if (isset($_FILES["post_image"]["name"])) {
    $postImageName = $_FILES["post_image"]["name"];
    $postImageTmpName = $_FILES["post_image"]["tmp_name"];
    $fileSize = $_FILES["post_image"]["size"];
    $fileError = $_FILES["post_image"]["error"];

}
if (isset($postImageName)) {
    $errors = "your post should have a image file";
}

if (isset($_POST['post_url_image'])) {
    $post_url_image = $_POST['post_url_image'];
}


//if ((isset($post_title) && $post_title != null) && (isset($post_body) && $post_body != null) && isset($postImageName)) {
if ((isset($post_title) && $post_title != null) && (isset($post_body) && $post_body != null)) {

    // check if the file comes from url : default url 
    if (isset($post_url_image) && ((strncmp($post_url_image, 'https://', 8) === 0) || strncmp($post_url_image, 'http://', 7) === 0)) {
        // create posts 
        $user_id = $obj->getUserIdByEmail($obj, $_SESSION['logged_user']);
        $query = "INSERT INTO posts VALUE (null,'$post_title','$post_body', '$post_url_image',CURRENT_TIMESTAMP, '$user_id');";
        $result = $obj->executeQuery($query);

        // refresh session time
        $_SESSION['valid_until'] = $obj->USER_SESSION_DURATION;
        $message = 'post created successfully';


    } else if (isset($postImageName)) {
        // errors during upload
        if ($fileError === 0) {
            // upload directory 
            $uploadDir = "uploads/";

            // Generate a unique name for the uploaded file
            $uniqueFileName = $uploadDir . uniqid() . "_" . $postImageName;

            $user_id = $obj->getUserIdByEmail($obj, $_SESSION['logged_user']);


            // Move the file from the temporary location to the desired directory
            if (move_uploaded_file($postImageTmpName, $uniqueFileName)) {
                // create posts 
                $query = "INSERT INTO posts VALUE (null,'$post_title','$post_body', '$uniqueFileName',CURRENT_TIMESTAMP, '$user_id');";
                $result = $obj->executeQuery($query);

                // refresh session time
                $_SESSION['valid_until'] = $obj->USER_SESSION_DURATION;

                $message = 'post created successfully';
            } else {
                $errors = "Error uploading file.";
            }
        }
    }

}
?>

<div class="content">
    <div class="register_post">
        <form action="twits.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <h6> create a new twit </h6>

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
            </div>

            <div class="mb-3">
                <label for="title" class="form-label">Twit Title</label>
                <input type="text" name="post_title" class="form-control" id="title" placeholder="let's build something"
                    value="<?php echo $post_title; ?>">
            </div>
            <div class="mb-3">
                <label for="post_body" class="form-label">Twit body</label>
                <textarea class="form-control" id="post_body" name="post_body" rows="3"
                    placeholder='create your first post' value="<?php echo $post_body; ?>"></textarea>
            </div>

            <div class="mb-3">
                <label for="post_image" class="form-label">Twit image</label>
                <input type="file" class="form-control" name="post_image" id="post_image" multiple>
            </div>

            <div class="mb-3">
                <label for="post_url_image" class="form-label">Twit image from url</label>
                <input type="url" class="form-control" name="post_url_image" id="post_url_image">
            </div>


            <div class="col-auto">
                <button type="submit" class="btn btn-primary mb-3">create Twit</button>
            </div>

        </form>
    </div>
</div>


<?php
include_once 'footer.php';
?>