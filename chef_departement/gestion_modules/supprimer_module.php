<?php
session_start();
include "../../include/database.php";

// Set the content type to JSON
header('Content-Type: application/json');

// Get module ID from POST data
$module_id = isset($_POST['module_id']) ? (int)$_POST['module_id'] : 0;

if ($module_id > 0) {
    try {
        // Prepare SQL to delete the module
        $sql = "DELETE FROM module WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$module_id]);

        // Return success response
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        // Return error response
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    // Return error response
    echo json_encode(['success' => false, 'error' => 'Invalid module ID']);
}
?>
