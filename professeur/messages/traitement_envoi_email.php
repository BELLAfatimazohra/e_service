<?php
session_start();
if (!isset($_SESSION['user_id']) ||  ($_SESSION['user_type'] !== 'professeur' && $_SESSION['user_type'] !== 'coordinateur_prof') ) {
    header("Location: login.php");
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

    // Récupération des informations du professeur
    $stmt_prof = $pdo->prepare("SELECT Nom, Prenom, Email FROM professeur WHERE id = :id");
    $stmt_prof->execute(['id' => $_SESSION['user_id']]);
    $professeur = $stmt_prof->fetch(PDO::FETCH_ASSOC);

    if (!$professeur) {
        throw new Exception("Informations du professeur introuvables.");
    }

    // Création de l'objet PHPMailer
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // Remplacez par votre serveur SMTP
    $mail->SMTPAuth = true;
    $mail->Username = 'bellafatimazahrae@gmail.com'; // Remplacez par votre adresse email
    $mail->Password = 'bsth dhnq koxy goib'; // Remplacez par votre mot de passe
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Définition de l'expéditeur et de la réponse
    $mail->setFrom($professeur['Email'], $professeur['Nom'] . ' ' . $professeur['Prenom']);
    $mail->addReplyTo($professeur['Email'], $professeur['Nom'] . ' ' . $professeur['Prenom']);

    // Ajout des destinataires (étudiants)
    foreach ($emails as $email) {
        $mail->addAddress($email);
    }

    // Paramètres du message
    $mail->isHTML(true);
    $mail->Subject = $titre;
    $mail->Body    = $message;

    // Envoi du message
    $mail->send();

    // Enregistrement du message dans la base de données
    $id_prof = $_SESSION['user_id'];
    $date_message = date('Y-m-d H:i:s');
    $stmt = $pdo->prepare("INSERT INTO message_prof (id_prof, id_filiere, message, titre, date_message) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$id_prof, $id_filiere, $message, $titre, $date_message]);

    $pdo->commit();

    echo "Message envoyé et enregistré avec succès.";
} catch (Exception $e) {
    $pdo->rollBack();
    echo "Erreur: " . $e->getMessage();
}
