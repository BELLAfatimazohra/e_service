<?php
session_start();
$_SESSION['user_type'] = 'coordinateur_prof';

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
    <link rel="stylesheet" href="../include/sidebarCoor.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .bodyDiv {
            padding: 80px;
            max-width: 1200px;
            margin: 40px auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
            color: #333;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        p {
            text-align: center;
            color: red;
            font-weight: bold;
        }

        .modi {
            display: block;
            width: 100%;
            max-width: 300px;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #007bff;
            border: none;
            color: white;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .modi:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }
    </style>
</head>

<body>
    <?php
    include "../include/sidebarCoor.php";
    ?>
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
    </div>
    <form action="modifier_emploi.php" method="GET">
        <input type="hidden" name="filiere_id" value="<?php echo $filiere_id; ?>">
        <button class="modi" type="submit">Modifier l'emploi du temps</button>
    </form>
    <script>

document.querySelectorAll("li").forEach(function(li) {
    if(li.classList.contains("active")){
        li.classList.remove("active");
    }
});

document.querySelector(".liEmp").classList.add("active");

</script>
</body>

</html>