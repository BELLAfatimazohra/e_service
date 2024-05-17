<?php
session_start();
if (!isset($_SESSION['user_id']) ||  ($_SESSION['user_type'] !== 'professeur')) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ancien_mdp']) && isset($_POST['nouveau_mdp']) && isset($_POST['confirmer_mdp'])) {

    $ancien_mdp = $_POST['ancien_mdp'];
    $nouveau_mdp = $_POST['nouveau_mdp'];
    $confirmer_mdp = $_POST['confirmer_mdp'];

    if ($nouveau_mdp !== $confirmer_mdp) {
        echo "Les nouveaux mots de passe ne correspondent pas.";
        exit;
    }
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=ensah_eservice', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "Erreur de connexion : " . $e->getMessage();
    }


    $userId = $_SESSION['user_id'];
    try {

        $stmt = $pdo->prepare("SELECT Password FROM professeur WHERE id = :id");
        $stmt->execute(['id' => $userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);


        if ($row && $ancien_mdp === $row['Password']) {

            $stmt = $pdo->prepare("UPDATE professeur SET Password = :password WHERE id = :id");

            $stmt->execute(['password' => $nouveau_mdp, 'id' => $userId]);

            header("Location: ../professeur/confirmation.php");
            exit;
        } else {

            echo "L'ancien mot de passe est incorrect.";
        }
    } catch (PDOException $e) {

        echo "Erreur lors de la modification du mot de passe : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="assets/include/sidebarProf.css">
    <link rel="stylesheet" href="../professeur/assets/changer_mot_de_passe.css">
    <title>Acceuil</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-xgWvbC/GtpG27dbUMf057Ok6ZgoyNnuToSCzjUEuFQlyDhVdRflh5JL4tsbvtRL8yK1z2CqS3hINQjyGv7wXVg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        

        .change {
            width: 50%;
            /* Largeur de la div */
            margin: auto;
            /* Centrer la div horizontalement */
            padding: 20px;
            /* Espacement intérieur */
            border: 1px solid #ccc;
            /* Bordure */
            border-radius: 5px;
            /* Coins arrondis */
            background-color: #f9f9f9;
            /* Couleur de fond */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            /* Ombre */
        }

        .change h2 {
            text-align: center;
            /* Centrer le titre */
            color: #333;
            /* Couleur du texte */
        }

        .change hr {
            margin-top: 20px;
            /* Espacement avant la ligne horizontale */
            margin-bottom: 20px;
            /* Espacement après la ligne horizontale */
            border: none;
            /* Supprimer la bordure */
            border-top: 1px solid #ccc;
            /* Style de la ligne */
        }

        .change h3 {
            color: #666;
            /* Couleur du sous-titre */
        }

        .change p {
            margin-bottom: 20px;
            /* Espacement après le paragraphe */
        }

        .change label {
            display: block;
            /* Afficher les labels en bloc */
            margin-bottom: 5px;
            /* Espacement après chaque label */
            font-weight: bold;
            /* Gras */
        }

        .change input[type="password"] {
            width: 100%;
            /* Largeur totale */
            padding: 10px;
            margin-left: -11px;
            /* Espacement intérieur */
            margin-bottom: 15px;
            /* Espacement après chaque champ */
            border: 1px solid #ccc;
            /* Bordure */
            border-radius: 4px;
            /* Coins arrondis */
        }

        .change input[type="submit"],
        .change button {
            width: 100%;
            /* Largeur totale */
            padding: 10px;
            /* Espacement intérieur */
            border: none;
            /* Supprimer la bordure */
            border-radius: 4px;
            /* Coins arrondis */
            background-color: #007bff;
            /* Couleur de fond */
            color: #fff;
            /* Couleur du texte */
            cursor: pointer;
            /* Curseur de pointeur */
            transition: background-color 0.3s;
            /* Transition de couleur */
        }

        .change input[type="submit"]:hover,
        .change button:hover {
            background-color: #0056b3;
            /* Changement de couleur au survol */
        }

        .change a {
            text-decoration: none;
            /* Supprimer le soulignement des liens */
        }

        .change h3 {
            background-color: green;
            margin-right: 300px;
            color: #f9f9f9;
            padding-left: 15px;
            border-radius: 5px;
        }

        .change button[type="button"] {
            margin-top: 10px;
            /* Espacement avant le bouton */
            background-color: #ccc;
            /* Couleur de fond du bouton annuler */
            color: #333;
            /* Couleur du texte du bouton annuler */
        }

        .change button[type="button"]:hover {
            background-color: #999;
            /* Changement de couleur au survol du bouton annuler */
        }
    </style>
</head>

<body>
    <?php

    include 'assets/include/sidebarProf.php';
    ?>


    <div class="bodyDiv">
        <div class="change">
            <h2> <i class="fas fa-key"></i> Changer le mot de passe</h2>
            <hr>
            <h3>Règles du mot de passe:</h3>
            <p>- Le nombre de caractères du mot de passe doit être entre 10 et 40. <br>
                - Le mot de passe doit contenir au moins un chiffre. <br>
                - Le mot de passe doit contenir au moins un caractère majuscule. <br>
                - Le mot de passe doit contenir au moins un symbole. <br>
                Le mot de passe doit contenir au moins un caractère majuscule.</p>
            <form action="changer_mot_de_passe.php" method="post">
                <label for="ancien_mdp">Ancien mot de passe :</label>
                <input type="password" id="ancien_mdp" name="ancien_mdp" required><br><br>
                <label for="nouveau_mdp">Nouveau mot de passe :</label>
                <input type="password" id="nouveau_mdp" name="nouveau_mdp" required><br><br>
                <label for="confirmer_mdp">Confirmer le nouveau mot de passe :</label>
                <input type="password" id="confirmer_mdp" name="confirmer_mdp" required><br><br>
                <input type="submit" value="Modifier le mot de passe">
                <a href="../professeur/index.php"><button  type="button">Annuler</button></a>
            </form>
        </div>



</body>

</html>