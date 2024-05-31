<?php
session_start();
if (!isset($_SESSION['user_id']) ||  ($_SESSION['user_type'] !== 'professeur' &&  $_SESSION['user_type'] !== 'chef_departement' && $_SESSION['user_type'] !== 'coordinateur_prof') ) {
    header("Location: login.php");
    exit;
}

require_once "../include/database.php";

// Vérifiez que $userId est défini. Remplacez $userId par la méthode appropriée pour obtenir l'ID de l'utilisateur.
$userId = $_SESSION['user_id'];

try {
    $stmt_filieres = $pdo->prepare("SELECT DISTINCT f.id, f.Nom_filiere, f.annee FROM module m INNER JOIN filiere f ON m.id_filiere = f.id WHERE m.id_prof = :id_prof");
    $stmt_filieres->execute(['id_prof' => $userId]);
    $filieres = $stmt_filieres->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erreur lors de l'exécution de la requête : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../professeur/assets/index.css">
    <link rel="stylesheet" href="assets/include/sidebarProf.css">
    <title>Accueil</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-xgWvbC/GtpG27dbUMf057Ok6ZgoyNnuToSCzjUEuFQlyDhVdRflh5JL4tsbvtRL8yK1z2CqS3hINQjyGv7wXVg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .filiere-list {
            max-width: 800px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .filiere-list h2 {
            font-size: 24px;
            color: #333;
            margin-bottom: 30px;
        }

        .filiere-list ul {
            list-style: none;
            padding: 0;
        }

        .filiere-list li {
            margin-bottom: 10px;
        }

        .filiere-list a {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
            display: block;
            padding: 10px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .filiere-list a:hover {
            background-color: #e9ecef;
        }
    </style>
</head>

<body>
    <?php include './assets/include/sidebarProf.php'; ?>

    <div class="bodyDiv">
        <div class="filiere-list">
            <h2>Liste des filières enseignées</h2>
            <ul>
                <?php foreach ($filieres as $filiere) : ?>
                    <li>
                        <a href="module.php?filiere_id=<?php echo $filiere['id']; ?>">
                            <?php echo $filiere['Nom_filiere'] . " " . $filiere['annee']; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</body>

</html>