<?php
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'coordinateur_prof') {
    header("Location: index.php");
    exit;
}
require_once '../../include/database.php';
if (isset($_GET['filiere_id'])) {
    $filiere_id = $_GET['filiere_id'];
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
        $error_message = "La filière sélectionnée n'existe pas.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Consulter l'emploi du temps</title>
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

            <?php
            if (!empty($emploi_du_temps)) {
                $plages_horaires = ['08:00 - 10:00', '10:00 - 12:00', '12:00 - 14:00', '14:00 - 16:00', '16:00 - 18:00'];
                foreach ($plages_horaires as $plage_horaire) :
            ?>
                    <tr>
                        <td><?php echo $plage_horaire; ?></td>
                        <?php
                        foreach ($jours as $jour) :
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
                        endforeach;
                        ?>
                    </tr>
            <?php endforeach;
            } ?>
        </table>
    <?php endif; ?>

</body>

</html>