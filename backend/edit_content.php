<?php
session_start();
require_once "config/Database.php";

// Only editors and admins
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['editor', 'admin'])) {
    header("Location: dashboard.php");
    exit();
}

$db = (new Database())->connect();
$reviews = $db->query("SELECT id, content FROM reviews ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

// If editing a specific review
$editId = $_GET['id'] ?? null;
$editContent = '';
if ($editId) {
    $stmt = $db->prepare("SELECT content FROM reviews WHERE id = :id");
    $stmt->bindParam(":id", $editId);
    $stmt->execute();
    $editContent = $stmt->fetchColumn();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Content</title>
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#new_content',
            height: 300,
            menubar: false
        });
    </script>
</head>
<body>
    <h2>Editor Panel: Edit Content</h2>

    <?php if ($editId): ?>
        <form action="save_content.php" method="POST">
            <input type="hidden" name="content_id" value="<?php echo $editId; ?>">
            <label for="new_content">Editing Review #<?php echo $editId; ?>:</label><br>
            <textarea id="new_content" name="new_content"><?php echo htmlspecialchars($editContent); ?></textarea><br><br>
            <button type="submit">Save Changes</button>
        </form>
        <hr>
    <?php endif; ?>

    <h3>All Reviews</h3>
    <table border="1" cellpadding="8">
        <tr>
            <th>ID</th>
            <th>Preview</th>
            <th>Action</th>
        </tr>
        <?php foreach ($reviews as $review): ?>
            <tr>
                <td><?php echo $review['id']; ?></td>
                <td><?php echo htmlspecialchars(substr($review['content'], 0, 50)) . '...'; ?></td>
                <td><a href="edit_content.php?id=<?php echo $review['id']; ?>">Edit</a></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <br>
    <a href="../dashboard.php" class="btn btn-primary">← Back to Dashboard</a>



</body>
</html>
<!-- <script src="https://cdn.ckeditor.com/4.25.1-lts/full/ckeditor.js"></script> -->
