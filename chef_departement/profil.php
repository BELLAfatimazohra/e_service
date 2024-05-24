<?php
// Démarrer la session / reprend la session qui est déjà existante
session_start();
require_once '../include/database.php';
// Vérifier si l'utilisateur est connecté et est un chef de département
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'chef_departement') {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="include/sidebarCoor.css">
    <title>Profil</title>
    <link rel="stylesheet" href="include/sidbar_chef_dep.css">
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
 
    include_once 'include/sidebar_chef_dep.php';
    ?>

    <div class="bodyDiv">
        <div class="informations-professeur">
            <?php
            if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'chef_departement') {
                $userId = $_SESSION['user_id'];

                try {
                    // Étape 1 : Récupérer id_prof depuis chef_departement
                    $stmt = $pdo->prepare("SELECT id_prof FROM chef_departement WHERE id = :id");
                    $stmt->execute(['id' => $userId]);
                    $chef_departement = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($chef_departement) {
                        $id_prof = $chef_departement['id_prof'];

                        // Étape 2 : Utiliser id_prof pour récupérer les informations du professeur
                        $stmt_prof = $pdo->prepare("SELECT * FROM Professeur WHERE id = :id_prof");
                        $stmt_prof->execute(['id_prof' => $id_prof]);
                        $professeur = $stmt_prof->fetch(PDO::FETCH_ASSOC);

                        if ($professeur) {
            ?>
                            <div class="informations">
                                <div class="informations_personnels">
                                    <h1>IDENTIFICATION DU PROF</h1>
                                    <p>CIN: <?php echo htmlspecialchars($professeur['CIN']); ?></p>
                                    <p>Nom: <?php echo htmlspecialchars($professeur['Nom']); ?></p>
                                    <p>Prénom: <?php echo htmlspecialchars($professeur['Prenom']); ?></p>
                                    <p>Email: <?php echo htmlspecialchars($professeur['Email']); ?></p>
                                    <p>Sexe: <?php echo htmlspecialchars($professeur['sexe']); ?></p>
                                    <p>Pays: <?php echo htmlspecialchars($professeur['pays']); ?></p>
                                </div>

                                <div class="modules">
                                    <h1>Modules enseignés par filière</h1>
                                    <?php
                                    try {
                                        // Requête pour obtenir les filières
                                        $stmt_filieres = $pdo->prepare("
                                        SELECT DISTINCT f.id, f.Nom_filiere 
                                        FROM module m 
                                        INNER JOIN filiere f ON m.id_filiere = f.id 
                                        WHERE m.id_prof = :id_prof
                                    ");
                                        $stmt_filieres->execute(['id_prof' => $id_prof]);
                                        $filieres = $stmt_filieres->fetchAll(PDO::FETCH_ASSOC);

                                        if ($filieres) {
                                            echo '<table>';
                                            echo '<thead><tr><th>Filière</th><th>Modules</th></tr></thead>';
                                            echo '<tbody>';

                                            foreach ($filieres as $filiere) {
                                                echo "<tr><td>" . htmlspecialchars($filiere['Nom_filiere']) . "</td><td>";

                                                // Requête pour obtenir les modules de chaque filière
                                                $stmt_modules = $pdo->prepare("
                                                SELECT Nom_module 
                                                FROM module 
                                                WHERE id_prof = :id_prof AND id_filiere = :id_filiere
                                            ");
                                                $stmt_modules->execute([
                                                    'id_prof' => $id_prof,
                                                    'id_filiere' => $filiere['id']
                                                ]);
                                                $modules = $stmt_modules->fetchAll(PDO::FETCH_ASSOC);

                                                if ($modules) {
                                                    echo '<ul>';
                                                    foreach ($modules as $module) {
                                                        echo "<li>" . htmlspecialchars($module['Nom_module']) . "</li>";
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
                                        echo "Erreur lors de l'exécution de la requête : " . htmlspecialchars($e->getMessage());
                                    }
                                    ?>
                                </div>

                                <div class="informations_naissances">
                                    <h1>Informations de naissance du professeur</h1>
                                    <p>Date de naissance: <?php echo htmlspecialchars($professeur['date_naissance']); ?></p>
                                    <p>Ville de naissance: <?php echo htmlspecialchars($professeur['ville_naisance']); ?></p>
                                </div>

                                <div class="informations_contact">
                                    <h1>Coordonnées</h1>
                                    <p>Email Institutionnel: <?php echo htmlspecialchars($professeur['Email']); ?></p>
                                    <p>Email Personnel: <?php echo htmlspecialchars($professeur['email_personnel']); ?></p>
                                    <p>Numéro de téléphone: <?php echo htmlspecialchars($professeur['telephone']); ?></p>
                                </div>

                                <div class="autres_informations">
                                    <h1>Autres informations du professeur</h1>
                                    <p>Année d'inscription à l'enseignement supérieur: <?php echo htmlspecialchars($professeur['ann_insc_ens_sup']); ?></p>
                                    <p>Année de travail dans l'enseignement supérieur: <?php echo htmlspecialchars($professeur['ann_travail_ens_sup']); ?></p>
                                    <p>Année de travail à l'université: <?php echo htmlspecialchars($professeur['ann_travail_uae']); ?></p>
                                </div>
                            </div>
            <?php
                        } else {
                            echo "Aucun professeur trouvé avec cet identifiant.";
                        }
                    } else {
                        echo "Aucun chef de département trouvé avec cet identifiant.";
                    }
                } catch (PDOException $e) {
                    echo "Erreur de connexion : " . htmlspecialchars($e->getMessage());
                }
            }
            ?>
        </div>
    </div>

    <script>
        document.querySelectorAll("li").forEach(function(li) {
            li.classList.remove("active");
        });

        document.querySelector(".liProfil").classList.add("active");
    </script>
</body>

</html>