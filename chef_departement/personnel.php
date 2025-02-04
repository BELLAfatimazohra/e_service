<?php
session_start();

include '../include/database.php';

try {

    $stmt_professeurs = $pdo->query("SELECT email, prenom, nom FROM professeur");
    $professeurs = $stmt_professeurs->fetchAll(PDO::FETCH_ASSOC);

    // Récupération des chefs de département
    $stmt_chefs = $pdo->query("SELECT email, prenom, nom FROM chef_departement");
    $chefs = $stmt_chefs->fetchAll(PDO::FETCH_ASSOC);

    // Récupération des coordinateurs
    $stmt_coordinateurs = $pdo->query("SELECT email, prenom, nom FROM coordinateur");
    $coordinateurs = $stmt_coordinateurs->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la connexion à la base de données : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Personnels </title>
    <link rel="stylesheet" href="../professeur/assets/personnel.css">
    <link rel="stylesheet" href="include/sidbar_chef_dep.css">
</head>

<body>
    <?php
    include 'include/sidebar_chef_dep.php';
    ?>
    <div class="bodyDiv">

        <h1>Liste des Personnels </h1>
        <table>
            <tr>
                <th>Email</th>
                <th>Prénom</th>
                <th>Nom</th>
                <th>Rôle</th>
            </tr>
            <?php
            // Affichage des professeurs
            foreach ($professeurs as $person) {
                echo "<tr><td>{$person['email']}</td><td>{$person['prenom']}</td><td>{$person['nom']}</td><td>Professeur</td></tr>";
            }
            // Affichage des chefs de département
            foreach ($chefs as $person) {
                echo "<tr><td>{$person['email']}</td><td>{$person['prenom']}</td><td>{$person['nom']}</td><td>Chef de département</td></tr>";
            }
            // Affichage des coordinateurs
            foreach ($coordinateurs as $person) {
                echo "<tr><td>{$person['email']}</td><td>{$person['prenom']}</td><td>{$person['nom']}</td><td>Coordinateur</td></tr>";
            }
            ?>
        </table>
    </div>

    <script>
        document.querySelectorAll("li").forEach(function(li) {
            if (li.classList.contains("active")) {
                li.classList.remove("active");
            }
        });

        document.querySelector(".liPersonnel").classList.add("active");
    </script>

</body>

</html>