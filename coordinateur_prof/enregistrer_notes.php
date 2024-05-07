<?php
session_start();

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'coordinateur_prof') {
    header("Location: index.php");
    exit;
}

if (!isset($_GET['exam_id'])) {
    header("Location: erreur.php");
    exit;
}
include '../include/database.php';
$exam_id = $_GET['exam_id'];

try {
    $stmt_exam_info = $pdo->prepare("SELECT type, pourcentage FROM exam WHERE id = :exam_id");
    $stmt_exam_info->execute(['exam_id' => $exam_id]);
    $exam_info = $stmt_exam_info->fetch(PDO::FETCH_ASSOC);
    $stmt_students = $pdo->prepare("SELECT id, nom, prenom FROM etudiant WHERE id_filiere = (SELECT id_filiere FROM module WHERE id = (SELECT id_module FROM exam WHERE id = :exam_id))");
    $stmt_students->execute(['exam_id' => $exam_id]);
    $students = $stmt_students->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erreur lors de l'exécution de la requête : " . $e->getMessage();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $notes = $_POST['notes'];
    $remarques = $_POST['remarques'];
    $file = fopen("notes_exam_$exam_id.csv", "w");
    fputcsv($file, ['Nom', 'Prénom', 'Note', 'Remarque']);
    foreach ($notes as $student_id => $note) {
        $student_info = $pdo->prepare("SELECT nom, prenom FROM etudiant WHERE id = :student_id");
        $student_info->execute(['student_id' => $student_id]);
        $student_data = $student_info->fetch(PDO::FETCH_ASSOC);
        $nom = $student_data['nom'];
        $prenom = $student_data['prenom'];
        $remarque = isset($remarques[$student_id]) ? $remarques[$student_id] : '';
        fputcsv($file, [$nom, $prenom, $note, $remarque]);
    }
    fclose($file);
}
?>
