<?php
require_once __DIR__ . '/config/Database.php';

class Model {
  protected $conn;

  public function __construct() {
    $db = new Database();
    $this->conn = $db->connect();
  }

  protected function fetchAll($query) {
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}