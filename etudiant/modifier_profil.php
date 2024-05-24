<?php
session_start(); // Assurez-vous que la session est démarrée
include "../include/database.php";

// ID de l'étudiant connecté (par exemple, obtenu après la connexion)
$id_etudiant = $_SESSION['user_id'];

// Récupérer les informations de l'étudiant
$sql_etudiant = "SELECT Nom, Prenom, Email, CIN, CNE, sexe, pays, date_naissance, ville, telephone, email_personnel, id_filiere FROM etudiant WHERE id = ?";
$stmt_etudiant = $pdo->prepare($sql_etudiant);
$stmt_etudiant->execute([$id_etudiant]);
$etudiant = $stmt_etudiant->fetch(PDO::FETCH_ASSOC);


// Vérifiez si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $cin = $_POST['CIN']; // Ajout du champ CIN
    $cne = $_POST['CNE']; // Ajout du champ CNE
    $ville = $_POST['ville']; // Ajout du champ ville
    $telephone = $_POST['telephone']; // Ajout du champ téléphone
    $email_personnel = $_POST['email_personnel']; // Ajout du champ email_personnel

    // ID de l'étudiant connecté
    $id_etudiant = $_SESSION['user_id'];

    // Mettre à jour les informations de l'étudiant dans la base de données
    $sql_update = "UPDATE etudiant SET Nom = ?, Prenom = ?, Email = ?, CIN = ?, CNE = ?, ville = ?, telephone = ?, email_personnel = ? WHERE id = ?";
    $stmt_update = $pdo->prepare($sql_update);
    $stmt_update->execute([$nom, $prenom, $email, $cin, $cne, $ville, $telephone, $email_personnel, $id_etudiant]);

    // Rediriger l'utilisateur vers la page de profil après la mise à jour
    header("Location: profil.php");
    exit();
}

?>


<!DOCTYPE html>
<html>

<head>
    <title>Modifier le profil</title>
    <link rel="stylesheet" type="text/css" href="include/sidebarEtud.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
        }

        .form {
            margin: auto;
            max-width: 600px;
            margin-left: 200px;
            padding: 20px;
            background-color: #fff;
            width: 500px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
        }

        input[type="text"],
        input[type="email"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            /* Pour inclure le padding dans la largeur */
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <?php
    include "include/sidebarEtud.php";
    ?>
    <div class="bodyDiv">
        <div class="form">
            <h2>Modifier mes informations</h2>
            <form action="" method="post">
                <label for="nom">Nom :</label>
                <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($etudiant['Nom']); ?>" required><br>

                <label for="prenom">Prénom :</label>
                <input type="text" id="prenom" name="prenom" value="<?php echo htmlspecialchars($etudiant['Prenom']); ?>" required><br>

                <label for="email">Email :</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($etudiant['Email']); ?>" required><br>

                <label for="cin">CIN :</label>
                <input type="text" id="cin" name="CIN" value="<?php echo htmlspecialchars($etudiant['CIN']); ?>" required><br>

                <label for="cne">CNE :</label>
                <input type="text" id="cne" name="CNE" value="<?php echo htmlspecialchars($etudiant['CNE']); ?>" required><br>

                <label for="ville">Ville :</label>
                <input type="text" id="ville" name="ville" value="<?php echo htmlspecialchars($etudiant['ville']); ?>" required><br>

                <label for="telephone">Num de telephone :</label>
                <input type="text" id="telephone" name="telephone" value="<?php echo htmlspecialchars($etudiant['telephone']); ?>" required><br>

                <label for="email">Email personnel :</label>
                <input type="email" id="email" name="email_personnel" value="<?php echo htmlspecialchars($etudiant['email_personnel']); ?>" required><br>

                <input type="submit" value="Enregistrer les modifications">
            </form>
        </div>
    </div>
</body>

</html>