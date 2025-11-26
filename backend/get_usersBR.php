<?php
require_once('Model.php'); // adjust path if needed

class UserBranches extends Model {
  public function getAllBranchUsers() {
    // Debugging query
    $sql = "SELECT Periodid, id, staff_id, user_id, user_name, group_app, start_date, end_date,
            start_time, end_time, email, co_code FROM employee ORDER BY id DESC";
    

    // Attempt to fetch data
    try {
      return $this->fetchAll($sql);
    } catch (Exception $e) {
      // Log error message if something goes wrong
      error_log('Error fetching users: ' . $e->getMessage());
      return [];
    }
  }
}

$branchModel = new UserBranches();
$users = $branchModel->getAllBranchUsers();

// Check if users array is empty
if (empty($users)) {
  error_log('No users found.');
}

// Set the header to JSON
header('Content-Type: application/json');
echo json_encode(["data" => $users]);