<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// kick user to login if they aren't authorized
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit();
    }
}

// sanitize output for safety
function h($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

// csrf stuff
function generateCsrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// verify token
function verifyCsrfToken($token) {
    if (isset($_SESSION['csrf_token']) && is_string($token) && hash_equals($_SESSION['csrf_token'], $token)) {
        return true;
    }
    return false;
}
?>
