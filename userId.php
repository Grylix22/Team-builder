<?php
header('Content-Type: text/plain');
require_once './partials/main.php';

$controller = new UserController();
$model = new UserModel($controller);

if (isset($_POST['user_id'])) {
    $userId = $_POST['user_id'];
    $controller->deleteUser($userId);
    unset($_POST['user_id']);


    $response = "\n";
    echo $response;
    $responseData = array('message' => $response);
    header('Content-Type: application/json');
} else {
    $response = "Error: no received data";
}
?>