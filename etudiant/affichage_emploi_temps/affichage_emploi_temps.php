<?php
session_start();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type'])) {
    header("Location: index.php");
    exit;
}

// Vérifie si l'utilisateur est un étudiant
if ($_SESSION['user_type'] !== 'etudiant') {
    header("Location: index.php"); // Redirige vers la page d'accueil
    exit;
}

require_once '../../include/database.php';

// Récupère l'ID de l'utilisateur connecté
$user_id = $_SESSION['user_id'];

// Requête pour récupérer les informations de l'étudiant
$sql_etudiant = "SELECT id_filiere FROM etudiant WHERE id = :user_id";
$stmt_etudiant = $pdo->prepare($sql_etudiant);
$stmt_etudiant->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt_etudiant->execute();
$etudiant_info = $stmt_etudiant->fetch(PDO::FETCH_ASSOC);

if ($etudiant_info) {
    $filiere_id = $etudiant_info['id_filiere'];

    // Requête pour récupérer le nom de la filière et l'année correspondante
    $sql_filiere = "SELECT Nom_filiere, annee FROM filiere WHERE id = :filiere_id";
    $stmt_filiere = $pdo->prepare($sql_filiere);
    $stmt_filiere->bindParam(':filiere_id', $filiere_id, PDO::PARAM_INT);
    $stmt_filiere->execute();
    $filiere_info = $stmt_filiere->fetch(PDO::FETCH_ASSOC);

    if ($filiere_info) {
        $nom_table = 'emploi_temps_' . strtolower(str_replace(' ', '_', $filiere_info['Nom_filiere'])) . '_' . $filiere_info['annee'];
        $sql_check_table = "SHOW TABLES LIKE :nom_table";
        $stmt_check_table = $pdo->prepare($sql_check_table);
        $stmt_check_table->bindParam(':nom_table', $nom_table, PDO::PARAM_STR);
        $stmt_check_table->execute();
        $table_exists = $stmt_check_table->rowCount() > 0;

        if ($table_exists) {
            $emploi_du_temps = [];
            $jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
            foreach ($jours as $jour) {
                $sql_select = "SELECT * FROM $nom_table WHERE nom_jour = :jour";
                $stmt_select = $pdo->prepare($sql_select);
                $stmt_select->bindParam(':jour', $jour, PDO::PARAM_STR);
                $stmt_select->execute();
                $emploi_du_temps[$jour] = $stmt_select->fetchAll(PDO::FETCH_ASSOC);
            }
        } else {
            $error_message = "L'emploi du temps pour cette filière et cette année n'a pas encore été créé.";
        }
    } else {
        $error_message = "La filière de l'étudiant n'a pas été trouvée.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Emploi du temps</title>
    <link rel="stylesheet" href="../include/sidebarEtud.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        .bodyDiv {
            margin-left: 150px;
            padding: 20px;
            padding-left: 0px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;

        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #3f37c9;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        .btnn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            outline: none;
            color: #fff;
            background-color: #4CAF50;
            border: none;
            border-radius: 5px;
            box-shadow: 0 9px #999;
        }

        .btnn:hover {
            background-color: #3e8e41;
        }

        .btnn:active {
            background-color: #3e8e41;
            box-shadow: 0 5px #666;
            transform: translateY(4px);
        }

        a {
            color: white;
            text-decoration: none;
        }

        p {
            text-align: center;
            font-size: 16px;
            color: #555;
        }
    </style>
</head>

<body>
    <?php include "../include/sidebarEtud.php"; ?>
    <div class="bodyDiv">
        <h2>Emploi du temps</h2>

        <?php if (isset($error_message)) : ?>
            <p><?php echo $error_message; ?></p>
        <?php else : ?>
            <table>
                <tr>
                    <th>Heures</th>
                    <?php foreach ($jours as $jour) : ?>
                        <th><?php echo $jour; ?></th>
                    <?php endforeach; ?>
                </tr>

                <?php if (!empty($emploi_du_temps)) :
                    $plages_horaires = ['08:00 - 10:00', '10:00 - 12:00', '12:00 - 14:00', '14:00 - 16:00', '16:00 - 18:00'];
                    foreach ($plages_horaires as $plage_horaire) : ?>
                        <tr>
                            <td><?php echo $plage_horaire; ?></td>
                            <?php foreach ($jours as $jour) :
                                $emploi_jour = $emploi_du_temps[$jour];
                                $cours_trouve = false;
                                foreach ($emploi_jour as $cours) {
                                    if ($cours['temps'] === $plage_horaire) {
                                        echo '<td>';
                                        echo $cours['Nom_prof'] . " - ";
                                        echo $cours['Nom_salle'] . " - ";
                                        echo $cours['Nom_module'] . " - ";
                                        echo $cours['bloc'] . " - ";
                                        echo $cours['type_sceance'];
                                        echo '</td>';
                                        $cours_trouve = true;
                                        break;
                                    }
                                }
                                if (!$cours_trouve) {
                                    echo '<td>---</td>';
                                }
                            endforeach; ?>
                        </tr>
                <?php endforeach;
                endif; ?>
            </table>
            <div style="text-align: center;">
                <button class="btnn"><a href="telecharger_emploi.php">Télécharger en PDF</a></button>
            </div>
            <p>NB: Vous serez notifié en cas de changements.</p>
        <?php endif; ?>
    </div>
</body>

</html>