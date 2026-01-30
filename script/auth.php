
<?php
header('Content-Type: application/json');
require_once "../classes/user.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        "status"   => "error",
        "messages" => "Invalid request method"
    ]);
    exit;
}

if ($_POST['action'] == 'register') {
$fullname         = $_POST['fullname'] ?? '';
$email            = $_POST['email'] ?? '';
$password         = $_POST['password'] ?? '';
$confirmPassword  = $_POST['confirm_password'] ?? '';

$user = new User($fullname, $email, $password, $confirmPassword);
$response = $user->register();

echo json_encode($response);

}


if ($_POST['action'] == 'login') {
$email    = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

$user = new User('', $email, $password, '');
$response = $user->login();

echo json_encode($response);

}

?>