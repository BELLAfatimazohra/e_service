<?php
session_start();
if (!isset($_SESSION['user_id']) ||  ($_SESSION['user_type'] !== 'professeur' && $_SESSION['user_type'] !== 'coordinateur_prof') ) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/include/sidebarProf.css">

    <title>profil</title>
    <style>
        .bodyDiv {
            margin-left: 10px;
            color: black;
            max-width: 800px;
            margin-top: 100px;
            margin-left: 250px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .informations-professeur h1 {
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
            color: #333;
        }

        .informations {
            margin-bottom: 30px;
        }

        .informations p {
            margin: 10px 0;
            font-size: 16px;
            color: #555;
        }

        .modules h1,
        .informations_naissances h1,
        .informations_contact h1,
        .autres_informations h1 {
            font-size: 20px;
            margin-top: 30px;
            color: #444;
        }

        .modules table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .modules th,
        .modules td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .modules th {
            background-color: #f2f2f2;
            color: #333;
        }

        .modules ul {
            list-style-type: disc;
            padding-left: 20px;
            margin: 0;
        }
    </style>
</head>

<body>
    <?php
    include 'assets/include/sidebarProf.php';
    ?>
    <div class="bodyDiv">
        <div class="informations-professeur">
            <?php


                $userId = $_SESSION['user_id'];
                require_once '../include/database.php';

                try {
                    $stmt = $pdo->prepare("SELECT * FROM Professeur WHERE id = :id");

                    $stmt->execute(['id' => $userId]);
                    $professeur = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($professeur) {
            ?>

                        <div class="informations">
                            <div class="informations_personnels">
                                <h1>IDENTIFICATION DU PROF</h1>
                                <P>CIN:<?php echo $professeur['CIN']; ?> </P>
                                <p>Nom : <?php echo $professeur['Nom']; ?></p>
                                <p>Prénom : <?php echo $professeur['Prenom']; ?></p>
                                <p>Email : <?php echo $professeur['Email']; ?></p>
                                <P>Sexe : <?php echo $professeur['sexe']; ?></P>
                                <p>Pays : <?php echo $professeur['pays']; ?></p>
                            </div>
                            <div class="modules">
                                <h1>Modules enseignés par filière</h1>
                                <?php
                                try {

                                    $stmt_filieres = $pdo->prepare("SELECT DISTINCT f.id, f.Nom_filiere FROM module m INNER JOIN filiere f ON m.id_filiere = f.id WHERE m.id_prof = :id_prof");
                                    $stmt_filieres->execute(['id_prof' => $userId]);
                                    $filieres = $stmt_filieres->fetchAll(PDO::FETCH_ASSOC);


                                    if ($filieres) {

                                        echo '<table>';
                                        echo '<thead><tr><th>Filière</th><th>Modules</th></tr></thead>';
                                        echo '<tbody>';

                                        foreach ($filieres as $filiere) {

                                            echo "<tr><td>{$filiere['Nom_filiere']}</td><td>";


                                            $stmt_modules = $pdo->prepare("SELECT Nom_module FROM module WHERE id_prof = :id_prof AND id_filiere = :id_filiere");
                                            $stmt_modules->execute(['id_prof' => $userId, 'id_filiere' => $filiere['id']]);
                                            $modules = $stmt_modules->fetchAll(PDO::FETCH_ASSOC);

                                            if ($modules) {

                                                echo '<ul>';
                                                foreach ($modules as $module) {
                                                    echo "<li>{$module['Nom_module']}</li>";
                                                }
                                                echo '</ul>';
                                            } else {

                                                echo "Aucun module trouvé pour cette filière.";
                                            }


                                            echo '</td></tr>';
                                        }

                                        echo '</tbody></table>';
                                    } else {

                                        echo "<p>Aucune filière trouvée pour ce professeur.</p>";
                                    }
                                } catch (PDOException $e) {

                                    echo "Erreur lors de l'exécution de la requête : " . $e->getMessage();
                                }
                                ?>
                            </div>


                            <div class="informations_naissances">
                                <h1>Informations de naissance du professeur</h1>
                                <p>Date de naissance : <?php echo $professeur['date_naissance']; ?></p>
                                <p>Ville de naissance : <?php echo $professeur['ville_naisance']; ?></p>
                            </div>
                            <div class="informations_contact">
                                <h1>Coordonnes</h1>
                                <p>Email Institutionnel : <?php echo $professeur['Email']; ?></p>
                                <p>Email Personnel : <?php echo $professeur['email_personnel']; ?></p>
                                <p>Numéro de téléphone : <?php echo $professeur['telephone']; ?></p>
                            </div>
                            <div class="autres_informations">
                                <h1>Autres informations du professeur</h1>
                                <p>Année d'inscription à l'enseignement supérieur : <?php echo $professeur['ann_insc_ens_sup']; ?></p>
                                <p>Année de travail dans l'enseignement supérieur : <?php echo $professeur['ann_travail_ens_sup']; ?></p>
                                <p>Année de travail à l'université : <?php echo $professeur['ann_travail_uae']; ?></p>
                            </div>
                        </div>


        </div>
<?php

                    } else {
                        echo "Aucun professeur trouvé avec cet identifiant.";
                    }
                } catch (PDOException $e) {

                    echo "Erreur de connexion : " . $e->getMessage();
                }
            
?>
    </div>
    </div>





</body>

</html>