<?php
require_once 'header.php';

if (!$obj->loggedin($obj)) {
    header("Location: login.php");
}
if (!$obj->acountVerified($obj)) {
    header("Location: verifyemail.php");
}
$logged_user = $_SESSION['logged_user'];
$user = $obj->getUserByEmail($obj, $logged_user);

$delete = null;

if (isset($_POST['delete'])) {
    $delete = $_POST['delete'];


    // delete user posts && posts image 
    $user_id = $obj->getUserIDByEmail($obj, $logged_user);
    $query = "SELECT * FROM posts where user_id='$user_id'";
    $result = $obj->executeQuery($query);

    while ($rows = mysqli_fetch_array($result)) {
        if (!unlink($rows['image_url'])) {
            $errors = "can't delete user profile_image";
        }
    }
    // delet user posts 
    $query = "DELETE FROM posts where user_id='$user_id'";
    $result = $obj->executeQuery($query);

    // delet user_image 
    if (!unlink($user['profile_image'])) {
        $errors = "can't delete user profile_image";
    }

    // delete user connections 
    $query = "DELETE FROM user_has_friend where user_id1='$user_id' or user_id2='$user_id'";
    $result = $obj->executeQuery($query);

    // delete user 
    $query = "DELETE FROM users where email='$logged_user'";
    $result = $obj->executeQuery($query);
    if ($result) {
        unset($_SESSION['logged_user']);
        header("Location: login.php");
    }

}

?>

<div class="settings">
    <b>
        <h6>my profile</h6>
    </b>
    <div class="profile">
        <div class="p_details">
            <div class="p_image">
                <img id="user_image_profile" src="<?php echo $user['profile_image'] ?>" alt="">
            </div>
            <div class="user-name">
                <p>
                    <?php echo $user['user_name'] ?>
                </p>
                <p>
                    <?php
                    if ($user['isAdmin'] == false) {
                        echo 'USER';
                    } else {
                        echo 'ADMIN';
                    }
                    ?>
                </p>
            </div>
        </div>
        <div class="config">
            <a href="edit_profile.php">
                <button>
                    <span>Edit</span> <i class="fa-regular fa-pen-to-square"></i>
                </button>
            </a>
        </div>
    </div>


    <div class="p_info">
        <div class="header">
            <div>
                <b>
                    <h6>personel info</h6>
                </b>
            </div>
            <div class="config">
                <a href="edit_profile.php"><button>
                        <span>Edit</span> <i class="fa-regular fa-pen-to-square"></i>
                    </button>
                </a>
            </div>
        </div>


        <div class="user_info">
            <div class="line">
                <div>
                    <label for="first_name">User Name</label>
                    <b>
                        <?php echo $user['user_name'] ?>
                    </b>
                </div>
                <div>
                    <label>Age</label>
                    <b>
                        <?php echo $user['age'] ?>
                    </b>
                </div>
            </div>


            <div class="line">
                <div>
                    <label>Email</label>
                    <b>
                        <?php echo $user['email'] ?>
                    </b>
                </div>
                <div>
                    <label>Authority</label>
                    <b>
                        <?php
                        if ($user['isAdmin'] == false) {
                            echo 'USER';
                        } else {
                            echo 'ADMIN';
                        }
                        ?>
                    </b>
                </div>
            </div>

            <div class="line">
                <div>
                    <label>TOKEN</label>
                    <p style="font-size: 10pt;margin: 0;color: black;font-weight: 600;">
                        <?php echo $user['user_token'] ?>
                    </p>
                </div>
            </div>
            <div class="line">
                <div class="profile_bio">
                    <label>Bio</label>
                    <p>
                        <?php echo $user['bio'] ?>
                    </p>
                </div>
            </div>


            <div class="line">
                <form action="" method="POST">
                    <div class="delete_acount">
                        <label>Delete Account</label>
                        <input type="text" name="delete" value="delete" style="display: none;">
                        <button type="submit">delete</button>
                    </div>
                </form>
            </div>


        </div>

    </div>
</div>

<?php
include_once 'footer.php';
?>