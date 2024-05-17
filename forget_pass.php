<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'professeur/PHPMailer/src/Exception.php';
require 'professeur/PHPMailer/src/PHPMailer.php';
require 'professeur/PHPMailer/src/SMTP.php';
require_once 'include/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reset_password'])) {

    $email = $_POST['email'];


    $newPassword = generateRandomPassword();


    $stmt = $pdo->prepare("SELECT * FROM etudiant  WHERE Email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {

        $stmt = $pdo->prepare("UPDATE etudiant SET password = ? WHERE email = ?");
        $stmt->execute([$newPassword, $email]);

        sendNewPasswordByEmail($email, $newPassword);

        echo "Un nouveau mot de passe a été envoyé à votre adresse e-mail .";
    } else {
        echo "L'adresse e-mail n'existe pas dans notre base de données.";
    }
}

function generateRandomPassword($length = 8)
{
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $password = "";
    for ($i = 0; $i < $length; $i++) {
        $password .= $chars[rand(0, strlen($chars) - 1)];
    }
    return $password;
}

function sendNewPasswordByEmail($email, $password)
{

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'bellafatimazahrae@gmail.com';
        $mail->Password   = 'udrt vdly tvcs auim';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;


        $mail->setFrom('bellafatimazahrae@gmail.com', 'BELLA Fatima Zohra');
        $mail->addAddress($email);


        $mail->isHTML(true);
        $mail->Subject = 'Nouveau mot de passe';
        $mail->Body    = 'Votre nouveau mot de passe est : ' . $password;


        $mail->send();
    } catch (Exception $e) {
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="professeur/assets/index.css">
    <title>Réinitialisation de mot de passe</title>
</head>

<body>


    <main>
        <div style="max-width: 400px; margin: 0 auto;">
            <h2 style="text-align: center; margin-bottom: 20px;">Réinitialisation de mot de passe</h2>
            <form action="" method="post">
                <div style="margin-bottom: 20px;">
                    <label for="email" style="display: block; margin-bottom: 5px;">Adresse e-mail :</label>
                    <input type="email" id="email" name="email" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
                </div>
                <div style="text-align: center;">
                    <input type="submit" name="reset_password" value="Réinitialiser le mot de passe" style="background-color: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">
                </div>
            </form>
        </div>
    </main>

</body>

</html>