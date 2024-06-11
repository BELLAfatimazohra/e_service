<?php

session_start();

if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'etudiant') {
  $userId = $_SESSION['user_id'];
} else {
  header("Location: index.php");
  exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../professeur/assets/index.css">
  <link rel="stylesheet" href="include/sidebarEtud.css">
  <title>Acceuil</title>
<style>
  .col1{
    justify-content: center;
    gap: 100px;
  }
</style>
</head>

<body>

  <?php
  require_once '../include/database.php';
  include_once 'include/sidebarEtud.php';
  ?>


  <div class="bodyDiv">

    <div class="bienvenue">
      <h1>Bienvenue sur la plateforme e-Services</h1>
    </div>
    <div class="options">
      <div class="col1">

        <button class="btn4"><a class="opt" href="consulter_notes.php"> Mes notes<svg class="btnsvg" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M360-120q-33 0-56.5-23.5T280-200v-400q0-33 23.5-56.5T360-680h400q33 0 56.5 23.5T840-600v400q0 33-23.5 56.5T760-120H360Zm0-400h400v-80H360v80Zm160 160h80v-80h-80v80Zm0 160h80v-80h-80v80ZM360-360h80v-80h-80v80Zm320 0h80v-80h-80v80ZM360-200h80v-80h-80v80Zm320 0h80v-80h-80v80Zm-480-80q-33 0-56.5-23.5T120-360v-400q0-33 23.5-56.5T200-840h400q33 0 56.5 23.5T680-760v40h-80v-40H200v400h40v80h-40Z"/></svg></a> </button><br>
        <button class="btn4"><a class="opt" href="affichage_emploi_temps/affichage_emploi_temps.php">Emploi du Temps<svg class="btnsvg" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M320-400q-17 0-28.5-11.5T280-440q0-17 11.5-28.5T320-480q17 0 28.5 11.5T360-440q0 17-11.5 28.5T320-400Zm160 0q-17 0-28.5-11.5T440-440q0-17 11.5-28.5T480-480q17 0 28.5 11.5T520-440q0 17-11.5 28.5T480-400Zm160 0q-17 0-28.5-11.5T600-440q0-17 11.5-28.5T640-480q17 0 28.5 11.5T680-440q0 17-11.5 28.5T640-400ZM200-80q-33 0-56.5-23.5T120-160v-560q0-33 23.5-56.5T200-800h40v-80h80v80h320v-80h80v80h40q33 0 56.5 23.5T840-720v560q0 33-23.5 56.5T760-80H200Zm0-80h560v-400H200v400Zm0-480h560v-80H200v80Zm0 0v-80 80Z"/></svg> </a> </button>
      </div>
      <!-- Partie HTML de votre page -->
      <div class="col2">
        <div class="news-container" id="news-container">
          <h1 class="h1-act"> <i class="fas fa-bell"></i> Actualites</h1>
          <hr>
          <?php
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



    <script>
      document.querySelectorAll("li").forEach(function(li) {
        if (li.classList.contains("active")) {
          li.classList.remove("active");
        }
      });

      document.querySelector(".liHome").classList.add("active");
    </script>

  </div>





</body>

</html>