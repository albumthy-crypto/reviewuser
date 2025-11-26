<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/User.php';


class AuthController {
    public function handleLogin($postData) {
        $database = new Database();
        $db = $database->connect();

        $user = new User($db);
        $user->username = htmlspecialchars($postData['sigon_name']);
        $user->password = $postData['password'];

        $result = $user->login();

        if ($result['status']) {
            session_start();
            $_SESSION['username'] = $user->username;
            $_SESSION['role'] = $result['role'];
            header("Location: dashboard.php");
        } else {
            echo "Invalid credentials.";
        }
    }
}