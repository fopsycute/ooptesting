
<?php
require_once "classes/user.php";

$auth = new Auth();
$auth->logout();

// Optional: destroy session if used later
if (session_status() === PHP_SESSION_ACTIVE) {
    session_destroy();
}

// Redirect after logout
header("Location: login.php?logged_out=1");
exit;
