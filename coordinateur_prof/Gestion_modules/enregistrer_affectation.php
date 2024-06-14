<?php
session_start();

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'coordinateur_prof') {
    header("Location: index.php");
    exit;
}

require_once '../../include/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'prof_') === 0) {
            // Extract module ID from the input name
            $module_id = str_replace('prof_', '', $key);


            if (!empty($value)) {

                try {
                    $stmt = $pdo->prepare("UPDATE module SET id_prof = :id_prof WHERE id = :module_id");
                    $stmt->execute([
                        'id_prof' => $value,
                        'module_id' => $module_id
                    ]);
                } catch (PDOException $e) {
                    echo "Erreur lors de la mise à jour du module $module_id : " . $e->getMessage();
                }
            }
        }
    }


    header("Location:choisir_filiere.php");
    exit;
}
