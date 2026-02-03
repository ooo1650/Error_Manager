<?php
require_once '../config/db.php';
require_once '../includes/functions.php';

requireLogin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// get error details with username
$sql = "SELECT e.*, u.username FROM errors e JOIN users u ON e.user_id = u.id WHERE e.id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

if (!$row) {
    die("Error log not found.");
}
?>
<?php include '../includes/header.php'; ?>

<div class="container">
    <div class="card">
        <div class="d-flex justify-between" style="align-items: center; border-bottom: 1px solid #eee; padding-bottom: 1rem; margin-bottom: 1rem;">
            <div>
                <h1 style="margin: 0;"><?= h($row['title']) ?></h1>
                <span style="background: var(--secondary-color); color: white; padding: 2px 8px; border-radius: 4px; font-size: 0.8rem; margin-right: 10px;">
                    <?= h($row['error_code']) ?>
                </span>
                <span style="color: #666; font-size: 0.9rem;">
                    <?= h($row['language']) ?> | By <strong><?= h($row['username']) ?></strong> on <?= date('M d, Y', strtotime($row['created_at'])) ?>
                </span>
            </div>
            
            <?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] == $row['user_id']): ?>
                <!-- show edit/delete if it's theirs -->
                <div class="d-flex gap-2">
                    <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-primary">Edit</a>
                    <form action="delete.php" method="POST" onsubmit="return confirm('Delete this log?')">
                        <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            <?php endif; ?>
        </div>

        <div class="mb-4">
            <h3>Description</h3>
            <p style="white-space: pre-wrap;"><?= h($row['description']) ?></p>
        </div>

        <div class="mb-4">
            <h3>Solution</h3>
            <?php if($row['solution']): ?>
                <div style="background: #f0f9f0; padding: 1rem; border-left: 4px solid var(--success-color); border-radius: 4px;">
                    <p style="white-space: pre-wrap; margin: 0;"><?= h($row['solution']) ?></p>
                </div>
            <?php else: ?>
                <p style="color: #666; font-style: italic;">No solution recorded yet.</p>
            <?php endif; ?>
        </div>

        <div style="margin-top: 2rem; border-top: 1px solid #eee; padding-top: 1rem;">
            <a href="index.php" class="btn" style="background: #eee; color: #333;">&larr; Back to Dashboard</a>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
