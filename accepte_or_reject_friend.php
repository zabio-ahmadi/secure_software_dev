<?php
require_once 'header.php';

if (!$obj->loggedin($obj)) {
    header("Location: login.php");
}
if (!$obj->acountVerified($obj)) {
    header("Location: verifyemail.php");
}
$loged_user_email = $_SESSION['logged_user'];
$user_id = $obj->getUserIdByEmail($obj, $loged_user_email);

if (isset($_POST["accepte"]) && !empty($_POST["accepte"])) {


    $accpeted_user_id = $_POST["accepte"];

    // accepte friend request 
    $query = "UPDATE user_has_friend SET accepted = 1 where user_id2 = '$user_id' AND user_id1 = '$accpeted_user_id';";
    $result = $obj->executeQuery($query);

    // create a bidirectional relation 
    $query = "INSERT INTO user_has_friend VALUE ('$user_id', '$accpeted_user_id', 1);";
    $result = $obj->executeQuery($query);
}

if (isset($_POST["rejecte"]) && !empty($_POST["rejecte"])) {

    $rejected_user_id = $_POST["rejecte"];

    // rejecte friend request 
    $query = "DELETE FROM user_has_friend where user_id2 = '$user_id' AND user_id1 = '$rejected_user_id';";
    $result = $obj->executeQuery($query);

}

header("Location: " . $_SERVER['HTTP_REFERER']);

?>