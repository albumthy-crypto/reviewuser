<?php
require_once ('Model.php'); // adjust path if needed

class UserModel extends Model {
  public function getAllUsers() {
    return $this->fetchAll("SELECT id, username, role, created_at FROM rut24login ORDER BY id DESC");
  }
}

// ✅ Create instance and return JSON
$userModel = new UserModel();
$users = $userModel->getAllUsers();

header('Content-Type: application/json');
echo json_encode(["data" => $users]);