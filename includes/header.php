<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// fix paths for assets
$path_prefix = strpos($_SERVER['PHP_SELF'], '/public/') !== false ? '' : 'public/';
$assets_prefix = strpos($_SERVER['PHP_SELF'], '/public/') !== false ? '../' : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error Management System</title>
    <link rel="stylesheet" href="<?= $assets_prefix ?>assets/css/style.css">
    <!-- fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

<nav class="navbar">
    <a href="index.php" class="navbar-brand">ERROR_LOG</a>
    <div class="nav-links">
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="index.php">Dashboard</a>
            <a href="add.php">New Log</a>
            <span>Welcome, <?= htmlspecialchars($_SESSION['username']) ?></span>
            <a href="logout.php" style="color: var(--error-color);">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php endif; ?>
    </div>
</nav>

<div class="main-content">
