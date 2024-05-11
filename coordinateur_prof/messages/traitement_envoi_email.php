<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: login.php'); 
    exit;
}


include '../../include/database.php';

$filiere_annee = $_POST['filiere_annee'] ?? '';
$titre = $_POST['titre'] ?? '';
$message = $_POST['message'] ?? '';


list($nom_filiere, $annee) = explode(' ', $filiere_annee, 2);


$stmt = $pdo->prepare("SELECT id FROM filiere WHERE Nom_filiere = :nom_filiere");
$stmt->execute(['nom_filiere' => $nom_filiere]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$id_filiere = $row['id'] ?? null;


try {
    $stmt = $pdo->prepare("SELECT Email FROM etudiant WHERE id_filiere IN (SELECT id FROM filiere WHERE Nom_filiere = :Nom_filiere AND annee = :annee)");
    $stmt->execute(['Nom_filiere' => $nom_filiere, 'annee' => $annee]);
    $emails = $stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des adresses email: " . $e->getMessage());
}


if (empty($emails)) {
    echo "Aucun étudiant trouvé pour cette filière.";
    exit;
}


require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
   
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; 
    $mail->SMTPAuth = true;
    $mail->Username = 'bellafatimazahrae@gmail.com'; 
    $mail->Password = 'gbzc ncfx pyhm glir'; 
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

   
    $mail->setFrom('bellafatimazahrae@gmail.com', 'BELLA Fatima Zohra');

   
    $mail->addReplyTo('bellafatimazahrae@gmail.com', 'Bella Fatima Zohra');

    // Destinataires
    foreach ($emails as $email) {
        $mail->addAddress($email);
    }

    // Contenu
    $mail->isHTML(true);
    $mail->Subject = $titre;
    $mail->Body    = $message;

    $mail->send();
    echo 'Message envoyé avec succès.';
} catch (Exception $e) {
    echo "Message non envoyé. Erreur: {$mail->ErrorInfo}";
}


$id_prof = $_SESSION['user_id']; 
$date_message = date('Y-m-d H:i:s'); 


try {
    $stmt = $pdo->prepare("INSERT INTO message_prof (id_prof, id_filiere, message, titre, date_message) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$id_prof, $id_filiere, $message, $titre, $date_message]);
   
    echo "Message envoyé avec succès.";
} catch (PDOException $e) {
   
    echo "Erreur lors de l'envoi du message : " . $e->getMessage();
}
?>
