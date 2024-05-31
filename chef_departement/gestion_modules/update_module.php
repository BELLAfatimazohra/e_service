<?php
session_start();
include "../../include/database.php";

// Set the content type to JSON
header('Content-Type: application/json');

// Get form data
$module_id = isset($_POST['module_id']) ? (int)$_POST['module_id'] : 0;
$nom_module = isset($_POST['nom_module']) ? trim($_POST['nom_module']) : '';
$nom_prof = isset($_POST['nom_prof']) ? trim($_POST['nom_prof']) : '';

if ($module_id > 0 && $nom_module && $nom_prof) {
    try {
        // Split the full name into first and last names (assuming space-separated)
        list($nom, $prenom) = explode(' ', $nom_prof, 2);

        // Fetch the professor's ID based on the name
        $sql_prof = "SELECT id FROM professeur WHERE nom = ? AND prenom = ?";
        $stmt_prof = $pdo->prepare($sql_prof);
        $stmt_prof->execute([$nom, $prenom]);
        $prof_id = $stmt_prof->fetchColumn();

        if ($prof_id) {
            // Update the module name and professor ID
            $sql = "UPDATE module SET nom_module = ?, id_prof = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nom_module, $prof_id, $module_id]);

            // Return success response
            echo json_encode(['success' => true]);
        } else {
            // Return error response if professor not found
            echo json_encode(['success' => false, 'error' => 'Professor not found']);
        }
    } catch (Exception $e) {
        // Return error response
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    // Return error response
    echo json_encode(['success' => false, 'error' => 'Invalid input']);
}
?>
