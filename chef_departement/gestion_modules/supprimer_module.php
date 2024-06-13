<?php
session_start();
include "../../include/database.php";

// Set the content type to JSON
header('Content-Type: application/json');

// Get module ID from POST data
$module_id = isset($_POST['module_id']) ? (int)$_POST['module_id'] : 0;

if ($module_id > 0) {
    try {
        // Start a transaction
        $pdo->beginTransaction();

        // Prepare SQL to delete from exam table
        $sql_exam = "DELETE FROM exam WHERE id_module = ?";
        $stmt_exam = $pdo->prepare($sql_exam);
        $stmt_exam->execute([$module_id]);

        // Prepare SQL to delete the module
        $sql_module = "DELETE FROM module WHERE id = ?";
        $stmt_module = $pdo->prepare($sql_module);
        $stmt_module->execute([$module_id]);

        // Commit the transaction
        $pdo->commit();

        // Return success response
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        // Rollback the transaction on error
        $pdo->rollBack();

        // Return error response
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    // Return error response
    echo json_encode(['success' => false, 'error' => 'Invalid module ID']);
}
?>
