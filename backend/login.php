<?php
require_once "controllers/AuthController.php";
$_SESSION['username'] = $user->username;
$_SESSION['role'] = $result['role'];

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auth = new AuthController();
    $auth->handleLogin($_POST);

}