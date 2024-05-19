<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once '../../include/database.php';

// Vérifier si l'ID du message est passé en paramètre GET
if (isset($_GET['id']) && isset($_GET['type'])) {
    $message_id = $_GET['id'];
    $message_type = $_GET['type'];

    // Déterminer la table à partir de laquelle récupérer le message
    $table_name = ($message_type === 'prof') ? 'message_prof' : 'message_coordinateur';

    // Récupérer les détails du message à partir de la table appropriée
    $stmt = $pdo->prepare("SELECT * FROM $table_name WHERE id = ?");
    $stmt->execute([$message_id]);
    $message = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérifier si le message existe
    if (!$message) {
        echo "Message introuvable.";
        exit;
    }

    // Afficher le titre et le contenu du message
    $titre = $message['titre'];
    $contenu = $message['message'];
    $date_message = $message['date_message'];
} else {
    echo "ID du message non spécifié.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lire le message</title>
    <link rel="stylesheet" href="../include/sidebarEtud.css">
    <style>
        .message-details {
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-top: 40px;
            max-width: 600px;
            width: 100%;
        }

        .message-details h2 {
            font-size: 1.5em;
            color: #495057;
            margin-bottom: 10px;
            text-decoration: underline;
        }

        .message-details p {
            font-size: 1em;
            color: #212529;
            line-height: 1.6;
            margin-bottom: 10px;
        }

        .message-details p:last-of-type {
            font-size: 0.9em;
            color: #888;
            text-align: left;
        }
    </style>
</head>

<body>
    <?php
    include "../include/sidebarEtud.php";
    ?>
    <div class="bodyDiv">

        <div class="message-details">
            <div class="h2">
                <h2><?= htmlspecialchars($titre) ?></h2>
            </div>
            <p><?= nl2br(htmlspecialchars($contenu)) ?></p>
            <p>Envoye le : <?= htmlspecialchars($date_message) ?></p>
        </div>
    </div>
</body>

</html>