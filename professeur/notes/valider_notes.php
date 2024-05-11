<?php
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type'])) {
    header("Location: index.php");
    exit;
}
require_once '../../include/database.php';
require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!isset($_POST['exam_id']) || !isset($_POST['module_id']) || !isset($_POST['filiere_id'])) {
    header("Location: erreur.php");
    exit;
}

$exam_id = $_POST['exam_id'];
$module_id = $_POST['module_id'];
$filiere_id = $_POST['filiere_id'];
$user_id = $_SESSION['user_id'];

try {
    // Récupérer les informations sur l'examen
    $stmt_exam_info = $pdo->prepare("SELECT type, id_module FROM exam WHERE id = :exam_id");
    $stmt_exam_info->execute(['exam_id' => $exam_id]);
    $exam_info = $stmt_exam_info->fetch(PDO::FETCH_ASSOC);

    // Récupérer le nom du module
    $stmt_module_info = $pdo->prepare("SELECT Nom_module FROM module WHERE id = :module_id");
    $stmt_module_info->execute(['module_id' => $module_id]);
    $module_info = $stmt_module_info->fetch(PDO::FETCH_ASSOC);

    // Récupérer le nom de la filière et l'e-mail du coordinateur
    $stmt_filiere_info = $pdo->prepare("SELECT Nom_filiere, id_coordinateur FROM filiere WHERE id = :filiere_id");
    $stmt_filiere_info->execute(['filiere_id' => $filiere_id]);
    $filiere_info = $stmt_filiere_info->fetch(PDO::FETCH_ASSOC);

    // Récupérer l'e-mail du coordinateur
    $coordinateur_id = $filiere_info['id_coordinateur'];
    $stmt_coordinateur_info = $pdo->prepare("SELECT Email , Nom ,Prenom FROM coordinateur WHERE id = :id_coordinateur");
    $stmt_coordinateur_info->execute(['id_coordinateur' => $coordinateur_id]);
    $coordinateur_info = $stmt_coordinateur_info->fetch(PDO::FETCH_ASSOC);
    $coordinateur_email = $coordinateur_info['Email'];
    $coordinateur_nom = $coordinateur_info['Nom'];
    $coordinateur_prenom = $coordinateur_info['Prenom'];
    $coordinateur_nom_complet = $coordinateur_info['Nom'] . ' ' . $coordinateur_info['Prenom'];

    // Récupérer l'email du professeur connecté
    $stmt_email = $pdo->prepare("SELECT Email , Nom,Prenom FROM professeur WHERE id = :user_id");
    $stmt_email->execute(['user_id' => $user_id]);
    $user_info = $stmt_email->fetch(PDO::FETCH_ASSOC);
    $prof_email = $user_info['Email'];
    $prof_nom = $user_info['Nom'];
    $prof_prenom = $user_info['Prenom'];
    $prof_nom_complet = $user_info['Nom'] . ' ' . $user_info['Prenom'];

    // Préparer le contenu de l'e-mail
    $subject = "Validation des notes pour l'examen {$exam_info['type']}";
    $message = "Les notes pour l'examen {$exam_info['type']} du module {$module_info['Nom_module']} ont été validées pour la filière {$filiere_info['Nom_filiere']}.";

    // Envoi de l'e-mail
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = $prof_email; 
    $mail->Password = 'frra jjmc fxjg rxdp'; 

    $mail->Port = 587;
    $mail->setFrom($prof_email, $prof_nom_complet); 
    $mail->addAddress($coordinateur_email, $coordinateur_nom_complet);
    $mail->addReplyTo($prof_email, $prof_nom_complet);
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body = $message;

    $mail->send();
    echo 'L\'e-mail a été envoyé avec succès au coordinateur de la filière.';
} catch (PDOException $e) {
    echo "Erreur lors de l'exécution de la requête : " . $e->getMessage();
} catch (Exception $e) {
    echo "Erreur lors de l'envoi de l'e-mail : {$mail->ErrorInfo}";
}
