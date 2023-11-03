<?php

include 'connection.php';
$obj = new Connection();

// request method 
$requestMethod = $_SERVER['REQUEST_METHOD'];

// user token 
$userToken = $_SERVER['HTTP_XAPITOKEN'];

$requestPath = $_SERVER['REQUEST_URI'];

$requestPath = explode('/', $requestPath);
// send a JSON response

$key = array_search('api.php', $requestPath);
unset($requestPath[$key]);
unset($requestPath[0]);

function sendResponse($data)
{
    header('Content-Type: application/json');
    echo json_encode($data);
}

function parseSQLResult($result)
{
    $data = [];
    while ($row = mysqli_fetch_assoc($result))
        array_push($data, $row);
    return $data;
}

function sendBadRequest()
{
    header("HTTP/1.1 401 Unauthorized");
    sendResponse(["message", "un authorized request"]);
}





if ($userToken == '') {
    sendBadRequest();
    die();
}

$query = "SELECT * FROM users WHERE user_token = '$userToken'";
$result = $obj->executeQuery($query);
$data = mysqli_fetch_assoc($result);

if (empty($data)) {
    sendBadRequest();
    die();
}
if (count($requestPath) > 2) {
    sendBadRequest();
} else if (1 == 2) {

} else {

    $isAdmin = $data["isAdmin"];
    $saved_token = $data["user_token"];

    if (count($requestPath) == 1 && $requestPath[2] == 'messages' && $requestMethod == 'GET') {
        // read all messages : GET /messages with admin token 
        if ($userToken == $saved_token && $isAdmin == 1) {
            $query = "SELECT * FROM posts;";
            $result = $obj->executeQuery($query);
            sendResponse(parseSQLResult($result));
        } else if ($userToken == $saved_token && $isAdmin == 0) {

            $query = "SELECT * FROM posts where user_id = " . $data['id'] . ";";
            $result = $obj->executeQuery($query);
            sendResponse(parseSQLResult($result));

        }
    }
    // GET /message/<Id>
    // > Header XAPITOKEN token (if admin token, read all messages)
    else if (count($requestPath) == 2 && $requestPath[2] == 'messages' && !empty($requestPath[3]) && $requestMethod == 'GET') {
        if ($userToken == $saved_token && $isAdmin == 1) {

            $query = "SELECT * FROM posts where id = $requestPath[3];";
            $result = $obj->executeQuery($query);
            sendResponse(parseSQLResult($result));
        } else if ($userToken == $saved_token && $isAdmin == 0) {

            $query = "SELECT * FROM posts where user_id = " . $data['id'] . " AND id = " . $requestPath[3] . ";";
            $result = $obj->executeQuery($query);
            sendResponse(parseSQLResult($result));

        }

    } else if (count($requestPath) == 1 && $requestPath[2] == 'messages' && $requestMethod == 'POST') {

        if ($userToken == $saved_token && $isAdmin == 0) {
            $requestBody = file_get_contents("php://input");

            // You can then parse the request body, for example, if it's JSON data:
            $recieved_data = json_decode($requestBody, true);

            $query = "INSERT INTO posts VALUES (NULL, '" . $recieved_data['title'] . "','" . $recieved_data['body'] . "', '" . $recieved_data['image_url'] . "', CURRENT_TIMESTAMP, " . $data['id'] . ") ;";
            $result = $obj->executeQuery($query);
            if ($result) {
                sendResponse(['message' => 'post created successfully']);
            }
        }
    } else {
        sendBadRequest();
    }
}

?>