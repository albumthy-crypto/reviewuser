<?php
session_start();
require_once "config/Database.php";

// Only editors and admins can save content
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['editor', 'admin'])) {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contentId = $_POST['content_id'];
    $newContent = $_POST['new_content'];

    $db = (new Database())->connect();
    $query = "UPDATE reviews SET content = :content WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":content", $newContent);
    $stmt->bindParam(":id", $contentId);

    if ($stmt->execute()) {
        echo "✅ Content updated successfully.<br>";
    } else {
        echo "❌ Failed to update content.<br>";
    }

    echo '<a href="edit_content.php">← Back to Edit Page</a>';
}
?>
