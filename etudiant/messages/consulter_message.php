<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once '../../include/database.php';

// Récupérer les messages de l'étudiant connecté
$stmt_messages_prof = $pdo->prepare("SELECT mp.id, mp.titre, mp.date_message, p.Nom AS nom_prof, p.Prenom AS prenom_prof
                                    FROM message_prof mp
                                    JOIN professeur p ON mp.id_prof = p.id
                                    WHERE mp.id_filiere IN (SELECT id_filiere FROM etudiant WHERE id = ?)");
$stmt_messages_prof->execute([$_SESSION['user_id']]);
$messages_prof = $stmt_messages_prof->fetchAll(PDO::FETCH_ASSOC);

$stmt_messages_coord = $pdo->prepare("SELECT mc.id, mc.titre, mc.date_message, c.Nom AS nom_coord, c.Prenom AS prenom_coord
                                    FROM message_coordinateur mc
                                    JOIN coordinateur c ON mc.id_coordinateur = c.id
                                    WHERE mc.id_filiere IN (SELECT id_filiere FROM etudiant WHERE id = ?)");
$stmt_messages_coord->execute([$_SESSION['user_id']]);
$messages_coord = $stmt_messages_coord->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages reçus</title>
    <style>
        h1 {
            color: #4a4a48;
            text-decoration: underline;
            font-family: 'Arial', sans-serif;

            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);

            letter-spacing: 2px;

            word-spacing: 5px;

            border: 2px solid #4a4a48;

            padding: 10px;

            background-color: #0466c8;

            border-radius: 10px;

            display: inline-block;

        }


        .message-buttons {
            background-color: #edf2fb;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .button {
            background-color: #f8f8ff;
            width: 400px;
            color: black;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            margin-bottom: 2px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .button:hover {
            background-color: #0056b3;
            color: white;
        }

        .date {
            color: #5e747f;
        }
    </style>
</head>

<body>
    <h1>Boîte de messages</h1>
    <div class="message-buttons">
        <?php foreach ($messages_prof as $message) : ?>
            <a href="lire_message.php?type=prof&id=<?= $message['id'] ?>" target="_blank">
                <button class="button">
                    <span class="date"> <?= $message['date_message'] ?></span> <br>
                    [<?= $message['nom_prof'] ?> <?= $message['prenom_prof'] ?>]-
                    <?= $message['titre'] ?>
                </button>
            </a>
        <?php endforeach; ?>


        <?php foreach ($messages_coord as $message) : ?>
            <a href="lire_message.php?type=coordinateur&id=<?= $message['id'] ?>" target="_blank">
                <button class="button">
                    <span class="date"> <?= $message['date_message'] ?> </span> <br>
                    [ <?= $message['nom_coord'] ?> <?= $message['prenom_coord'] ?> ]-
                    <?= $message['titre'] ?>
                </button>

            </a>
        <?php endforeach; ?>
    </div>
</body>

</html>