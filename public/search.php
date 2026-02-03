<?php
require_once '../config/db.php';
require_once '../includes/functions.php';

requireLogin();

$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$is_ajax = isset($_GET['ajax']) && $_GET['ajax'] == 1;

if ($q) {
    // search title, code, lang
    $term = "%" . $q . "%";
    $sql = "SELECT id, title, error_code, language, created_at FROM errors WHERE title LIKE ? OR error_code LIKE ? OR language LIKE ? LIMIT 10";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $term, $term, $term);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    $data = [];
    while($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    
    if ($is_ajax) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }
} else {
    $data = [];
}

?>

<?php if (!$is_ajax): ?>
<?php include '../includes/header.php'; ?>

<div class="container">
    <h1>Search Results for "<?= h($q) ?>"</h1>
    <div class="card">
        <?php if (!empty($data)): ?>
            <ul style="list-style: none; padding: 0;">
                <?php foreach($data as $item): ?>
                    <li style="border-bottom: 1px solid #eee; padding: 10px 0;">
                        <span style="font-weight: bold; color: var(--primary-color);">[<?= h($item['error_code']) ?>]</span>
                        <a href="edit.php?id=<?= $item['id'] ?>" style="font-size: 1.1rem; text-decoration: none; color: #333;"><?= h($item['title']) ?></a>
                        <span style="color: #888; font-size: 0.9em; float: right;"><?= h($item['language']) ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No results found.</p>
        <?php endif; ?>
        <a href="index.php" class="btn btn-primary mt-3">Back to Dashboard</a>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
<?php endif; ?>
