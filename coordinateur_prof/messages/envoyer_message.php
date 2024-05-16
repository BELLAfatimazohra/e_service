<?php
session_start();

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'coordinateur_prof') {
    header("Location: index.php");
    exit;
}

$coordinateur_id = $_SESSION['user_id'];

require_once '../../include/database.php';

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../professeur/assets/envoyer_message.css">
    <link rel="stylesheet" href="../include/sidebarCoor.css">
    <title>Envoyer un message aux étudiants</title>
    <style>
        .bodyDiv {
            padding: 20px;
            max-width: 800px;
            margin: 40px auto;
            margin-top: 80px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
        }

        form.message {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        label {
            margin-top: 10px;
            font-weight: bold;
            text-align: left;
            width: 100%;
            max-width: 600px;
        }

        select,
        input.input,
        textarea {
            width: 100%;
            max-width: 600px;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
        }

        textarea {
            resize: vertical;
        }

        button.button {
            margin-top: 20px;
            padding: 15px 30px;
            background-color: #007bff;
            border: none;
            color: white;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        button.button:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        button.button:active {
            background-color: #003f7f;
            transform: scale(1);
        }
    </style>
</head>

<body>
    <?php include '../include/sidebarCoor.php'; ?>
    <div class="bodyDiv">
        <h1>Envoyer un message aux étudiants</h1>
        <form class="message" action="traitement_envoi_email.php" method="POST">
            <label for="filiere_annee">Choisir une filière :</label>
            <select name="filiere_annee" id="filiere_annee">
                <?php
                try {
                    $stmt_filieres = $pdo->prepare("SELECT id, Nom_filiere, annee FROM filiere 
                                                    WHERE id_coordinateur = :id_coord
                                                    ORDER BY Nom_filiere, annee");
                    $stmt_filieres->execute(['id_coord' => $coordinateur_id]);
                    $filieres = $stmt_filieres->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($filieres as $filiere) {
                        echo "<option value=\"{$filiere['Nom_filiere']} {$filiere['annee']}\">{$filiere['Nom_filiere']} _ {$filiere['annee']}</option>";
                    }
                } catch (PDOException $e) {
                    echo "Erreur lors de la récupération des filières : " . $e->getMessage();
                }
                ?>
            </select>
            <br>
            <label for="titre">Titre du message :</label>
            <input class="input" type="text" name="titre" id="titre" required>
            <br>
            <label for="message">Message :</label><br>
            <textarea name="message" id="message" cols="30" rows="10" required></textarea>
            <br>
            <button class="button" type="submit">Envoyer le message</button>
        </form>
    </div>
</body>

</html>