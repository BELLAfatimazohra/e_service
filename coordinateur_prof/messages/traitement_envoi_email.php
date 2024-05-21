<?php
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['email']) || !isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'coordinateur_prof' || $_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: login.php');
    exit;
}

require '../../include/database.php';
require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$filiere_annee = $_POST['filiere_annee'] ?? '';
$titre = $_POST['titre'] ?? '';
$message = $_POST['message'] ?? '';
list($nom_filiere, $annee) = explode(' ', $filiere_annee, 2);

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("SELECT id FROM filiere WHERE Nom_filiere = :nom_filiere AND annee = :annee");
    $stmt->execute(['nom_filiere' => $nom_filiere, 'annee' => $annee]);
    $filiere = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$filiere) {
        throw new Exception("Filière introuvable.");
    }

    $id_filiere = $filiere['id'];
    $stmt = $pdo->prepare("SELECT Email FROM etudiant WHERE id_filiere = :id_filiere");
    $stmt->execute(['id_filiere' => $id_filiere]);
    $emails = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if (!$emails) {
        throw new Exception("Aucun étudiant trouvé pour cette filière.");
    }
    // Avant d'utiliser les informations du coordinateur, vous devez les extraire de la base de données en utilisant l'ID du coordinateur connecté

    $stmt_coord = $pdo->prepare("SELECT Nom, Prenom, Email FROM coordinateur WHERE id = :id");
    $stmt_coord->execute(['id' => $_SESSION['user_id']]);
    $coordinateur = $stmt_coord->fetch(PDO::FETCH_ASSOC);

    if (!$coordinateur) {
        throw new Exception("Informations du coordinateur introuvables.");
    }


    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'bellafatimazahrae@gmail.com';
    $mail->Password = 'xpbo xdos badf auwh';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Maintenant, vous pouvez utiliser les informations extraites pour définir les informations de l'expéditeur et de la réponse
    $mail->setFrom($coordinateur['Email'], $coordinateur['Nom'] . ' ' . $coordinateur['Prenom']);
    $mail->addReplyTo($coordinateur['Email'], $coordinateur['Nom'] . ' ' . $coordinateur['Prenom']);

    foreach ($emails as $email) {
        $mail->addAddress($email);
    }

    $mail->isHTML(true);
    $mail->Subject = $titre;
    $mail->Body    = $message;
    $mail->send();

    $id_coord = $_SESSION['user_id'];
    $date_message = date('Y-m-d H:i:s');
    $stmt = $pdo->prepare("INSERT INTO message_coordinateur (id_coordinateur, id_filiere, message, titre, date_message) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$id_coord, $id_filiere, $message, $titre, $date_message]);

    $pdo->commit();

    echo "Message envoyé et enregistré avec succès.";
} catch (Exception $e) {
    $pdo->rollBack();
    echo "Erreur: " . $e->getMessage();
}
