<?php
require_once '../config/db.php';
require_once '../includes/functions.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // security check
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        include '../includes/header.php';
        echo '<div class="container"><div class="alert alert-danger">
            <h3>Security Verification Failed</h3>
            <p>Invalid Request (CSRF Token Mismatch). Please try again.</p>
            <a href="index.php" class="btn btn-primary mt-3">Back to Dashboard</a>
        </div></div>';
        include '../includes/footer.php';
        exit();
    }

    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

    if ($id) {
        // check if they are allowed to delete this
        $sql = "SELECT user_id FROM errors WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);

        if ($row) {
            if ($row['user_id'] == $_SESSION['user_id']) {
                $del_sql = "DELETE FROM errors WHERE id = ?";
                $del_stmt = mysqli_prepare($conn, $del_sql);
                mysqli_stmt_bind_param($del_stmt, "i", $id);
                mysqli_stmt_execute($del_stmt);
            } else {
                 // Access Denied for logic
                include '../includes/header.php';
                echo '<div class="container"><div class="alert alert-danger">
                    <h3>Access Denied</h3>
                    <p>You can only delete your own error logs.</p>
                    <a href="index.php" class="btn btn-primary mt-3">Back to Dashboard</a>
                </div></div>';
                include '../includes/footer.php';
                exit();
            }
        }
    }
}

header("Location: index.php");
exit();
?>

