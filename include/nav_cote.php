<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceuil</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-xgWvbC/GtpG27dbUMf057Ok6ZgoyNnuToSCzjUEuFQlyDhVdRflh5JL4tsbvtRL8yK1z2CqS3hINQjyGv7wXVg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .container {
            position: relative;
            min-height: 100vh;
            /* Hauteur minimale de la page égale à 100% de la hauteur de la fenêtre du navigateur */
            display: flex;
            background-color: #f1f1f1;
        }

        .column1 {
            flex: 18%;
            /* Prendre le double de l'espace par rapport à la colonne 2 */
            background-color: white;
            padding: 0;
            margin: 0;
            top: 0;
            bottom: 0;
            left: 0;
            height: 100%;
            padding-bottom: 0px;
            overflow-y: auto;
            margin-bottom: 0px;
            padding-bottom: 0%;
        }

        .column2 {

            flex: 82%;
            display: flex;
            flex-direction: column;
            background-color: #f1f1f1;
        }



        .dropdown-content {
            display: none;
            position: fixed;
            /* Définit la position fixe pour le menu */
            top: 50px;
            /* Le menu sera fixé en haut de la fenêtre */
            right: 0;
            /* Le menu sera aligné sur le bord droit */
            width: 10%;
            /* Le menu occupera toute la largeur de la fenêtre */
            background-color: white;
            /* Couleur de fond du menu */
            z-index: 1;
            /* Assure que le menu s'affiche au-dessus du contenu de la page */
            padding: 30px;
            /* Ajoutez un peu de marge intérieure pour le contenu du menu */
        }

        .acceuil {
            background-color: white;
            border: none;
            color: blue;
            font-size: 13px;
            text-align: center;
            float: center;
            margin-left: 10px;
            font-family: Arial, sans-serif;
            /* Exemple de police de caractères */
        }

        .acceuil i {
            font-size: 15px;
            color: blue;
        }

        .option_left {
            margin-top: 50px;
        }

        .e_service {
            padding: 10px;
            background-color: gray;
            margin-right: 10px;
            margin-left: 10px;
            margin-top: 80px;
            border-radius: 5px;
        }

        .signaler {
            background-color: white;
            padding: 0;
            margin: 0;
            margin-bottom: 0;
        }

        .signaler p {
            bottom: 0;
            left: 0;
            margin: 0;
            margin-bottom: 0;
            justify-content: center;
            text-align: center;
        }

        .signaler h1 {
            margin-top: 20px;
            height: 30px;
            text-align: center;
            font-size: 20px;

        }


        .savoir_plus {
            height: 40px;
            background-color: darkolivegreen;
            border: none;
        }

        .savoir_plus a {
            text-decoration: none;
            color: white;
        }

        /* Style pour le survol des liens dans le menu déroulant */
        .dropdown-content {
            display: none;
            /* Caché par défaut */
            position: absolute;
            /* Position absolue pour le positionner par rapport au bouton */
            top: 40px;
            /* Espacement par rapport au haut */
            right: 0;
            /* Aligné sur le bord droit */
            background-color: #fff;
            /* Couleur de fond */
            min-width: 160px;
            /* Largeur minimale */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            /* Ombre légère */
            z-index: 1;
            /* Assurer que le menu s'affiche au-dessus du contenu */
            border-radius: 5px;
            /* Coins arrondis */
            padding: 10px 0;
            /* Espacement intérieur */
        }

        /* Style pour les éléments de la liste */
        .dropdown-content ul {
            list-style-type: none;
            /* Supprimer les puces */
            padding: 0;
            /* Supprimer le padding */
            margin: 0;
            /* Supprimer les marges */
        }

        /* Style pour les liens dans le menu déroulant */
        .dropdown-content a {
            display: block;
            /* Affichage en bloc */
            padding: 5px 10px;
            /* Espacement intérieur */
            text-decoration: none;
            /* Supprimer le soulignement par défaut */
            color: black;
            /* Couleur du texte */
            transition: background-color 0.3s;
            /* Transition fluide pour le survol */
        }

        /* Style pour le survol des liens */
        .dropdown-content a:hover {
            background-color: #f0f0f0;
            /* Couleur de fond au survol */
        }

        nav {
            background-color: #ccc;
            overflow: hidden;
            display: flex;
            justify-content: space-between;
            /* Pour espacer les éléments à l'intérieur de la barre de navigation */
            align-items: center;
            /* Pour aligner les éléments verticalement au centre */
            flex: 5%;
            padding: 0 10px;
            /* Ajoute une marge interne pour éviter le chevauchement */
        }

        .msg,
        .notif,
        .dropbtn {
            background-color: #ccc;
            border: none;
            padding: 10px;
            margin: 0;
        }

        .msg i,
        .notif i,
        .dropbtn i {
            font-size: 20px;
            color: #007bff;
        }


        .body {
            flex: 90%;
            background-color: #d6d5d5;
        }

        .dropbtn {
            float: right;

            margin-right: 10px;
            background-color: #ccc;
            border: none;
        }

        .dropbtn i {

            text-align: center;
            font-size: 20px;
            /* Taille de l'icône */
            color: #007bff;
        }

        body {
            padding: 0px;
            margin: 0px;
        }

        .search-container {
            display: flex;
            align-items: center;
            /* Pour centrer verticalement */
            margin-top: 0px;
            padding-left: 20px;
            border-left: 1px solid #ccc;
            flex: 1;
            /* Pour que la barre de recherche occupe tout l'espace disponible */
        }

        .search-container input[type="text"] {
            flex: 1;
            /* Pour que le champ de saisie occupe tout l'espace disponible */
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .search-container button {
            padding: 8px;
            border: none;
            background-color: #007bff;
            color: #fff;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-container button:hover {
            background-color: #0056b3;
        }




        .bienvenue button {
            width: 150px;
            margin-left: 80px;
        }

        .ns {
            text-align: center;

        }






        .savoir_plus {
            border: none;
            border-radius: 5px;
        }

        footer {
            height: 0px;
            text-align: center;
        }


        .fas fa-chevron-right {
            margin-left: 5px;
        }

        .txt {
            text-decoration: none;
            justify-content: center;
            text-align: center;
            color: black;
            font-style: italic;
            /* Ajout de l'italique au texte */
            font-size: 16px;
            /* Taille de la police */
            margin: 20px;
            /* Ajout de marges autour du paragraphe */
        }
    </style>
</head>

<body>

    <div class="container">
        <!-- Première colonne -->
        <div class="column1">
            <div class="option_left">
                <button class="acceuil"><a href="../professeur/index.php"><i class="fas fa-home"></i></a> Acceuil <a href="../professeur/index.php"><i class="fas fa-chevron-right"></i></button><br><br>
                <button class="acceuil"><a href="personnel.php"><i class="fas fa-users"></i></a> Personnel <a href="personnel.php"> <i class="fas fa-chevron-right"></i></button><br><br>
                <button class="acceuil"><a href="profil.php"><i class="fas fa-user"></i></a> Profil <a href="profil.php"><i class="fas fa-chevron-right"></i></button><br><br>
                <button class="acceuil"><a href="about.php"><i class="fa-question-circle"></i></a> About ENSAH <a href="about.php"><i class="fas fa-chevron-right"></i></button><br><br>
                <button class="acceuil"><a href="biblio.php"><i class="fas fa-book"></i></a> Bibliothque <a href="biblio.php"><i class="fas fa-chevron-right"></i></button><br><br>
            </div>
            <div class="e_service">
                <p class="txt">ce site est une application Web développée par BELLA Fatima Zohra et AMMARA Abderrahmane pour offrir un ensemble de services numériques aux membres de l'établissement .</p>
                <button class="savoir_plus"><a href="../professeur/en_savoir_plus.php">En Savoir plus sur e_services</a></button><br><br><br>
            </div>
            <div class="signaler">
                <h1>Signaler un bug </h1>
                <p>Si vous constatez la moindre anomalie n'hésitez pas à nous contactez via l'email <a href="mailto:support_ensah@gmail.com">support_ensah@gmail.com</a></p>
            </div>
        </div>

        <div class="column2">
            <nav>
                <div class="search-container">
                    <form>
                        <input type="text" id="searchInput" placeholder="Rechercher..." name="search">
                        <button type="button" onclick="highlightSearch()"><i class="fas fa-search"></i></button>
                    </form>
                </div>
                <div class="dropdown">
                    <button class="dropbtn" onclick="toggleDropdown()"><i class="fas fa-user fa-lg"></i></button><br><br>
                    <div class="dropdown-content" id="dropdownContent">
                        <ul>
                            <li><a href="../professeur/profil.php"><i class="fas fa-user"></i> Profil </a></li>
                            <li><a href="../professeur/se_deconnecter.php"><i class="fas fa-sign-out-alt"></i> Se déconnecter </a></li>
                            <li><a href="../professeur/changer_mot_de_passe.php"><i class="fas fa-lock"></i> Changer mot de passe</a></li>
                        </ul>
                    </div>
                </div>
                <button class="msg"><a href="message.php"><i class="fas fa-envelope"></i>
                    </a></button>
                <button class="notif"><a href="notif.php"><i class="fas fa-bell"></i>
                    </a></button>
                <script>
                    function toggleDropdown() {
                        console.log("Toggle dropdown function called");
                        var dropdownContent = document.getElementById("dropdownContent");
                        if (dropdownContent.style.display === "block") {
                            dropdownContent.style.display = "none";
                        } else {
                            dropdownContent.style.display = "block";
                        }
                    }
                </script>

            </nav>
            <div class="body">

            </div>
        </div>

    </div>
    <footer>
        <p>E-SERVICES © Copyright 2024 - Développée par BELLA Fatima Zohra & AMMARA Abderrahmane</p>
    </footer>

</body>

</html>