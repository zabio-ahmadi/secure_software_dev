<?php
require_once 'header.php';

if (!$obj->loggedin($obj)) {
    header("Location: login.php");
}
if (!$obj->acountVerified($obj)) {
    header("Location: verifyemail.php");
}

if (!$obj->isAdmin($obj)) {
    header("Location: " . $_SERVER['HTTP_REFERER']);
}


$query = "SELECT * FROM users where isAdmin=0";
$users = $obj->executeQuery($query);


$post_id = NULL;
if (isset($_POST["delete_post"]) && !empty(isset($_POST["delete_post"]))) {
    $post_id = $_POST["delete_post"];


    // delete reported post image 
    $query = "SELECT * FROM posts where id='$post_id'";
    $result = $obj->executeQuery($query);

    // delete reported post id 
    $query = "DELETE FROM reports where post_id='$post_id'";
    $result = $obj->executeQuery($query);

    while ($rows = mysqli_fetch_array($result)) {
        if (!unlink($rows['image_url'])) {
            $errors = "can't delete post image";
        }
    }

    // delete reported post 
    $query = "DELETE FROM posts where id='$post_id'";
    $result = $obj->executeQuery($query);
    header("Location: " . $_SERVER['HTTP_REFERER']); // to refresh

}

$toggle_disabled_user = NULL;
if (isset($_POST["toggle_disabled_user"]) && !empty(isset($_POST["toggle_disabled_user"]))) {
    $toggle_disabled_user = $_POST["toggle_disabled_user"];
    $query = "UPDATE users SET active= NOT active where id =$toggle_disabled_user;";
    $result = $obj->executeQuery($query);
    header("Location: " . $_SERVER['HTTP_REFERER']);
}

$delete_user_id = NULL;
if (isset($_POST["delete_user_id"]) && !empty(isset($_POST["delete_user_id"]))) {
    $delete_user_id = $_POST["delete_user_id"];
    $user = $obj->getUserById($obj, $delete_user_id);

    // delete user posts && posts image 
    $query = "SELECT * FROM posts where user_id='$delete_user_id'";
    $result = $obj->executeQuery($query);

    while ($rows = mysqli_fetch_array($result)) {

        // delete user report 
        $query = "DELETE FROM reports where post_id='" . $row['id'] . "'";
        $result = $obj->executeQuery($query);

        if (!unlink($rows['image_url'])) {
            $errors = "can't delete user profile_image";
        }
    }

    // delet user posts 
    $query = "DELETE FROM posts where user_id='$delete_user_id'";
    $result = $obj->executeQuery($query);

    // delet user_image 
    if (!unlink($user['profile_image'])) {
        $errors = "can't delete user profile_image";
    }

    // delete user connections 
    $query = "DELETE FROM user_has_friend where user_id1='$delete_user_id' or user_id2='$delete_user_id'";
    $result = $obj->executeQuery($query);

    // delete user 
    $query = "DELETE FROM users where id='$delete_user_id'";
    $result = $obj->executeQuery($query);
}
?>

<div class="admin-page">
    <h3>User Management</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">User Name </th>
                <th scope="col">Profile Photo </th>
                <th scope="col">Email</th>
                <th scope="col">User Type</th>
                <th scope="col">Edit</th>
                <th scope="col">Status</th>
                <th scope="col">Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = mysqli_fetch_array($users)) {
                $isAdmin = ($row['isAdmin'] == 1) ? '@ADMIN' : '@USER';
                echo ' <tr>
               <td>' . $row['user_name'] . '</td>
               <td><img src="' . $row['profile_image'] . '" alt=""></td>
               <th scope="row">' . $row['email'] . '</th>
               <th>' . $isAdmin . '</th>
               <td>
                    <form action="edit_profile.php" method="POST">
                        <input type="text" name="profile_email" value="' . $row['email'] . '" style="display:none">
                        <button type="submit" class="btn btn-sm btn-primary">edit <i class="fa-solid fa-pen-to-square"></i></button>
                    </form>
               </td>

               <td>
                    <form action="" method="POST">
                        <input type="text" name="toggle_disabled_user" value="' . $row['id'] . '" style="display:none">
                        ';

                if ($row['active'] == 1) {
                    echo '<button type="submit" class="btn btn-sm btn-warning">enable <i class="fa-solid fa-user-shield"></i></button>';
                } else {
                    echo '<button type="submit" class="btn btn-sm btn-warning">disable <i class="fa-solid fa-user-large-slash"></i></button>';
                }
                echo '
                    </form>
               </td>
               <td>
                    <form action="" method="POST">
                        <input type="text" name="delete_user_id" value="' . $row['id'] . '" style="display:none">
                        <button type="submit" class="btn btn-sm btn-danger">Delete <i class="fa-solid fa-trash"></i></button>
                    </form>
               </td>
           </tr>';
            }
            ?>
        </tbody>
    </table>
</div>

<?php
$query = "SELECT * FROM reports
LEFT JOIN posts ON reports.post_id = posts.id
LEFT JOIN users ON posts.user_id = users.id";
$reports = $obj->executeQuery($query);


?>
<div class="admin-page">
    <h3>reported message</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Posted by </th>
                <th scope="col">Post title</th>
                <th scope="col">Post image </th>
                <th scope="col">Post content </th>
                <th scope="col">Reported messages</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = mysqli_fetch_array($reports)) {
                echo ' <tr>
               <td><b>' . $row['user_name'] . '</b></td>
               <td><b>' . $row['title'] . '</b></td>
               <td><img classe="reported_image" src="' . $row['image_url'] . '" alt=""></td>
               <th><p>' . $row['body'] . '</p></th>
               <th><p>' . $row['report_body'] . '</p></th>
               <td>
                    <form action="" method="POST">
                    <input type="text" name="delete_post" value="' . $row['post_id'] . '" style="display:none">
                    <button type="submit" class="btn btn-sm btn-danger">Delete <i class="fa-solid fa-trash"></i></button></td>
                    </form>
               </td>
               
           </tr>';
            }
            ?>
        </tbody>
    </table>
</div>