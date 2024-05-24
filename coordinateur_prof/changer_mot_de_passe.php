<?php
session_start();
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'coordinateur_prof') {
    header("Location: index.php");
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
        exit;
    }

    $userId = $_SESSION['user_id'];
    try {
        // Vérifier l'ancien mot de passe dans la table `professeur`
        $stmt = $pdo->prepare("SELECT Password FROM professeur WHERE id = :id");
        $stmt->execute(['id' => $userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row && $ancien_mdp === $row['Password']) {
            // Mettre à jour le mot de passe dans la table `professeur`
            $stmt = $pdo->prepare("UPDATE professeur SET Password = :password WHERE id = :id");
            $stmt->execute(['password' => $nouveau_mdp, 'id' => $userId]);

            // Mettre à jour le mot de passe dans la table `coordinateur`
            $stmt = $pdo->prepare("UPDATE coordinateur SET Password = :password WHERE id_prof = :id_prof");
            $stmt->execute(['password' => $nouveau_mdp, 'id_prof' => $userId]);

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
    <link rel="stylesheet" href="../professeur/assets/index.css">
    <link rel="stylesheet" href="./include/sidebarCoor.css">
    <link rel="stylesheet" href="../professeur/assets/changer_mot_de_passe.css">
    <title>Acceuil</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-xgWvbC/GtpG27dbUMf057Ok6ZgoyNnuToSCzjUEuFQlyDhVdRflh5JL4tsbvtRL8yK1z2CqS3hINQjyGv7wXVg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .col1 hr.transparent-line {
            border: none;
            height: 1px;
            background-color: transparent;
        }

        .bienvenue button {
            width: 150px;
            margin-left: 80px;
        }

        .change {
            width: 50%;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .change h2 {
            text-align: center;
            color: #333;
        }

        .change hr {
            margin-top: 20px;
            margin-bottom: 20px;
            border: none;
            border-top: 1px solid #ccc;
        }

        .change h3 {
            color: #666;
        }

        .change p {
            margin-bottom: 20px;
        }

        .change label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .change input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-left: -11px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .change input[type="submit"],
        .change button {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 4px;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .change input[type="submit"]:hover,
        .change button:hover {
            background-color: #0056b3;
        }

        .change a {
            text-decoration: none;
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
            background-color: #ccc;
            color: #333;
        }

        .change button[type="button"]:hover {
            background-color: #999;
        }
    </style>
</head>

<body>
    <?php include './include/sidebarCoor.php'; ?>
    <div class="bodyDiv">
        <div class="change">
            <h2><i class="fas fa-key"></i> Changer le mot de passe</h2>
            <hr>
            <h3>Règles du mot de passe:</h3>
            <p>- Le nombre de caractères du mot de passe doit être entre 10 et 40. <br>
                - Le mot de passe doit contenir au moins un chiffre. <br>
                - Le mot de passe doit contenir au moins un caractère majuscule. <br>
                - Le mot de passe doit contenir au moins un symbole. <br>
                - Le mot de passe doit contenir au moins un caractère majuscule.</p>
            <form action="changer_mot_de_passe.php" method="post">
                <label for="ancien_mdp">Ancien mot de passe :</label>
                <input type="password" id="ancien_mdp" name="ancien_mdp" required><br><br>
                <label for="nouveau_mdp">Nouveau mot de passe :</label>
                <input type="password" id="nouveau_mdp" name="nouveau_mdp" required><br><br>
                <label for="confirmer_mdp">Confirmer le nouveau mot de passe :</label>
                <input type="password" id="confirmer_mdp" name="confirmer_mdp" required><br><br>
                <input type="submit" value="Modifier le mot de passe">
                <a href="../professeur/index.php"><button type="button">Annuler</button></a>
            </form>
        </div>
    </div>
</body>

</html>