
<?php
require_once "classes/user.php";
$activeLog = 0;
$userType  = "user"; // buyer header

$auth = new Auth($userType);

if ($auth->isLoggedIn()) {
    $activeLog = 1;
    $user      = $auth->getUser();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Website</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            padding-top: 80px; /* ensure content clears fixed navbar */
            background-color: #f8f9fa;
        }
        .navbar-brand {
            font-weight: 700;
            letter-spacing: 1px;
        }
        .nav-link,
        .nav-item span {
            color: #fff !important;
            font-weight: 500;
        }
        /* Small screens: give more top spacing when navbar wraps */
        @media (max-width: 575px) {
            body { padding-top: 110px; }
        }
        /* Footer helper class for consistent spacing */
        .site-footer { padding-top: 1.25rem; padding-bottom: 1.25rem; }
    </style>
</head>
<body>

<!-- Header / Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="index.php">MyWebsite</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav ms-auto align-items-lg-center">

            <?php if ($activeLog): ?>
  <li class="nav-item">  <span>Welcome, <?= htmlspecialchars($user['name']) ?></span> </li>
                <li class="nav-item ms-lg-3">
                    <a class="btn btn-outline-light btn-sm" href="logout.php">Logout</a>
                </li>
    <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Login</a>
                </li>
                <li class="nav-item ms-lg-2">
                    <a class="btn btn-primary btn-sm" href="register.php">Register</a>
                </li>

  <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
