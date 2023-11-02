<?php
require_once 'header.php';

if (!$obj->loggedin($obj)) {
    header("Location: login.php");
}
if (!$obj->acountVerified($obj)) {
    header("Location: verifyemail.php");
}

if (isset($_POST["friend"]) && !empty($_POST["friend"])) {

    $freind_id = $_POST["friend"];
    $logged_user = $_SESSION['logged_user'];
    $user_id = $obj->getUserIdByEmail($obj, $logged_user);

    // check if two user has freind relationship 
    $query = "SELECT * FROM user_has_friend WHERE (user_id1 = '$user_id' AND user_id2='$freind_id') OR (user_id2 = '$user_id' AND user_id1='$freind_id');";

    $result = $obj->executeQuery($query);

    // there is no relation between : already freinds
    if (mysqli_num_rows($result) == 0) {
        $query = "INSERT INTO user_has_friend VALUE ('$user_id', '$freind_id', 0);";

        $result = $obj->executeQuery($query);
        if ($result) {
            // redirect 
            header("Location: index.php");
        }
    }






}
?>