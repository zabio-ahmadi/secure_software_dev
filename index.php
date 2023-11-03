<?php
include_once 'header.php';

if (!$obj->loggedin($obj)) {
  header("Location: login.php");
}

if (!$obj->acountVerified($obj)) {

  header("Location: verifyemail.php");
}
$loged_user_email = $_SESSION['logged_user'];
$loged_user_id = $obj->getUserIdByEmail($obj, $loged_user_email);


$query = "SELECT u.id, u.user_name,u.profile_image, f1.*, f2.*
FROM users u
LEFT JOIN user_has_friend f1 ON u.id = f1.user_id1 AND f1.user_id2 = $loged_user_id
LEFT JOIN user_has_friend f2 ON u.id = f2.user_id2 AND f2.user_id1 = $loged_user_id
WHERE u.email != '$loged_user_email' AND u.id != $loged_user_id
AND (f1.user_id2 IS NULL AND f2.user_id1 IS NULL);
";

$users = $obj->executeQuery($query);



?>

<div class="friend_proposal">
  <div class="header">
    Peoples you may know
  </div>
  <div class="proposal">
    <?php
    while ($row = mysqli_fetch_array($users)) {
      echo '<div class="person">
      <div class="person_image">
        <img src="' . $row['profile_image'] . '" alt="">
      </div>
      <div class="person_content">
        <div class="person_details">
          <b>' . $row['user_name'] . '</b>
        </div>
        <div class="connect">
          <form action="add_friend.php" method="post">
          <input type="hidden" name="friend" value="' . $row['id'] . '">
            <button type="submit">Follow</button>
          </form>
            
        </div>
      </div>
    </div>';
    }
    ?>
  </div>


</div>



<?php
$query = "SELECT posts.id as post_id, title, body, image_url, posted_at, user_id, user_name, email, profile_image FROM posts
LEFT JOIN users on users.id = posts.user_id order by posted_at DESC;
";
$posts = $obj->executeQuery($query);

while ($row = mysqli_fetch_array($posts)) {
  $isAdmin = '';
  if ($row['isAdmin'] == false) {
    $isAdmin = '@USER';
  } else {
    $isAdmin = '@ADMIN';
  }

  echo '
      <div class="d-flex justify-content-start flex-wrap">
      <div class="twit">
        <div class="twit-owner">
          <div class="owner-image">
            <img src="' . $row['profile_image'] . '" alt="">
          </div>
          <div class="owner-username">
            <p>
              <a href=""><span>' . $row['user_name'] . '</span></a>
              <span class="material-icons post__badge"> verified
              </span>
            </p>
            <p>' . $isAdmin . '</p>
          </div>

        </div>
        <div class="twit-header">
          <h4>' . $row['title'] . '</h4>
          ' . $row['body'] . '
        </div>
        <div class="twit-body">
          <img src="' . $row['image_url'] . '" alt="">
        </div>
        <div class="twit-footer">
          <div class="twit-date">
            <p>' . $row['posted_at'] . '</p>
          </div>

          <div class="share_like">
            <i class="fa-regular fa-comment"></i>
            <i class="fa-solid fa-arrow-up-right-from-square"></i>
            <i class="fa-regular fa-heart"></i>
            <form action="report_message.php" method="POST">
              <input type="text" name="reported_message_id" value="' . $row['post_id'] . '" style="display:none;">
              <button type="submit"><i class="fa-solid fa-bug"></i></button>
            </form>
          </div>
        </div>
      </div>
    </div>
  ';
}

?>


<?php
include_once 'footer.php';
?>