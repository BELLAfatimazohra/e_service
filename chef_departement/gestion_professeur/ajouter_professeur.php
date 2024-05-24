<?php
session_start();
include "../../include/database.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../professeur/PHPMailer/src/Exception.php';
require '../../professeur/PHPMailer/src/PHPMailer.php';
require '../../professeur/PHPMailer/src/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $filiere_id = $_GET['filiere_id'];

    // Hash du mot de passe
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    $id_chef_departement = $_SESSION['user_id'];
    $sql = "SELECT id_depa, email FROM chef_departement WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_chef_departement]);
    $chef_departement = $stmt->fetch(PDO::FETCH_ASSOC);
    $id_departement = $chef_departement['id_depa'];
    $chef_email = $chef_departement['email'];

    $sql = "INSERT INTO professeur (Nom, Prenom, id_filiere, id_departement, email, password) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([$nom, $prenom, $filiere_id, $id_departement, $email, $hashed_password])) {

        $mail = new PHPMailer(true);
        try {

            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = "bellafatimazahrae@gmail.com";
            $mail->Password = 'ymov vcqb dleo hgqa';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;


            $mail->setFrom($chef_email, 'Chef de Département');
            $mail->addAddress($email, "$prenom $nom");


            $mail->isHTML(true);
            $mail->Subject = 'Bienvenue à notre système';
            $mail->Body = "Bonjour $prenom $nom,<br><br>Vous avez été ajouté en tant que professeur dans notre système. Vous pouvez maintenant vous connecter et consulter votre profil pour modifier vos données personnelles.<br><br>Votre identifiant de connexion :<br> email: $email<br> password: $password <br><br> Cordialement,<br>L'équipe.";

            $mail->send();
            echo 'L\'email a été envoyé avec succès.';
        } catch (Exception $e) {
            echo "L'email n'a pas pu être envoyé. Erreur: {$mail->ErrorInfo}";
        }

        header("Location: consulter_professeurs.php?filiere=$filiere_id");
        exit;
    } else {
        echo "Une erreur s'est produite lors de l'ajout du professeur.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   
    <title>Ajouter un Professeur</title>
    <link rel="stylesheet" href="../include/sidbar_chef_dep.css">
    <style>
        .bodyDiv {
            margin-left: 10px;
            color: black;
        }

        .form-container {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin-left: 350px;
           
        }

        .form-container h1 {
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
            color: #333;
        }

        .form-container label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }

        .form-container input[type="text"],
        .form-container input[type="email"],
        .form-container input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .form-container input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .form-container input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <?php
    include "../include/sidebar_chef_dep.php";
    ?>

    <div class="bodyDiv">
        <div class="form-container">
            <h1>Ajouter un Professeur</h1>
            <form method="post">
                <label for="nom">Nom :</label>
                <input type="text" id="nom" name="nom" required>

                <label for="prenom">Prénom :</label>
                <input type="text" id="prenom" name="prenom" required>

                <label for="email">Email :</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" required>

                <input type="submit" value="Ajouter">
            </form>
        </div>
    </div>

    <script>
        document.querySelectorAll("li").forEach(function(li) {
            li.classList.remove("active");
        });

        document.querySelector(".liProfil").classList.add("active");
    </script>
</body>

</html>