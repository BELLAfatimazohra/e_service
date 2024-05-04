<?php

// Démarrer la session / reprend la session qui est deja existe 
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>profil</title>
    <style>
       .informations-professeur{
        margin-left: 10px;
       } 
    </style>
</head>

<body>
    <?php
    include '../include/nav_cote_corr.php';
    ?>
    <script>
        var bodyDiv = document.querySelector('.bodyDiv');


        bodyDiv.innerHTML = `
        <div class="informations-professeur">
        <?php

        if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'coordinateur_prof') {

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
        } else {

            header("Location: professeur/index.php");
            exit;
        }
        ?>
    </div>
        `;
    </script>



</body>

</html>