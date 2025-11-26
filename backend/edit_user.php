<?php
session_start();
require_once __DIR__ . '/../config/Database.php';


if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../dashboard.php");
    exit();
}

$db = (new Database())->connect();
$id = $_GET['id'] ?? null;

if (!$id) {
    echo "❌ Invalid user ID.";
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newRole = $_POST['role'];
    $stmt = $db->prepare("UPDATE rut24login SET role = :role WHERE id = :id");
    $stmt->bindParam(":role", $newRole);
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    header("Location: manage_users.php");
    exit();
}

// Load user info
$stmt = $db->prepare("SELECT username, role FROM rut24login WHERE id = :id");
$stmt->bindParam(":id", $id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "❌ User not found.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User Role</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Edit Role for <strong><?php echo htmlspecialchars($user['username']); ?></strong></h2>

    <form method="POST">
        <div class="mb-3">
            <label for="role" class="form-label">Role:</label>
            <select name="role" class="form-select" required>
                <option value="admin" <?php if ($user['role'] === 'admin') echo 'selected'; ?>>Admin</option>
                <option value="editor" <?php if ($user['role'] === 'editor') echo 'selected'; ?>>Editor</option>
                <option value="viewer" <?php if ($user['role'] === 'viewer') echo 'selected'; ?>>Viewer</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Save Changes</button>
        <a href="manage_users.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
