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
<html>

<head>
    <title>Ajouter un Professeur</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .bodyDiv {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
        }

        label {
            display: block;
            text-align: left;
            margin: 10px 0 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #5cb85c;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #4cae4c;
        }
    </style>
</head>

<body>
    <div class="bodyDiv">
        <h1>Ajouter un Professeur</h1>
        <form method="post">
            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="nom" required><br>

            <label for="prenom">Prénom :</label>
            <input type="text" id="prenom" name="prenom" required><br>

            <label for="email">Email :</label>
            <input type="email" id="email" name="email" required><br>

            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required><br>

            <input type="submit" value="Ajouter">
        </form>
    </div>
</body>

</html>