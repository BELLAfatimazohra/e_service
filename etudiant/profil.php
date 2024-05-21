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

// Vérifier si l'étudiant existe
if ($etudiant) {
    // Récupérer les informations de la filière
    $id_filiere = $etudiant['id_filiere'];
    $sql_filiere = "SELECT Nom_filiere, annee FROM filiere WHERE id = ?";
    $stmt_filiere = $pdo->prepare($sql_filiere);
    $stmt_filiere->execute([$id_filiere]);
    $filiere = $stmt_filiere->fetch(PDO::FETCH_ASSOC);

    // Créer le niveau (nom de la filière concaténé avec l'année)
    if ($filiere) {
        $niveau = $filiere['Nom_filiere'] . '_' . $filiere['annee'];
    } else {
        $niveau = "Niveau non trouvé";
    }
} else {
    echo "Étudiant non trouvé.";
    exit;
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Profil de l'Étudiant</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .profile-info {
            margin: 20px 0;
        }

        .profile-info p {
            margin: 10px 0;
            font-size: 16px;
        }

        .label {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Profil de l'Étudiant</h1>
        <div class="profile-info">
            <p><span class="label">Nom:</span> <?php echo htmlspecialchars($etudiant['Nom']); ?></p>
            <p><span class="label">Prénom:</span> <?php echo htmlspecialchars($etudiant['Prenom']); ?></p>
            <p><span class="label">Email:</span> <?php echo htmlspecialchars($etudiant['Email']); ?></p>
            <p><span class="label">CIN:</span> <?php echo htmlspecialchars($etudiant['CIN']); ?></p>
            <p><span class="label">CNE:</span> <?php echo htmlspecialchars($etudiant['CNE']); ?></p>
            <p><span class="label">Sexe:</span> <?php echo htmlspecialchars($etudiant['sexe']); ?></p>
            <p><span class="label">Pays:</span> <?php echo htmlspecialchars($etudiant['pays']); ?></p>
            <p><span class="label">Date de Naissance:</span> <?php echo htmlspecialchars($etudiant['date_naissance']); ?></p>
            <p><span class="label">Ville:</span> <?php echo htmlspecialchars($etudiant['ville']); ?></p>
            <p><span class="label">Téléphone:</span> <?php echo htmlspecialchars($etudiant['telephone']); ?></p>
            <p><span class="label">Email Personnel:</span> <?php echo htmlspecialchars($etudiant['email_personnel']); ?></p>
            <p><span class="label">Niveau:</span> <?php echo htmlspecialchars($niveau); ?></p>
        </div>
        <a href="modifier_profil.php" class="button">Modifier mes informations</a>

    </div>
</body>

</html>