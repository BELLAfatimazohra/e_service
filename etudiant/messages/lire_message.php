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

    // Vous pouvez également afficher d'autres détails du message, si nécessaire

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
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <h1>Détails du message</h1>
    <div class="message-details">
        <h2><?= $titre ?></h2>
        <p><?= $contenu ?></p>
        <p>Date du message: <?= $date_message ?></p>
    </div>
</body>

</html>