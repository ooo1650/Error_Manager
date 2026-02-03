<?php
require_once '../config/db.php';
require_once '../includes/functions.php';

requireLogin(); // check if logged in

// grab all the errors
$sql = "SELECT e.*, u.username FROM errors e JOIN users u ON e.user_id = u.id ORDER BY e.created_at DESC";
$result = mysqli_query($conn, $sql);
?>
<?php include '../includes/header.php'; ?>

<div class="container">
    <div class="d-flex justify-between" style="align-items: center; margin-bottom: 2rem;">
        <h1>Recent Errors</h1>
        
        <div style="position: relative;">
            <input type="text" id="searchInput" placeholder="Search errors..." class="form-control" style="width: 300px;">
            <div id="searchResults" class="search-results-dropdown" style="display:none; position:absolute; top:100%; left:0; right:0; background:white; border:1px solid #ddd; z-index:1000;"></div>
        </div>
        
        <a href="add.php" class="btn btn-primary">+ New Log</a>
    </div>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <div class="card" style="padding: 0;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Title</th>
                        <th>Tech</th>
                        <th>By</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><span style="background:#eee; padding:2px 6px; border-radius:4px; font-family:monospace;"><?= h($row['error_code']) ?></span></td>
                            <td><?= h($row['title']) ?></td>
                            <td><?= h($row['language']) ?></td>
                            <td><?= h($row['username']) ?></td>
                            <td><?= date('M d, Y', strtotime($row['created_at'])) ?></td>
                            <td>
                                <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                                <form action="delete.php" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure?')">
                                    <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="card text-center">
            <h3>No errors logged yet.</h3>
            <p>Great job! Or maybe you just haven't logged them yet.</p>
            <a href="add.php" class="btn btn-primary mt-3">Log First Error</a>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
