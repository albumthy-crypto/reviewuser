<?php
require_once "controllers/RegisterController.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $register = new RegisterController();
    $register->handleRegistration($_POST);
}
