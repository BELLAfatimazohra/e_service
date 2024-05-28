<?php
session_start();

// Vérifier si l'utilisateur est connecté en tant que coordinateur
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'coordinateur_prof') {
    // Rediriger vers la page d'index si l'utilisateur n'est pas connecté ou s'il n'est pas un coordinateur
    header("Location: index.php");
    exit;
}

require_once '../../include/database.php';

// Vérifier si le paramètre 'id' est présent dans l'URL
if (!isset($_GET['filiere_id'])) {
    // Rediriger vers une page d'erreur si le paramètre 'id' est manquant
    header("Location: erreur.php");
    exit;
}

// Récupérer l'identifiant de la filière depuis l'URL
$filiere_id = $_GET['filiere_id'];

// Requête SQL pour récupérer le nom de la filière
$sql_filiere = "SELECT Nom_filiere, annee FROM filiere WHERE id = :filiere_id";
$stmt_filiere = $pdo->prepare($sql_filiere);
$stmt_filiere->bindParam(':filiere_id', $filiere_id, PDO::PARAM_INT);
$stmt_filiere->execute();
$filiere = $stmt_filiere->fetch(PDO::FETCH_ASSOC);

// Vérifier si la filière existe
if (!$filiere) {
    // Rediriger vers une page d'erreur si la filière n'existe pas
    header("Location: erreur.php");
    exit;
} else {
    // Nom de la table d'emploi du temps
    $nom_table_emploi_temps = "emploi_temps_" . strtolower(str_replace(' ', '_', $filiere['Nom_filiere'])) . "_" . $filiere['annee'];

    // Vérifier si la table d'emploi du temps existe déjà dans la base de données
    $sql_check_emploi_temps_table = "SHOW TABLES LIKE :nom_table";
    $stmt_check_emploi_temps_table = $pdo->prepare($sql_check_emploi_temps_table);
    $stmt_check_emploi_temps_table->bindParam(':nom_table', $nom_table_emploi_temps, PDO::PARAM_STR);
    $stmt_check_emploi_temps_table->execute();
    $emploi_temps_table_existe = $stmt_check_emploi_temps_table->fetch(PDO::FETCH_ASSOC);

    if ($emploi_temps_table_existe) {
        $message = "Un emploi du temps existe déjà pour cette filière. Vous pouvez la consulter pour le modifier.";
    } else {
        // Requête SQL pour récupérer les modules de la filière
        $sql_modules = "SELECT id, Nom_module FROM module WHERE id_filiere = :filiere_id";
        $stmt_modules = $pdo->prepare($sql_modules);
        $stmt_modules->bindParam(':filiere_id', $filiere_id, PDO::PARAM_INT);
        $stmt_modules->execute();
        $modules = $stmt_modules->fetchAll(PDO::FETCH_ASSOC);

        // Requête SQL pour récupérer les professeurs de la filière
        $sql_profs = "SELECT id, Nom, Prenom FROM professeur WHERE id_filiere = :filiere_id";
        $stmt_profs = $pdo->prepare($sql_profs);
        $stmt_profs->bindParam(':filiere_id', $filiere_id, PDO::PARAM_INT);
        $stmt_profs->execute();
        $profs = $stmt_profs->fetchAll(PDO::FETCH_ASSOC);

        // Options pour les salles
        $salles = ['AmphiA', 'AmphiB', 'Salle 1', 'Salle 2', 'Salle 3', 'Salle 4', 'Salle 5', 'Salle 6', 'Salle 7', 'Salle 8'];

        // Options pour les blocs
        $blocs = ['Ancien bloc', 'Nouveau bloc'];

        // Options pour les types de cours
        $types_cours = ['TD', 'TP', 'Cours'];
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../include/sidebarCoor.css">
    <title>Créer Emploi du Temps</title>

    <style>
        .bodyDiv {
            padding: 20px;
            max-width: 1000px;
            margin: 40px auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);

        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f8f8f8;
            color: #333;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        select {
            width: 100%;
            padding: 5px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }

        .valider {
            padding: 10px 20px;
            background-color: #007bff;
            border: none;
            color: white;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .valider:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        .message-container {

            color: #721c24;

            border-radius: 5px;
            margin-bottom: 20px;
            width: fit-content;

        }

        .message {
            margin-bottom: 10px;
        }

        .btn-return {
            background-color: #007bff;
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            margin-left: 700px;
        }

        .btn-return:hover {
            background-color: #0056b3;
        }

        .btn-return a {
            color: white;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <?php include '../include/sidebarCoor.php'; ?>
    <div class="bodyDiv">
        <div class="bodyDiv"></div>
        <h1>Créer Emploi du Temps pour <?php echo $filiere['Nom_filiere'] . "_" . $filiere['annee']; ?></h1>

        <?php if (isset($message)) : ?>
            <div class="message-container">
                <div class="message">
                    <p><?php echo $message; ?></p>
                    <button class="btn-return"><a href="emploi_du_temps.php">Retour à l'emploi du temps</a></button>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!isset($message)) : ?>
            <form action="valider_emploi_temps.php" method="get">
                <input type="hidden" name="filiere_id" value="<?php echo $filiere_id; ?>">
                <table>
                    <thead>
                        <tr>
                            <th></th>
                            <th>08:00 - 10:00</th>
                            <th>10:00 - 12:00</th>
                            <th>14:00 - 16:00</th>
                            <th>16:00 - 18:00</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Heures de travail
                        $heures = ['08:00 - 10:00', '10:00 - 12:00', '14:00 - 16:00', '16:00 - 18:00'];

                        // Jours de la semaine
                        $jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];

                        foreach ($jours as $jour) {
                            echo "<tr>";
                            echo "<td>$jour</td>";
                            foreach ($heures as $heure) {
                                echo "<td>";
                                // Dropdown des modules
                                echo "<select name='module[]'>";
                                foreach ($modules as $module) {
                                    echo "<option value='{$module['id']}'>{$module['Nom_module']}</option>";
                                }
                                echo "</select>";

                                // Dropdown des professeurs
                                echo "<select name='prof[]'>";
                                foreach ($profs as $prof) {
                                    echo "<option value='{$prof['id']}'>{$prof['Nom']} {$prof['Prenom']}</option>";
                                }
                                echo "</select
                                >";

                                // Dropdown des salles
                                echo "<select name='salle[]'>";
                                foreach ($salles as $salle) {
                                    echo "<option value='$salle'>$salle</option>";
                                }
                                echo "</select>";

                                // Dropdown des blocs
                                echo "<select name='bloc[]'>";
                                foreach ($blocs as $bloc) {
                                    echo "<option value='$bloc'>$bloc</option>";
                                }
                                echo "</select>";

                                // Dropdown des types de cours
                                echo "<select name='type_cours[]'>";
                                foreach ($types_cours as $type_cours) {
                                    echo "<option value='$type_cours'>$type_cours</option>";
                                }
                                echo "</select>";

                                echo "</td>";
                            }
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <button class="valider" type="submit">Valider</button>
            </form>
        <?php endif; ?>

    </div>

    <?php include '../include/sidebarCoor.php'; ?>
</body>

</html>