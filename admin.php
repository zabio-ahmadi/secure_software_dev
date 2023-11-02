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


$query = "SELECT * FROM users";
$users = $obj->executeQuery($query);


?>

<div class="admin-page">
    <h3>admin page</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">User Name </th>
                <th scope="col">Profile Photo </th>
                <th scope="col">Email</th>
                <th scope="col">User Type</th>
                <th scope="col">Suspend</th>
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
               <td><button type="button" class="btn btn-sm btn-warning">suspend <i
                           class="fa-solid fa-stop"></i></button>
               </td>
               <td><button type="button" class="btn btn-sm btn-danger">Delete <i
                           class="fa-solid fa-trash"></i></button></td>
           </tr>';
            }
            ?>
        </tbody>
    </table>

</div>