<?php
require_once '../config/db.php';
require_once '../includes/functions.php';

requireLogin();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // check token
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        die("Invalid CSRF token");
    }

    $title = trim($_POST['title']);
    $error_code = trim($_POST['error_code']);
    $language = trim($_POST['language']);
    $description = trim($_POST['description']);
    $solution = trim($_POST['solution']);
    $user_id = $_SESSION['user_id'];

    if (empty($title) || empty($description)) {
        $error = "Title and Description are required.";
    } else {
        $sql = "INSERT INTO errors (user_id, title, error_code, language, description, solution) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "isssss", $user_id, $title, $error_code, $language, $description, $solution);
        
        if (mysqli_stmt_execute($stmt)) {
            $success = "Error logged successfully!";
            // maybe redirect?
            // header("Location: index.php");
        } else {
            $error = "Failed to log error: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    }
}
?>
<?php include '../includes/header.php'; ?>

<div class="container">
    <div class="card">
        <div class="card-header">
            <h2>Log New Error</h2>
        </div>

        <?php if($error): ?>
            <div style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 20px;">
                <?= h($error) ?>
            </div>
        <?php endif; ?>
        
        <?php if($success): ?>
            <div style="background: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin-bottom: 20px;">
                <?= $success ?> <a href="index.php">Back to Dashboard</a>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
            
            <div class="d-flex gap-2">
                <div class="form-group" style="flex:1;">
                    <label class="form-label">Error Code</label>
                    <input type="text" name="error_code" class="form-control" placeholder="e.g. 404, ReferenceError">
                </div>
                <div class="form-group" style="flex:1;">
                    <label class="form-label">Tech</label>
                    <input type="text" name="language" class="form-control" placeholder="e.g. PHP, JavaScript">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Title <span style="color:red">*</span></label>
                <input type="text" name="title" class="form-control" required placeholder="Brief summary of the error">
            </div>

            <div class="form-group">
                <label class="form-label">Description <span style="color:red">*</span></label>
                <textarea name="description" class="form-control" rows="4" required placeholder="Detailed description of what went wrong..."></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Solution</label>
                <textarea name="solution" class="form-control" rows="4" placeholder="How did you fix it? (Optional but recommended)"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Save Log</button>
            <a href="index.php" class="btn" style="float:right; color:#666;">Cancel</a>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
