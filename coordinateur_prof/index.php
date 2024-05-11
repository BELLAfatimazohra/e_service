<?php

session_start();
$email = $_SESSION['email'];
$password = $_SESSION['password'];
$_SESSION['user_type'] = 'coordinateur_prof';
if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'coordinateur_prof') {
    $userId = $_SESSION['user_id'];
} else {

    header("Location: index.php");
    exit;
}
echo $_SESSION['user_type'] ."11";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../professeur/assets/index.css">
    <link rel="stylesheet" href="include/sidebarCoor.css">
    <title>Acceuil</title>
</head>

<body>

    <?php
    include 'include/sidebarCoor.php';
    ?>


    <div class="bodyDiv">

        <div class="bienvenue">
            <h1>Bienvenue sur la plateforme <br> e-Services</h1>
        </div>
        <div class="options">
            <div class="col1">
                <button class="btn2"><i class="fas fa-envelope"></i><a class="opt" href="messages/message.php"> Messages</a> </button><br>
                <button class="btn4"><i class="fas fa-calendar-alt"></i> <a class="opt" href="emploi_du_temps\choisir_filiere_consulter_emploi.php"> Gerer emploi du Temps</a> </button><br>
                <button class="btn4"><i class="fas fa-calendar-alt"></i> <a class="opt" href="Gestion_modules\choisir_filiere.php"> Gerer les Modules</a> </button>
            </div>
            <!-- Partie HTML de votre page -->
            <div class="col2">
                <div class="news-container" id="news-container">
                    <h1 class="h1-act"> <i class="fas fa-bell"></i> Actualites</h1>
                    <hr>
                    <?php
                    require_once '../include/database.php';
                    try {
                        // Requête SQL pour récupérer les actualités
                        $sql = "SELECT * FROM actualites";
                        $stmt = $pdo->query($sql);

                        // Affichage des actualités
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<div class='actualite'>";
                            echo "<div class='news-content'>";
                            echo "<p><strong>Date :</strong> " . htmlspecialchars($row['date_actualite']) . "</p>";
                            if (!empty($row['image_url'])) {
                                echo "<img src='" . htmlspecialchars($row['image_url']) . "' alt='Image de l'actualité'>";
                            }
                            echo "<div class='text-container'>";
                            echo "<p>" . htmlspecialchars($row['description']) . "</p>";
                            if (!empty($row['url_lien'])) {
                                echo "<a href='" . htmlspecialchars($row['url_lien']) . "'>lire plus</a>";
                            }
                            echo "</div>"; // Fin de .text-container
                            echo "</div>"; // Fin de .news-content
                            echo "</div>"; // Fin de .actualite
                        }
                    } catch (PDOException $e) {
                        // Gestion des erreurs de connexion
                        echo "Erreur de connexion : " . $e->getMessage();
                    }
                    ?>
                </div> <br> <br>
                <a class="url_act" href="https://ensah.ma/apps/eservices/internal/members/common/newsDetails.php"> <i class="fas fa-chevron-right"></i> Lire plus d'actualités</a>
            </div>
        </div>



        <?php 
        $_SESSION['user_type'] = 'professeur';
        $_SESSION["email"]= $email;
        $_SESSION["password"]= $password;
        ?>
        <a href="../professeur/index.php">
        <button class="changer">Accéder à la zone prof</button></a>




    </div>





</body>

</html>