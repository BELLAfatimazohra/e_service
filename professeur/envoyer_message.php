<?php
session_start();
include '../include/nav_cote.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'professeur') {
    header("Location: index.php");
    exit;
}

$professeur_id = $_SESSION['user_id'];

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../professeur/assets/envoyer_message.css">
    <title>Envoyer un message aux étudiants</title>
</head>

<body>
    <div class="bodyDiv">
        <h1>Envoyer un message aux étudiants</h1>
        <form class="message" action="traitement_envoi_email.php" method="POST">
            <label for="filiere_annee">Choisir une filière :</label>
            <select name="filiere_annee" id="filiere_annee">
                <?php
                try {
                    require_once '../include/database.php';
                    $stmt_filieres = $pdo->prepare("SELECT f.Nom_filiere, f.annee FROM filiere f INNER JOIN professeur p ON f.id = p.id_filiere WHERE p.id = :professeur_id");
                    $stmt_filieres->execute(['professeur_id' => $professeur_id]);
                    $filieres = $stmt_filieres->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($filieres as $filiere) {
                        $nom_filiere = $filiere['Nom_filiere'];
                        $annee = $filiere['annee'];
                        $filiere_annee = $nom_filiere . ' ' . $annee;
                        echo "<option value=\"$filiere_annee\">$filiere_annee</option>";
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