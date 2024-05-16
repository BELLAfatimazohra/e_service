<?php
include "../include/database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nom_exam']) && isset($_POST['exam_id'])) {
    $nom_exam = $_POST['nom_exam'];
    $id_exam = $_POST['exam_id'];
    try {
        // Start transaction
        $pdo->beginTransaction();
    
        // Insert data
        $sql = "INSERT INTO note (id_etudiant, ide_exam, remarque, note_value)
                SELECT id_etudiant, :id_exam, remarque, note_value
                FROM {$nom_exam}";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_exam', $id_exam, PDO::PARAM_INT);
        $stmt->execute();
    
        // Drop the table
        $sql_drop = "DROP TABLE {$nom_exam}";
        $pdo->exec($sql_drop);
    
        // Delete row from note_provisoire
        $sql_delete = "DELETE FROM notes_provisoire WHERE nom_exam = :nom_exam";
        $stmt_delete = $pdo->prepare($sql_delete);
        $stmt_delete->bindParam(':nom_exam', $nom_exam, PDO::PARAM_STR);
        $stmt_delete->execute();
    
        // Commit transaction
        $pdo->commit();
        echo "Success: All operations were successfully completed.";
    
    } catch (PDOException $e) {
        // Check if there is an active transaction before rolling back
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        echo "Error during transaction: " . $e->getMessage();
    }
    
} else {
    echo "No exam selected or wrong method used.";
}
