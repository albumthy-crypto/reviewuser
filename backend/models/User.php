<?php
class User {
    private $conn;
    private $table = "rut24login";

    public $username;
    public $password;
    public $role;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function login() {
        $query = "SELECT * FROM " . $this->table . " WHERE username = :username LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $this->username);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($this->password, $row['password'])) {
                return [
                    "status" => true,
                    "role" => $row['role']
                ];
            }
        }
        return ["status" => false];
    }
 


     public function create($username, $plainPassword, $role = 'viewer') {
    $query = "INSERT INTO {$this->table} (username, password, role) VALUES (:username, :password, :role)";
    $stmt = $this->conn->prepare($query);

    $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

    $stmt->bindParam(":username", $username);
    $stmt->bindParam(":password", $hashedPassword);
    $stmt->bindParam(":role", $role);

    return $stmt->execute();
    }
}