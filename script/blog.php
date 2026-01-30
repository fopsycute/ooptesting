
<?php
header('Content-Type: application/json');
require_once "../classes/user.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid request"
    ]);
    exit;
}

if ($_POST['action'] == 'addforum') {
  

$userId  = $_POST['user'] ?? '';
$title   = $_POST['title'] ?? '';
$article = $_POST['article'] ?? '';
$tags    = $_POST['tags'] ?? '';
$image   = $_FILES['featured_image'] ?? null;

$forum = new Forum($userId, $title, $article, $tags, $image);
$response = $forum->create();

echo json_encode($response);

}