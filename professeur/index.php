User
<?php
// Démarrer la session
session_start();
// Vérifier si l'utilisateur est connecté en tant que professeur
if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'professeur') {
    // Récupérer l'ID de l'utilisateur connecté
    $userId = $_SESSION['user_id'];
    // Vous pouvez utiliser $userId ici pour accéder aux données spécifiques du professeur
    // par exemple, vous pouvez récupérer les données du professeur à partir de la base de données en utilisant cet ID
} else {
    // Rediriger l'utilisateur vers la page de connexion s'il n'est pas connecté
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
    <title>Acceuil</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-xgWvbC/GtpG27dbUMf057Ok6ZgoyNnuToSCzjUEuFQlyDhVdRflh5JL4tsbvtRL8yK1z2CqS3hINQjyGv7wXVg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .search-container {
            display: flex;
            align-items: center;
            margin-left: auto;

            padding-left: 20px;

            border-left: 1px solid #ccc;

        }

        .search-container input[type="text"] {
            flex: 1;

            padding: 8px;

            border: 1px solid #ccc;

            border-radius: 4px;

            margin-top: 10px;
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


        .highlighted {
            background-color: yellow;

        }

        .col1 hr.transparent-line {
            border: none;

            height: 1px;

            background-color: transparent;

        }

        .bienvenue button {
            width: 150px;
            margin-left: 80px;
        }
    </style>
</head>

<body>

    <div class="container">
        <!-- Première colonne -->
        <div class="column1">
            <div class="logobt">
                <img src="../images/logo-eservices.png" alt="" class="logo_eservice"><br>
            </div>
            <div class="option_left">
                <button class="acceuil"><a href="index.php"><i class="fas fa-home"></i></a> Acceuil</button><br><br>
                <button class="acceuil"><a href="personnel.php"><i class="fas fa-users"></i></a> Personnel</button><br><br>
                <button class="acceuil"><a href="biblio.php"><i class="fas fa-book"></i></a> Bibliothque</button><br><br>
            </div>
            <div class="e_service">
                <p class="txt">ce site est une application Web développée par BELLA Fatima Zohra ,AMMARA Abderrahmane et AISSAM Amine pour offrir un ensemble de services numériques aux membres de l'établissement .</p>
                <button class="savoir_plus"><a href="../professeur/en_savoir_plus.php">En Savoir plus sur e_services</a></button><br><br><br>
            </div>
            <div class="signaler">
                <h1>Signaler un bug </h1>
                <p>Si vous constatez la moindre anomalie n'hésitez pas à nous contactez via l'email <a href="mailto:bellafatimazahrae@gmail.com">bellafatimazahrae@gmail.com</a></p>
            </div>
        </div>

        <!-- Deuxième colonne -->
        <div class="column2">
            <nav>
                <div class="dropdown">
                    <button class="dropbtn" onclick="toggleDropdown()"><i class="fas fa-user"></i></button><br><br>
                    <div class="dropdown-content" id="dropdownContent">
                        <ul>
                            <li><a href="../professeur/profil.php"><i class="fas fa-user"></i> Profil </a></li>
                            <li><a href="#"><i class="fas fa-sign-out-alt"></i> Se déconnecter </a></li>
                            <li><a href="changer_mot_de_passe.php"><i class="fas fa-lock"></i> Changer mot de passe</a></li>
                        </ul>
                    </div>
                </div>
                <button class="msg"><a href=""><i class="fas fa-envelope"></i>
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
                <div class="search-container">
                    <form>
                        <input type="text" id="searchInput" placeholder="Rechercher..." name="search">
                        <button type="button" onclick="highlightSearch()"><i class="fas fa-search"></i></button>
                        <script>
                            function highlightSearch() {
                                var searchText = document.getElementById('searchInput').value.trim(); // Récupérer le texte de recherche
                                console.log('Texte de recherche :', searchText); // Afficher le texte de recherche dans la console

                                var textNodes = document.createTreeWalker(
                                    document.body,
                                    NodeFilter.SHOW_TEXT,
                                    null,
                                    false
                                );


                                while (textNodes.nextNode()) {
                                    var node = textNodes.currentNode;
                                    var text = node.nodeValue;


                                    if (text.toLowerCase().includes(searchText.toLowerCase())) {
                                        console.log('Texte trouvé dans cet élément :', node);

                                        var parts = text.split(new RegExp('(' + searchText.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + ')', 'gi'));


                                        var fragment = document.createDocumentFragment();


                                        fragment.appendChild(document.createTextNode(parts[0]));


                                        var highlightedWord = document.createElement('span');
                                        highlightedWord.classList.add('highlighted');
                                        highlightedWord.style.backgroundColor = 'yellow';
                                        highlightedWord.textContent = parts[1];
                                        fragment.appendChild(highlightedWord);

                                        fragment.appendChild(document.createTextNode(parts[2]));


                                        node.parentNode.insertBefore(fragment, node);


                                        node.parentNode.removeChild(node);
                                    }
                                }
                            }
                        </script>
                    </form>
                </div>
            </nav>
            <div class="bienvenue">
                <h1>Bienvenue sur la plateforme <br> e-Services</h1>
                <button class="guide"><a href="../professeur/guide.php"></a><i class="fas fa-download"></i> Guide d'utilisation</button>
            </div>
            <div class="options">
                <div class="col1">
                    <button class="btn1"><a href=""></a><i class="fas fa-book"></i> Modules</button><br>
                    <button class="btn2"><a href=""></a><i class="fas fa-envelope"></i> Messages</button><br>
                    <button class="btn3"><a href=""></a><i class="fas fa-upload"></i> Importer Cours</button><br>
                    <button class="btn4"><a href=""></a><i class="fas fa-calendar-alt"></i> Emploi du Temps</button>
                </div>
                <div class="col2">
                    <div class="news-container" id="news-container">
                        <h1>Actualites</h1>
                        <hr>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer>
        <p>E-SERVICES © Copyright 2024 - Dévelopée par </p>
    </footer>
</body>

</html>