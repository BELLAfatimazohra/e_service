<?php
session_start();
// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type'])) {
    header("Location: index.php");
    exit;
}

// Vérifie si l'utilisateur est un professeur
if (!isset($_SESSION['user_type']) || ($_SESSION['user_type'] !== 'professeur' && $_SESSION['user_type'] !== 'chef_departement' &&  $_SESSION['user_type'] !== 'coordinateur_prof')) {
    header("Location: index.php");
    exit;
}
require_once '../../include/database.php';
// Récupère le nom du professeur connecté
$user_id = $_SESSION['user_id'];
// Requête pour récupérer le nom du professeur
$sql_professeur = "SELECT Nom , Prenom FROM professeur WHERE id = :user_id";
$stmt_professeur = $pdo->prepare($sql_professeur);
$stmt_professeur->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt_professeur->execute();
$professeur_info = $stmt_professeur->fetch(PDO::FETCH_ASSOC);
$suite_table = $professeur_info['Nom'];
if (isset($professeur_info['Nom']) && isset($professeur_info['Prenom'])) {
    // Concaténer le nom et le prénom pour former le nom complet du professeur
    $prof_nom = $professeur_info['Nom'] . " " . $professeur_info['Prenom'];

    // Créer la table de l'emploi du temps du professeur si elle n'existe pas
    $sql_create_table = "CREATE TABLE IF NOT EXISTS emploi_$suite_table (
        id INT PRIMARY KEY AUTO_INCREMENT NOT NULL ,
        Nom_prof VARCHAR(255),
        nom_jour VARCHAR(255),
        Nom_salle VARCHAR(255),
        temps VARCHAR(255),
        type_seance VARCHAR(255),
        bloc VARCHAR(255),
        id_filiere INT,
        Nom_module VARCHAR(255)
    )";

    $pdo->exec($sql_create_table);

    // Définir les plages horaires et les jours de la semaine
    $plages_horaires = ['08:00 - 10:00', '10:00 - 12:00', '14:00 - 16:00', '16:00 - 18:00'];
    $jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];

    // Initialiser la matrice de l'emploi du temps du professeur
    $emploi_prof_matrice = [];

    // Récupérer toutes les tables d'emploi du temps
    $sql_get_tables = "SHOW TABLES LIKE 'emploi_temps_%'";
    $stmt_get_tables = $pdo->prepare($sql_get_tables);
    $stmt_get_tables->execute();
    $tables = $stmt_get_tables->fetchAll(PDO::FETCH_COLUMN);

    // Parcourir les tables et récupérer les cours du professeur
    foreach ($tables as $table) {

        // Vérifie si le professeur a un cours dans cette table
        $sql_check_prof = "SELECT * FROM $table WHERE Nom_prof = :prof_nom";

        $stmt_check_prof = $pdo->prepare($sql_check_prof);
        $stmt_check_prof->bindParam(':prof_nom', $prof_nom, PDO::PARAM_STR);
        $stmt_check_prof->execute();

        $cours_prof = $stmt_check_prof->fetchAll(PDO::FETCH_ASSOC);

        // Remplir la table de l'emploi du temps du professeur avec les données de cette table
        foreach ($cours_prof as $cours) {
            $sql_insert_update = "INSERT INTO emploi_$suite_table (
                Nom_prof, nom_jour, Nom_salle, temps, type_seance, bloc, id_filiere, Nom_module
            ) VALUES (
                :Nom_prof, :nom_jour, :Nom_salle, :temps, :type_seance, :bloc, :id_filiere, :Nom_module
            ) ON DUPLICATE KEY UPDATE
                Nom_salle = VALUES(Nom_salle),
                type_seance = VALUES(type_seance),
                bloc = VALUES(bloc),
                id_filiere = VALUES(id_filiere),
                Nom_module = VALUES(Nom_module)";

            $stmt_insert_update = $pdo->prepare($sql_insert_update);
            $params = [
                ':Nom_prof'    => $cours['Nom_prof'],
                ':nom_jour'    => $cours['nom_jour'],
                ':Nom_salle'   => $cours['Nom_salle'],
                ':temps'       => $cours['temps'],
                ':type_seance' => $cours['type_seance'] ?? 'default_value', // Utilisation de coalescence nulle
                ':bloc'        => $cours['bloc'],
                ':id_filiere'  => $cours['id_filiere'],
                ':Nom_module'  => $cours['Nom_module']
            ];
            $stmt_insert_update->execute($params);
        }

        // Remplir la matrice avec les cours du professeur
        foreach ($cours_prof as $cours) {
            $emploi_prof_matrice[$cours['temps']][$cours['nom_jour']][] = $cours; // Utiliser un tableau pour stocker plusieurs cours par plage horaire et jour
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Emploi du temps du professeur</title>
    <link rel="stylesheet" href="../assets/include/sidebarProf.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <?php
    include "../assets/include/sidebarProf.php"
    ?>
    <div class="bodyDiv">
        <h2>Votre emploi du temps </h2>

        <?php if (isset($emploi_prof_matrice)) : ?>
            <table>
                <tr>
                    <th>Heures</th>
                    <?php foreach ($jours as $jour) : ?>
                        <th><?php echo $jour; ?></th>
                    <?php endforeach; ?>
                </tr>
                <?php foreach ($plages_horaires as $plage_horaire) : ?>
                    <tr>
                        <td><?php echo $plage_horaire; ?></td>
                        <?php foreach ($jours as $jour) : ?>
                            <td>
                                <?php if (isset($emploi_prof_matrice[$plage_horaire][$jour])) : ?>
                                    <?php foreach ($emploi_prof_matrice[$plage_horaire][$jour] as $cours) : ?>
                                        <?php echo $cours['Nom_module'] . "<br>" . $cours['Nom_salle'] . "<br>" . $cours['bloc'] . "<br>"; ?>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    ---
                                <?php endif; ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>

            </table>
            <p>NB : Votre emploi emploi du temps sera change et vous serez notifie par tous changements</p>
        <?php else : ?>
            <p>Aucun cours n'est prévu pour le professeur <?php echo $prof_nom; ?> dans l'emploi du temps.</p>
        <?php endif; ?>
    </div>

</body>

</html>