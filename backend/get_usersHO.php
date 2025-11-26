<?php
require_once ('Model.php'); // adjust path if needed

class UserBranches extends Model {
  public function getAllBranchUsers() {
    return $this->fetchAll("SELECT Periodid, id, staff_id, user_id, user_name, group_app, start_date, end_date,
           start_time, end_time, email, co_code
    FROM employee
    WHERE co_code = 'KH0010001'
    ORDER BY id DESC
");
  }
}

// ✅ Correct class name here:
$HOModel = new UserBranches();
$users = $HOModel->getAllBranchUsers();

header('Content-Type: application/json');
echo json_encode(["data" => $users]);