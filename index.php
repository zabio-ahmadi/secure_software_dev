<?php
include_once 'header.php';

if (!$obj->loggedin($obj)) {
  header("Location: login.php");
}

if (!$obj->acountVerified($obj)) {

  header("Location: verifyemail.php");
}


$query = "SELECT * FROM posts
          LEFT JOIN users on users.id = posts.user_id;
";
$result = $obj->executeQuery($query);



?>

<div class="d-flex justify-content-start flex-wrap">

  <div class="twit">
    <div class="twit-owner">
      <div class="owner-image">
        <img src="uploads/profile.jpg" alt="">
      </div>
      <div class="owner-username">
        <p>
          <a href=""><span>zabiullah ahmadi</span></a>
          <span class="material-icons post__badge"> verified
          </span>
        </p>
        <p>@USER</p>
      </div>

    </div>
    <div class="twit-header">
      Lorem ipsum dolor, sit amet consectetur adipisicing elit. Aliquam deserunt impedit possimus quis! Omnis fugiat
      sequi nostrum beatae optio sint, dolor mollitia delectus recusandae distinctio. Id ex doloribus voluptas
      veritatis.
    </div>
    <div class="twit-body">
      <img src="uploads/tesla.jpeg" alt="">
    </div>
    <div class="twit-footer">
      <div class="twit-date">
        <p>12:09 PM 10 Nov 2023</p>
      </div>

      <div class="share_like">
        <i class="fa-regular fa-comment"></i>
        <i class="fa-solid fa-arrow-up-right-from-square"></i>
        <i class="fa-regular fa-heart"></i>
        <i class="fa-regular fa-bookmark"></i>
      </div>

    </div>


  </div>


  <div class="twit">
    <div class="twit-owner">
      <div class="owner-image">
        <img src="uploads/profile.jpg" alt="">
      </div>
      <div class="owner-username">
        <p>
          <a href=""><span>zabiullah ahmadi</span></a>
          <span class="material-icons post__badge"> verified
          </span>
        </p>
        <p>@USER</p>
      </div>

    </div>
    <div class="twit-header">
      Lorem ipsum dolor, sit amet consectetur adipisicing elit. Aliquam deserunt impedit possimus quis! Omnis fugiat
      sequi nostrum beatae optio sint, dolor mollitia delectus recusandae distinctio. Id ex doloribus voluptas
      veritatis.
    </div>
    <div class="twit-body">
      <img src="uploads/nature.jpeg" alt="">
    </div>
    <div class="twit-footer">
      <div class="twit-date">
        <p>12:09 PM 10 Nov 2023</p>
      </div>

      <div class="share_like">
        <i class="fa-regular fa-comment"></i>
        <i class="fa-solid fa-arrow-up-right-from-square"></i>
        <i class="fa-regular fa-heart"></i>
        <i class="fa-regular fa-bookmark"></i>
      </div>

    </div>


  </div>



</div>
<?php
include_once 'footer.php';
?>