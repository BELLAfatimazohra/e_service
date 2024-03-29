<?php

// Démarrer la session
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../professeur/assets/profil.css">
    <title>profil</title>
</head>

<body>
    <?php
    include "../include/nav_cote.php";
    ?>
    <div class="informations-professeur">
        <?php

        if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'professeur') {

            $userId = $_SESSION['user_id'];


            require_once '../include/database.php';

            try {

                $pdo = new PDO('mysql:host=localhost;dbname=ensah_eservice', 'root', '');
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


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

                                    foreach ($filieres as $filiere) {
                                        echo "<h2>" . $filiere['Nom_filiere'] . "</h2>";
                                        echo "<ul>";


                                        $stmt_modules = $pdo->prepare("SELECT Nom_module FROM module WHERE id_prof = :id_prof AND id_filiere = :id_filiere");
                                        $stmt_modules->execute(['id_prof' => $userId, 'id_filiere' => $filiere['id']]);
                                        $modules = $stmt_modules->fetchAll(PDO::FETCH_ASSOC);


                                        if ($modules) {

                                            foreach ($modules as $module) {
                                                echo "<li>" . $module['Nom_module'] . "</li>";
                                            }
                                        } else {
                                            echo "<li>Aucun module trouvé pour cette filière.</li>";
                                        }
                                        echo "</ul>";
                                    }
                                } else {
                                    echo "<p>Aucune filière trouvée pour ce professeur.</p>";
                                }
                            } catch (PDOException $e) {

                                echo "Erreur lors de l'exécution de la requête : " . $e->getMessage();
                            }
                            ?>
                        </div>
                    </div>
        <?php

                } else {
                    echo "Aucun professeur trouvé avec cet identifiant.";
                }
            } catch (PDOException $e) {

                echo "Erreur de connexion : " . $e->getMessage();
            }
        } else {

            header("Location: professeur/index.php");
            exit;
        }
        ?>
    </div>

</body>

</html>