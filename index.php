<?php
include_once 'header.php';

$query = "SELECT * FROM posts
          LEFT JOIN users on users.id = posts.user_id;
";
$result = $obj->executeQuery($query);



?>

<div class="container m-3 d-flex justify-content-start flex-wrap">

  <?php


  $_SESSION['valid_until'] = time() + (20);

  while ($query_row = mysqli_fetch_assoc($result)) {

    echo '
      <div class="article m-3 content">
      <div class="row-8">
        <div class="card ml-3" style="max-width: 540px">
          <div class="row g-0">
            <div class="col-md-4">
              <img src="' . $query_row['image_url'] . '" class="img-scal img-fluid rounded-start" alt="..." />
            </div>
            <div class="col-md-8">
              <div class="card-body">
                <h5 class="card-title">' . $query_row['title'] . '</h5>
                <p class="card-text">
                ' . $query_row['body'] . '
                </p>
                <p class="card-text">
                 posted by @<span class="author">' . $query_row['user_name'] . '</span> 3 mins ago
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
      
      ';
  }

  ?>
</div>
<?php
include_once 'footer.php';
?>