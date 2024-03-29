<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../professeur/assets/en_savoir_plus.css">
    <title>en savoir plus sur le site</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-xgWvbC/GtpG27dbUMf057Ok6ZgoyNnuToSCzjUEuFQlyDhVdRflh5JL4tsbvtRL8yK1z2CqS3hINQjyGv7wXVg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
                <button class="savoir_plus"><a href="en-saoir_plus.php">En Savoir plus sur e_services</a></button><br><br><br>
            </div>
            <div class="signaler">
                <h1>Signaler un bug </h1>
                <p>Si vous constatez la moindre anomalie n'hésitez pas à nous contactez via l'email <a href="https://accounts.google.com/SignOutOptions?hl=fr&continue=https://mail.google.com/mail&service=mail&ec=GBRAFw">bellafatimazahrae@gmail.com</a></p>
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
            </nav>
            <div class="titre_txt">
                <h1>L'application eServices de l'ENSAH</h1>
            </div>
            <div class="texte">
                <p>e-Services est une application Web développée à l'Ecole Nationale des Sciences Appliquées par BELLA Fatima Zohra ,AMMARA Abderrahmane et AISSAM Amine. Son objectif est d'offrir un point d’accès unique où les enseignants, les étudiants et l’ensemble du personnel de l’établissement, peuvent trouver les informations, outils et services numériques en rapport avec leurs activités pédagogiques ou administratives.</p><br>
                <p>L’application a été crée au début pour couvrir des besoins simples concernant la communication des étudiants avec le service de scolarité. Et ce dans l'objectif de simplifier aux étudiants les procédures de consulter leur notes et les demandes des attestations administratives.</p>

                <p>
                    À propos du développeurs :
                    Etudiants dans
                    Ecole Nationale des Sciences Appliquées - Al HOCEIMA <br>
                    Email: <a href="mailto:belllafatimazahrae@gmail.com">bellafatimazahrae@gmail.com</a><br>
                    Email: <a href="mailto:belllafatimazahrae@gmail.com">bellafatimazahrae@gmail.com</a><br>
                    Email: <a href="mailto:belllafatimazahrae@gmail.com">bellafatimazahrae@gmail.com</a><br>
                    Linkedin : <a href=""></a>
                </p>
            </div>
        </div>
    </div>
    <footer>
        <p>E-SERVICES © Copyright 2024 - Dévelopée par </p>
    </footer>
</body>

</html>