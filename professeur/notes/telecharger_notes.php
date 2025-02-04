<?php
session_start();
if (!isset($_SESSION['user_id']) ||  ($_SESSION['user_type'] !== 'professeur' &&  $_SESSION['user_type'] !== 'chef_departement' && $_SESSION['user_type'] !== 'coordinateur_prof') ) {
    header("Location: login.php");
    exit;
}

if (!isset($_POST['exam_id']) || !isset($_POST['module_id']) || !isset($_POST['filiere_id'])) {
    header("Location: erreur.php");
    exit;
}

include '../../include/database.php';

$exam_id = $_POST['exam_id'];
$module_id = $_POST['module_id'];
$filiere_id = $_POST['filiere_id'];

try {

    $stmt_exam_info = $pdo->prepare("SELECT type FROM exam WHERE id = :exam_id");
    $stmt_exam_info->execute(['exam_id' => $exam_id]);
    $exam_info = $stmt_exam_info->fetch(PDO::FETCH_ASSOC);

    $stmt_module_info = $pdo->prepare("SELECT Nom_module FROM module WHERE id = :module_id");
    $stmt_module_info->execute(['module_id' => $module_id]);
    $module_info = $stmt_module_info->fetch(PDO::FETCH_ASSOC);

    $stmt_filiere_info = $pdo->prepare("SELECT Nom_filiere, annee FROM filiere WHERE id = :filiere_id");
    $stmt_filiere_info->execute(['filiere_id' => $filiere_id]);
    $filiere_info = $stmt_filiere_info->fetch(PDO::FETCH_ASSOC);

    // Construire le nom du fichier CSV
    $filename = "notes_" . str_replace(' ', '_', strtolower($exam_info['type'])) . "_" . str_replace(' ', '_', strtolower($module_info['Nom_module'])) . "_" . str_replace(' ', '_', strtolower($filiere_info['Nom_filiere'])) . "_" . $filiere_info['annee'] . ".csv";

    if (file_exists($filename)) {
        // Définir les en-têtes HTTP pour le téléchargement
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');


        $file = fopen($filename, 'r');


        echo "Nom,Prenom,Note,Remarque\n";


        while (($line = fgetcsv($file)) !== FALSE) {
            echo implode(',', $line) . "\n";
        }


        fclose($file);
        exit;
    } else {

        header("Location: erreur.php");
        exit;
    }
} catch (PDOException $e) {
    echo "Erreur lors de l'exécution de la requête : " . $e->getMessage();
}
