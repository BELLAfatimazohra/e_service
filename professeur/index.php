<?php
session_start();
if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'professeur') {
    $userId = $_SESSION['user_id'];
} else {

    header("Location: index.php");
    exit;
}


?>
<?php





if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'professeur') {

    echo $_SESSION['user_type'] . "verif";
    header("Location: index.php");
    exit;
}

$userId = $_SESSION['user_id'];

include "../include/database.php";

try {
    $stmt_filieres = $pdo->prepare("SELECT DISTINCT f.id, f.Nom_filiere FROM module m INNER JOIN filiere f ON m.id_filiere = f.id WHERE m.id_prof = :id_prof");
    $stmt_filieres->execute(['id_prof' => $userId]);
    $filieres = $stmt_filieres->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erreur lors de l'exécution de la requête : " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/index.css">
    <link rel="stylesheet" href="assets/include/sidebarProf.css">
    <title>Acceuil</title>

</head>

<body>

    <?php
    include 'assets/include/sidebarProf.php';
    ?>

    <div class="bodyDiv">


        <div class="bienvenue">
            <h1>Bienvenue sur la plateforme e-Services</h1>
        </div>
        <div class="options">
            <div class="col1">

                <a class="opt" href="messages/message.php"><button class="btn2"> Messages<svg class="btnsvg" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                            <path d="M160-160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h640q33 0 56.5 23.5T880-720v480q0 33-23.5 56.5T800-160H160Zm320-280L160-640v400h640v-400L480-440Zm0-80 320-200H160l320 200ZM160-640v-80 480-400Z" />
                        </svg></button></a> <br>
                <a class="opt" href="cours.php"><button class="btn3">Importer Cours<svg class="btnsvg" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                            <path d="M480-320 280-520l56-58 104 104v-326h80v326l104-104 56 58-200 200ZM240-160q-33 0-56.5-23.5T160-240v-120h80v120h480v-120h80v120q0 33-23.5 56.5T720-160H240Z" />
                        </svg></button></a> <br>
                <a class="opt" href="emploi.php"><button class="btn4">Emploi du Temps <svg class="btnsvg" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                            <path d="M320-400q-17 0-28.5-11.5T280-440q0-17 11.5-28.5T320-480q17 0 28.5 11.5T360-440q0 17-11.5 28.5T320-400Zm160 0q-17 0-28.5-11.5T440-440q0-17 11.5-28.5T480-480q17 0 28.5 11.5T520-440q0 17-11.5 28.5T480-400Zm160 0q-17 0-28.5-11.5T600-440q0-17 11.5-28.5T640-480q17 0 28.5 11.5T680-440q0 17-11.5 28.5T640-400ZM200-80q-33 0-56.5-23.5T120-160v-560q0-33 23.5-56.5T200-800h40v-80h80v80h320v-80h80v80h40q33 0 56.5 23.5T840-720v560q0 33-23.5 56.5T760-80H200Zm0-80h560v-400H200v400Zm0-480h560v-80H200v80Zm0 0v-80 80Z" />
                        </svg></button></a>
            </div>
            <!-- Partie HTML de votre page -->
            <div class="col2">
                <div class="news-container" id="news-container">
                    <h1 class="h1-act"> <i class="fas fa-bell"></i> Actualites</h1>
                    <hr>
                    <?php
                    include '../include/database.php';
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
                                echo "<img src='" . htmlspecialchars($row['image_url']) . "' alt='Image de lactualité'>";
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


            <div class="filiere-list">
                <h2>Liste des filières enseignées</h2>
                <ul>
                    <?php foreach ($filieres as $filiere) : ?>
                        <a href="module.php?filiere_id=<?php echo $filiere['id']; ?>"><?php echo $filiere['Nom_filiere']; ?></a>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>



    </div>


    <footer>
        E-SERVICES © Copyright 2020 - Dévelopée par AMMARA ABDERRAHMANE & BELLA FATIMA ZOHRA
    </footer>

</body>

</html>