<?php
session_start();
$_SESSION['user_type'] = 'coordinateur_prof';

if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'coordinateur_prof') {
    $userId = $_SESSION['user_id'];
} else {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../include/sidebarCoor.css">
    <title>Emploi du Temps</title>
    <style>
        .bodyDiv {
            padding: 70px;
            max-width: 800px;
            margin: 40px auto;
            margin-top: 100px;
            background-color: whitesmoke;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .container {
            text-align: center;
        }

        h1 {
            margin-bottom: 20px;
            font-size: 28px;
            color: #333;
        }

        .buttons {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
        }

        .buttons a {
            display: block;
            padding: 10px 20px;
            margin: 10px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .buttons a:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }
    </style>
</head>

<body>
    <?php include '../include/sidebarCoor.php'; ?>
    <div class="bodyDiv">
        <div class="container">
            <h1>Emploi du Temps</h1>
            <div class="buttons">
                <a href="choisir_filiere_consulter_emploi.php">Consulter Emploi du Temps</a>
                <a href="creer_emploi_du_temps.php">Créer un Emploi du Temps</a>
            </div>
        </div>
    </div>

</body>

</html>