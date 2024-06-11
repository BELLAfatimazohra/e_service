<?php

session_start();
$email = $_SESSION['email'];
$password = $_SESSION['password'];
if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'chef_departement') {
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

    <link rel="stylesheet" href="include/sidbar_chef_dep.css">

    <title>Acceuil</title>
    <style>
        :root {
            --body-bg: rgb(243, 243, 243);
            --nav-bg: #3d44d1;
        }

        * {
            font-family: poppins, Helvetica, sans-serif;
        }

        .options {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            grid-row-gap: 3rem;
            place-items: center;
        }

        h1 {
            color: rgb(0, 0, 0, 0.7);
            font-weight: 300;
            margin-left: 1rem;
            margin-top: 1.5rem;
        }

        .col1 {
            display: flex;
            justify-content: center;
            gap: 100px;
            grid-column: span 5;
            width: 100%;
        }

        /* Styles généraux pour les boutons */

        .btn1,
        .btn2,
        .btn3,
        .btn4 {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px 10px;
            border: 2px solid #474dcb;
            border-radius: 15px;
            cursor: pointer;
            color: white;
            font-size: 1.2rem;
            height: 6rem;
            text-align: center;
            width: 20rem;
            background-color: var(--nav-bg);
            overflow: hidden;
            box-shadow: 5px 5px 2ch rgb(209, 209, 209);
            transition: all 0.2s;
            margin: 0.3rem 1rem;
        }

        .btn1:hover,
        .btn2:hover,
        .btn3:hover,
        .btn4:hover {
            box-shadow: 5px 5px 3ch 0.5px rgb(161, 161, 161);
            width: 23rem;
            background-color: #2028c4;
            margin: 0;
            border-color: rgb(47, 56, 159);

        }

        .opt {
            text-decoration: none;
            color: rgb(255, 255, 255);
        }

        /* Style pour l'icône à l'intérieur des boutons */
        .btnsvg {
            position: absolute;
            height: fit-content;
            width: 4rem;
            top: -5%;
            right: 1%;
            opacity: 0.6;
            z-index: -1;
            transition: all 0.2s;
        }

        .btn1:hover .btnsvg,
        .btn2:hover .btnsvg,
        .btn3:hover .btnsvg,
        .btn4:hover .btnsvg {
            opacity: 01;
            right: 71%;
            top: 0%;
            width: 4.5rem;
        }

        .btnsvg path {
            fill: rgb(110, 99, 255);
        }

        .col2 {
            max-width: 400px;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
            padding: 0 20px;
            grid-column: 4/6;
            margin-right: 1rem;
            place-self: end;
            color: black;
        }

        .news-container {
            padding: 0.3rem 1.3rem;
        }

        .news-container .actualite {
            margin-bottom: 1.3rem;
        }

        .h1-act {
            font-size: 1.5rem;
            text-align: center;
            font-weight: bold;
            margin-bottom: 10px;
            color: black;
        }

        .news-content {
            margin: 1rem 0.2rem;
            display: grid;
            grid-template-columns: 0.8fr 0.6fr 0.6fr;
            place-items: start;
            align-items: center;
            position: relative;
        }

        .news-content::after {
            content: "";
            background-color: rgb(0, 0, 0, 0.3);
            position: absolute;
            width: 95%;
            height: 1px;
            left: 50%;
            transform: translateX(-50%);
            bottom: -5%;
        }

        .news-content p {
            margin: 0.5rem 0;
            grid-column: span 3;
        }

        .news-content img {
            height: auto;
            margin-bottom: 10px;
        }

        .text-container {
            grid-column: span 2;
        }

        .text-container p {
            margin-bottom: 2px;
        }

        .url_act {
            display: block;
            text-align: center;
            margin-top: 10px;
            color: #007bff;
            font-size: 18px;
            text-decoration: none;
            margin-bottom: 30px;
        }

        .url_act:hover {
            text-decoration: underline;
        }

        .actua {
            background-color: rgb(247, 247, 239);
        }

        .option_left i {
            color: #ccc;
        }

        .option_left i:hover {
            color: white;
        }

        .news-container i {
            color: black;
            font-size: 24px;
            transition: transform 0.3s ease;
        }

        .news-container i:hover {
            transform: translateY(-5px);
        }

        .url_act:hover {
            color: darkblue;
            text-decoration: underline;
        }

        .actualite {
            padding-left: 10px;
            margin-bottom: 15px;
            max-width: 2000px;
            color: black;
            width: 100%;
            padding-bottom: -2px;
        }

        .actualite img {
            max-width: 100px;
            height: 90px;
        }

        .filiere-list {
            background-color: #fff;
            padding: 20px;
            margin-left: 1rem;
            border-radius: 15px;
            grid-row: 2/4;
            grid-column: span 3;
            place-self: start;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .filiere-list h2 {
            font-size: 24px;
            color: #333;
            margin-bottom: 30px;
            margin-left: 1rem;
        }

        .filiere-list a {
            display: block;
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .filiere-list a:hover {
            color: #0056b3;
        }
    </style>
</head>

<body>

    <?php
    require_once '.././include/database.php';
    include_once 'include/sidebar_chef_dep.php';
    
$userId = $_SESSION['user_id'];
echo $_SESSION['user_id'];
    ?>


    <div class="bodyDiv">

        <div class="bienvenue">
            <h1>Bienvenue sur la plateforme e-Services</h1>
        </div>
        <div class="options">
            <div class="col1">

                 <a class="opt" href="gestion_professeur/choisir_filiere.php"><button class="btn4"> Gerer les Modules<svg class="btnsvg" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M400-400h160v-80H400v80Zm0-120h320v-80H400v80Zm0-120h320v-80H400v80Zm-80 400q-33 0-56.5-23.5T240-320v-480q0-33 23.5-56.5T320-880h480q33 0 56.5 23.5T880-800v480q0 33-23.5 56.5T800-240H320Zm0-80h480v-480H320v480ZM160-80q-33 0-56.5-23.5T80-160v-560h80v560h560v80H160Zm160-720v480-480Z"/></svg></button></a> <br>
                 <a class="opt" href="Gestion_modules/choisir_filiere.php"><button class="btn4"> Gerer les professeurs<svg class="btnsvg" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M440-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47Zm0-80q33 0 56.5-23.5T520-640q0-33-23.5-56.5T440-720q-33 0-56.5 23.5T360-640q0 33 23.5 56.5T440-560ZM884-20 756-148q-21 12-45 20t-51 8q-75 0-127.5-52.5T480-300q0-75 52.5-127.5T660-480q75 0 127.5 52.5T840-300q0 27-8 51t-20 45L940-76l-56 56ZM660-200q42 0 71-29t29-71q0-42-29-71t-71-29q-42 0-71 29t-29 71q0 42 29 71t71 29Zm-540 40v-111q0-34 17-63t47-44q51-26 115-44t142-18q-12 18-20.5 38.5T407-359q-60 5-107 20.5T221-306q-10 5-15.5 14.5T200-271v31h207q5 22 13.5 42t20.5 38H120Zm320-480Zm-33 400Z"/></svg></button></a> 
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