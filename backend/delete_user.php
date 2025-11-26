<?php
session_start();
require_once "../config/Database.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../dashboard.php");
    exit();
}

$id = $_GET['id'] ?? null;
$db = (new Database())->connect();

$stmt = $db->prepare("DELETE FROM rut24 WHERE id = :id");
$stmt->bindParam(":id", $id);
$stmt->execute();

header("Location: manage_users.php");
exit();
