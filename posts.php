<?php
require_once 'header.php';

// Your time variable in seconds
$timeInSeconds = $_SESSION['valid_until']; // Replace this with your actual time variable

// Convert seconds to a timestamp
$timestamp = strtotime("@" . $timeInSeconds);

// Format the timestamp as desired
$formattedTime = date("Y-m-d H:i:s", $timestamp);

// Output the formatted time
echo "current time : ", date("Y-m-d H:i:s", strtotime("@" . time())), "<br>";
echo "expiration time : ", $formattedTime;

$valid = isset($_SESSION['valid_until']) && $_SESSION['valid_until'] > time();

if (!isset($_SESSION['logged_user']) || !$valid) {
    header("Location: login.php");
}



$errors = null;
$message = null;
$post_title = null;
$post_body = null;

$postImageName = null;
$postImageTmpName = null;
$fileSize = null;
$fileError = null;


if (isset($_POST['post_title'])) {
    $post_title = $_POST['post_title'];
} else {
    $errors = 'post title must be present';
}

if (isset($_POST['post_body'])) {
    $post_body = $_POST['post_body'];
} else {
    $errors = 'post body must be present';
}
// Get the file details
if (isset($_FILES["post_image"]["name"])) {

    $postImageName = $_FILES["post_image"]["name"];
    $postImageTmpName = $_FILES["post_image"]["tmp_name"];
    $fileSize = $_FILES["post_image"]["size"];
    $fileError = $_FILES["post_image"]["error"];

}

if (isset($post_title) && isset($post_body) && isset($postImageName)) {
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
            $query = "INSERT INTO posts VALUE (null,'$post_title','$post_body', '$uniqueFileName', '$user_id');";
            $result = $obj->executeQuery($query);

            // refresh session time
            $_SESSION['valid_until'] = time() + (20);

            $message = 'post created successfully';
        } else {
            $errors = "Error uploading file.";
        }
    } else {
        $errors = "Error during file upload: " . $fileError;
    }
}


?>




<div class="content">
    <div class="register_post">
        <form action="posts.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <h5 class="text-primary"> create a new post </h5>

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
                <label for="title" class="form-label">Post Title</label>
                <input type="text" name="post_title" class="form-control" id="title" placeholder="let's build something"
                    value="<?php echo $post_title; ?>">
            </div>
            <div class="mb-3">
                <label for="post_body" class="form-label">post body</label>
                <textarea class="form-control" id="post_body" name="post_body" rows="3"
                    value="<?php echo $post_body; ?>"></textarea>
            </div>

            <div class="mb-3">
                <label for="post_image" class="form-label">post image</label>
                <input class="form-control" type="file" name="post_image" id="post_image" multiple>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary mb-3">create post</button>
            </div>

        </form>
    </div>
</div>



</div>
<?php
include_once 'footer.php';
?>