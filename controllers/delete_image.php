<?php
include_once __DIR__ . '/../models/User.php';
include_once __DIR__ . '/../controllers/userControllers.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['imagePath'])) {
    $imagePath = $_POST['imagePath'];

    // Make sure the file exists before deleting
    $filePath = "../uploads/" . $imagePath;
    if (file_exists($filePath)) {
        unlink($filePath); // Remove the image from the server

        // You also need to remove the image path from the database
        // Update this code with your method for removing the image from the database
        $userController = new UserController();
        $user = new User();
        $user->deleteImage($imagePath); // Add your database removal logic here

        echo "Image removed successfully";
    } else {
        echo "Image not found";
    }
} else {
    echo "Invalid request";
}
?>
