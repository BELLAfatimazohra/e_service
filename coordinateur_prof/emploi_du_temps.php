<?php
session_start();
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
    <title>Emploi du Temps</title>
    <style>

.container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
}

h1 {
    font-size: 24px;
    text-align: center;
}

.buttons {
    display: flex;
    justify-content: center;
    margin-top: 20px;
}

.buttons a {
    display: inline-block;
    margin: 0 10px;
    padding: 10px 20px;
    background-color: #007bff;
    color: #fff;
    text-decoration: none;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

.buttons a:hover {
    background-color: #0056b3;
}
</style>
</head>

<body>
    <?php include '../include/nav_cote_corr.php'; ?>

    <div class="container">
        <h1>Emploi du Temps</h1>
        <div class="buttons">
            <a href="consulter_emploi_du_temps.php">Consulter Emploi du Temps</a>
            <a href="creer_emploi_du_temps.php">Créer un Emploi du Temps</a>
        </div>
    </div>
</body>

</html>
