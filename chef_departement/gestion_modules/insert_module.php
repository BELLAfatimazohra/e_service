<?php
session_start();
include "../../include/database.php";

// Set the content type to JSON
header('Content-Type: application/json');

// Get form data
$id_filiere = isset($_POST['id_filiere']) ? (int)$_POST['id_filiere'] : 0;
$nom_module = isset($_POST['nom_module']) ? trim($_POST['nom_module']) : '';
$id_prof = isset($_POST['id_prof']) ? (int)$_POST['id_prof'] : null; // Assuming nullable as before, change if different

// Check for valid data
if ($id_filiere > 0 && $nom_module && $id_prof !== null) {
    try {
        // Check if the module name already exists
        $check_sql = "SELECT COUNT(*) FROM module WHERE Nom_module = ? AND id_filiere = ?";
        $check_stmt = $pdo->prepare($check_sql);
        $check_stmt->execute([$nom_module, $id_filiere]);
        $module_count = $check_stmt->fetchColumn();

        if ($module_count > 0) {
            // Module name already exists, return error
            echo json_encode(['success' => false, 'error' => 'Module name already exists in the database.']);
        } else {
            // Module name doesn't exist, proceed with insertion
            $insert_sql = "INSERT INTO module (id_prof, id_filiere, Nom_module) VALUES (?, ?, ?)";
            $insert_stmt = $pdo->prepare($insert_sql);
            $insert_stmt->execute([$id_prof, $id_filiere, $nom_module]);

            // Return success response
            echo json_encode(['success' => true]);
        }
    } catch (Exception $e) {
        // Handle potential exceptions such as database connection errors
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    // Inputs are not valid, return error
    echo json_encode(['success' => false, 'error' => 'Invalid input. Please ensure all fields are correctly filled.']);
}
?>
