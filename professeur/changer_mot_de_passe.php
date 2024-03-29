<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'professeur') {
    header("Location: index.php");
    exit;
}

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ancien_mdp']) && isset($_POST['nouveau_mdp']) && isset($_POST['confirmer_mdp'])) {
    // Récupérer les données du formulaire
    $ancien_mdp = $_POST['ancien_mdp'];
    $nouveau_mdp = $_POST['nouveau_mdp'];
    $confirmer_mdp = $_POST['confirmer_mdp'];

    // Vérifier si les nouveaux mots de passe correspondent
    if ($nouveau_mdp !== $confirmer_mdp) {
        echo "Les nouveaux mots de passe ne correspondent pas.";
        exit;
    }

    // Connexion à la base de données
    require_once '../include/database.php';

    // Récupérer l'ID de l'utilisateur
    $userId = $_SESSION['user_id'];

    try {
        // Préparer et exécuter la requête pour récupérer le mot de passe actuel de l'utilisateur
        $stmt = $pdo->prepare("SELECT Password FROM professeur WHERE id = :id");
        $stmt->execute(['id' => $userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérifier si l'ancien mot de passe correspond
        if ($row && $ancien_mdp === $row['Password']) {
            // Mettre à jour le mot de passe dans la base de données
            $stmt = $pdo->prepare("UPDATE professeur SET Password = :password WHERE id = :id");
            $stmt->execute(['password' => $nouveau_mdp, 'id' => $userId]);

            // Rediriger l'utilisateur vers une page de confirmation ou la page d'accueil après la modification du mot de passe
            header("Location: ../professeur/confirmation.php");
            exit;
        } else {
            // Afficher un message d'erreur si l'ancien mot de passe est incorrect
            echo "L'ancien mot de passe est incorrect.";
        }
    } catch (PDOException $e) {
        // En cas d'erreur lors de la modification du mot de passe
        echo "Erreur lors de la modification du mot de passe : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../professeur/assets/index.css">
    <link rel="stylesheet" href="../professeur/assets/changer_mot_de_passe.css">
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

        .change {
            width: 50%;
            /* Largeur de la div */
            margin: auto;
            /* Centrer la div horizontalement */
            padding: 20px;
            /* Espacement intérieur */
            border: 1px solid #ccc;
            /* Bordure */
            border-radius: 5px;
            /* Coins arrondis */
            background-color: #f9f9f9;
            /* Couleur de fond */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            /* Ombre */
        }

        .change h2 {
            text-align: center;
            /* Centrer le titre */
            color: #333;
            /* Couleur du texte */
        }

        .change hr {
            margin-top: 20px;
            /* Espacement avant la ligne horizontale */
            margin-bottom: 20px;
            /* Espacement après la ligne horizontale */
            border: none;
            /* Supprimer la bordure */
            border-top: 1px solid #ccc;
            /* Style de la ligne */
        }

        .change h3 {
            color: #666;
            /* Couleur du sous-titre */
        }

        .change p {
            margin-bottom: 20px;
            /* Espacement après le paragraphe */
        }

        .change label {
            display: block;
            /* Afficher les labels en bloc */
            margin-bottom: 5px;
            /* Espacement après chaque label */
            font-weight: bold;
            /* Gras */
        }

        .change input[type="password"] {
            width: 100%;
            /* Largeur totale */
            padding: 10px;
            margin-left: -11px;
            /* Espacement intérieur */
            margin-bottom: 15px;
            /* Espacement après chaque champ */
            border: 1px solid #ccc;
            /* Bordure */
            border-radius: 4px;
            /* Coins arrondis */
        }

        .change input[type="submit"],
        .change button {
            width: 100%;
            /* Largeur totale */
            padding: 10px;
            /* Espacement intérieur */
            border: none;
            /* Supprimer la bordure */
            border-radius: 4px;
            /* Coins arrondis */
            background-color: #007bff;
            /* Couleur de fond */
            color: #fff;
            /* Couleur du texte */
            cursor: pointer;
            /* Curseur de pointeur */
            transition: background-color 0.3s;
            /* Transition de couleur */
        }

        .change input[type="submit"]:hover,
        .change button:hover {
            background-color: #0056b3;
            /* Changement de couleur au survol */
        }

        .change a {
            text-decoration: none;
            /* Supprimer le soulignement des liens */
        }

        .change h3 {
            background-color: green;
            margin-right: 300px;
            color: #f9f9f9;
            padding-left: 15px;
            border-radius: 5px;
        }

        .change button[type="button"] {
            margin-top: 10px;
            /* Espacement avant le bouton */
            background-color: #ccc;
            /* Couleur de fond du bouton annuler */
            color: #333;
            /* Couleur du texte du bouton annuler */
        }

        .change button[type="button"]:hover {
            background-color: #999;
            /* Changement de couleur au survol du bouton annuler */
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
                            <li><a href="../professeur/changer_mot_de_passe.php"><i class="fas fa-lock"></i> Changer mot de passe</a></li>
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

            <div class="change">
                <h2>  <i class="fas fa-key"></i> Changer le mot de passe</h2>
                <hr>
                <h3>Règles du mot de passe:</h3>
                <p>- Le nombre de caractères du mot de passe doit être entre 10 et 40. <br>
                    - Le mot de passe doit contenir au moins un chiffre. <br>
                    - Le mot de passe doit contenir au moins un caractère majuscule. <br>
                    - Le mot de passe doit contenir au moins un symbole. <br>
                    Le mot de passe doit contenir au moins un caractère majuscule.</p>
                <form action="changer_mot_de_passe.php" method="post">
                    <label for="ancien_mdp">Ancien mot de passe :</label>
                    <input type="password" id="ancien_mdp" name="ancien_mdp" required><br><br>
                    <label for="nouveau_mdp">Nouveau mot de passe :</label>
                    <input type="password" id="nouveau_mdp" name="nouveau_mdp" required><br><br>
                    <label for="confirmer_mdp">Confirmer le nouveau mot de passe :</label>
                    <input type="password" id="confirmer_mdp" name="confirmer_mdp" required><br><br>
                    <input type="submit" value="Modifier le mot de passe">
                    <a href="../professeur/index.php"><button type="button">Annuler</button></a>
                </form>
            </div>
        </div>
    </div>

</body>

</html>