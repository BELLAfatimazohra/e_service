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
    <title>Choix de l'emploi du temps</title>
    <link rel="stylesheet" href="include/sidebarCoor.css">
    <style>
        a {
            text-decoration: none;
        }
    </style>
</head>
<?php
include "include/sidebarCoor.php";

?>
<div class="bodyDiv">

    <body>
        <h2>Choisissez le type d'emploi du temps à créer :</h2>
        <button><a href="emploi_du_temps_professeur/choisir_prof.php">Emploi du temps des professeurs</a></button>
        <button><a href="emploi_du_temps_etuds/emploi_du_temps.php">Emploi du temps des etudiantes</a></button>
    </body>
</div>

</html>