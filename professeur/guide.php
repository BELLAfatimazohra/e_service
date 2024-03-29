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
            /* Pour centrer le formulaire */
            padding-left: 20px;
            /* Ajouter une marge à gauche */
            border-left: 1px solid #ccc;
            /* Ajouter une bordure à gauche */
        }

        .search-container input[type="text"] {
            flex: 1;
            /* L'input prendra tout l'espace restant */
            padding: 8px;
            /* Ajouter un padding aux côtés de l'input */
            border: 1px solid #ccc;
            /* Ajouter une bordure */
            border-radius: 4px;
            /* Ajouter un arrondi aux coins */
            margin-top: 10px;
        }

        .search-container button {
            padding: 8px;
            /* Ajouter un padding au bouton */
            border: none;
            /* Supprimer la bordure du bouton */
            background-color: #007bff;
            /* Couleur de fond du bouton */
            color: #fff;
            /* Couleur du texte du bouton */
            border-radius: 4px;
            /* Ajouter un arrondi aux coins du bouton */
            cursor: pointer;
            /* Changer le curseur au survol */
        }

        .search-container button:hover {
            background-color: #0056b3;
            /* Couleur de fond au survol */
        }

        /* Style pour la mise en surbrillance */
        .highlighted {
            background-color: yellow;
            /* Couleur de surbrillance */
        }

        .col1 hr.transparent-line {
            border: none;
            /* Supprimer le bord */
            height: 1px;
            /* Définir la hauteur de la ligne */
            background-color: transparent;
            /* Rendre la couleur de fond transparente */
        }

        .bienvenue button {
            width: 150px;
            margin-left: 80px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        /* Style pour les liens */
        a {
            text-decoration: none;
            color: blue;
        }

        a:hover {
            text-decoration: underline;
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
                <button class="savoir_plus"><a href="en-savoir_plus.php">En Savoir plus sur e_services</a></button><br><br><br>
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
                            <li><a href="#"><i class="fas fa-user"></i> Profil </a></li>
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

                                // Parcourir tous les nœuds de texte de la page
                                while (textNodes.nextNode()) {
                                    var node = textNodes.currentNode;
                                    var text = node.nodeValue; // Récupérer le texte de l'élément

                                    // Vérifier si le texte de l'élément contient le texte recherché
                                    if (text.toLowerCase().includes(searchText.toLowerCase())) {
                                        console.log('Texte trouvé dans cet élément :', node); // Afficher un message si le texte est trouvé dans l'élément

                                        // Diviser le texte en deux parties : avant et après le mot trouvé
                                        var parts = text.split(new RegExp('(' + searchText.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + ')', 'gi'));

                                        // Créer un fragment de document pour insérer le HTML
                                        var fragment = document.createDocumentFragment();

                                        // Ajouter le texte avant le mot trouvé
                                        fragment.appendChild(document.createTextNode(parts[0]));

                                        // Ajouter le mot trouvé avec mise en évidence
                                        var highlightedWord = document.createElement('span');
                                        highlightedWord.classList.add('highlighted');
                                        highlightedWord.style.backgroundColor = 'yellow';
                                        highlightedWord.textContent = parts[1];
                                        fragment.appendChild(highlightedWord);

                                        // Ajouter le texte après le mot trouvé
                                        fragment.appendChild(document.createTextNode(parts[2]));

                                        // Insérer le fragment avant le nœud de texte original
                                        node.parentNode.insertBefore(fragment, node);

                                        // Supprimer le nœud de texte original
                                        node.parentNode.removeChild(node);
                                    }
                                }
                            }
                        </script>
                    </form>
                </div>
            </nav>
            <div class="title_guide">
                <h1>Guides</h1>
            </div>
            <hr>
            <div>
                <table>
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Auteur</th>
                            <th>Date de publication</th>
                            <th>Profil visé</th>
                            <th>Version</th>
                            <th>Lien</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Utilisation de l'application eServices sur un smartphone</td>
                            <td>Tarik BOUDAA</td>
                            <td>23/04/2020</td>
                            <td>Enseignant</td>
                            <td>V5.X</td>
                            <td><a href="C:\xampp\htdocs\grades\videos\demo eservices smartphone.mp4">Ouvrir</a></td>
                        </tr>
                        <tr>
                            <td>Gestion des documents sur eServices</td>
                            <td>Tarik BOUDAA</td>
                            <td>01/04/2020</td>
                            <td>Enseignant</td>
                            <td>V5.X</td>
                            <td><a href="">Ouvrir</a></td>
                        </tr>
                        <tr>
                            <td>Enregistrement et partage d'une présentation vidéo par google meet</td>
                            <td>CHERRADI MOHAMED</td>
                            <td>01/04/2020</td>
                            <td>Enseignant</td>
                            <td>V5.X</td>
                            <td><a href="">Ouvrir</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <footer>
        <p>E-SERVICES © Copyright 2024 - Dévelopée par </p>
    </footer>
</body>

</html>