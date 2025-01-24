<?php

include_once __DIR__ . './controllers/userControllers.php';

$userController = new UserController();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userController->handleFormSubmit($_POST, $_FILES);
}
$errors = $userController->errors;
$formData = $userController->formData;

include "./views/navbar.php";
include_once "./views/form.php";
?>
