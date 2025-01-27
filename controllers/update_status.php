<?php
// Assuming you're using PDO for database connection
require_once __DIR__ . '/../db/Database.php'; // Include your DB class if needed

// Check if both 'id' and 'isactive' are set in the POST request
if (isset($_POST['id']) && isset($_POST['isactive'])) {
    $id = $_POST['id'];
    $isActive = $_POST['isactive'];

    try {
        // Database connection
        $db = new Database();
        $stmt = $db->connect()->prepare("UPDATE users SET isactive = :isactive WHERE id = :id");

        // Bind the parameters
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':isactive', $isActive, PDO::PARAM_INT);

        // Execute the statement
        $stmt->execute();

        // Return a success response
        echo json_encode(['status' => 'success']);
    } catch (PDOException $e) {
        // Handle the error and return an error response
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Missing parameters']);
}
?>
