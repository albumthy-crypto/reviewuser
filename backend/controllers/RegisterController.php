<?php
require_once "../config/Database.php";
require_once "../models/User.php";

class RegisterController {
    public function handleRegistration($postData) {
        $database = new Database();
        $db = $database->connect();

        $user = new User($db);

        $username = htmlspecialchars($postData['sigon_name']);
        $password = $postData['password'];
        $role = isset($postData['role']) ? $postData['role'] : 'viewer';

        if ($user->create($username, $password, $role)) {
            echo "Registration successful!";
        } else {
            echo "Registration failed. Username may already exist.";
        }
    }
}
