<?php
require_once '../config/db.php';
require_once '../includes/functions.php';

requireLogin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$error = '';
$success = '';

// get the current data
$sql = "SELECT * FROM errors WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

if (!$row) {
    include '../includes/header.php';
    echo '<div class="container"><div class="alert alert-danger">
        <h3>Error Not Found</h3>
        <p>The error log you are looking for does not exist.</p>
        <a href="index.php" class="btn btn-primary mt-3">Back to Dashboard</a>
    </div></div>';
    include '../includes/footer.php';
    exit();
}

// make sure they own this
// make sure they own this
if ($row['user_id'] != $_SESSION['user_id']) {
    include '../includes/header.php';
    echo '<div class="container"><div class="alert alert-danger">
        <h3>Access Denied</h3>
        <p>You do not have permission to edit this error log.</p>
        <a href="index.php" class="btn btn-primary mt-3">Back to Dashboard</a>
    </div></div>';
    include '../includes/footer.php';
    exit();
}

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

    if (empty($title) || empty($description)) {
        $error = "Title and Description are required.";
    } else {
        $update_sql = "UPDATE errors SET title=?, error_code=?, language=?, description=?, solution=? WHERE id=?";
        $update_stmt = mysqli_prepare($conn, $update_sql);
        mysqli_stmt_bind_param($update_stmt, "sssssi", $title, $error_code, $language, $description, $solution, $id);
        
        if (mysqli_stmt_execute($update_stmt)) {
            $success = "Error log updated successfully!";
            // refresh data for the form
            $row['title'] = $title;
            $row['error_code'] = $error_code;
            $row['language'] = $language;
            $row['description'] = $description;
            $row['solution'] = $solution;
        } else {
            $error = "Failed to update error: " . mysqli_error($conn);
        }
        mysqli_stmt_close($update_stmt);
    }
}
?>
<?php include '../includes/header.php'; ?>

<div class="container">
    <div class="card">
        <div class="card-header">
            <h2>Edit Error Log</h2>
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
                    <input type="text" name="error_code" class="form-control" value="<?= h($row['error_code']) ?>">
                </div>
                <div class="form-group" style="flex:1;">
                    <label class="form-label">Tech</label>
                    <input type="text" name="language" class="form-control" value="<?= h($row['language']) ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Title <span style="color:red">*</span></label>
                <input type="text" name="title" class="form-control" required value="<?= h($row['title']) ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Description <span style="color:red">*</span></label>
                <textarea name="description" class="form-control" rows="4" required><?= h($row['description']) ?></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Solution</label>
                <textarea name="solution" class="form-control" rows="4"><?= h($row['solution']) ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Update Log</button>
            <a href="index.php" class="btn" style="float:right; color:#666;">Cancel</a>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
