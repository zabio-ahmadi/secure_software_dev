<?php
require_once 'header.php';
if (!$obj->loggedin($obj)) {
    header("Location: login.php");
}
if (!$obj->acountVerified($obj)) {
    header("Location: verifyemail.php");
}

$reported_message_id = NULL;
$errors = NULL;
$message = NULL;
$report_body = NULL;

if (isset($_POST["reported_message_id"])) {
    $reported_message_id = $_POST["reported_message_id"];
} else {
    header("Location: index.php");
}

if (isset($_POST["report_body"]) && !empty($_POST["report_body"])) {
    $report_body = $_POST["report_body"];
} else {
    $errors = "report body shoudn't be empty";
}
if ((isset($_POST["report_body"]) && !empty($_POST["report_body"])) && (isset($reported_message_id) && !empty($reported_message_id))) {
    $query = "INSERT INTO reports VALUES (null,'$report_body', $reported_message_id);";
    $result = $obj->executeQuery($query);
    if ($result) {
        $message = 'report sended successfully';
        header("Location: index.php");
    }
}
?>



<div class="forgot_password">
    <h4 class='text-center'>Report the message </h4>
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
    <form action='' method='POST'>

        <div class="mb-3">
            <input type="text" name="reported_message_id" value="<?php echo $reported_message_id; ?>"
                style="display:none">
            <label for="report_body" class="form-label">report body</label>
            <textarea name="report_body" id="report_body" style="width: 100%;  min-height: 200px; padding:10px"
                placeholder="enter your report here"></textarea>
        </div>

        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-danger btn-sm">report</button>
        </div>
    </form>
</div>

<?php
include_once 'footer.php';
?>