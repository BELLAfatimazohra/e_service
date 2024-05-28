<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=ensah_eservice', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}

try {
    $user_id = $_SESSION['user_id'];

    $sql = "SELECT CONCAT(Nom, ' ', Prenom) AS full_name FROM chef_departement WHERE id = :user_id";

    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $nom = $result['full_name'];
    } else {
        echo "No user found with user ID $user_id";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="sidebarCoor.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-xgWvbC/GtpG27dbUMf057Ok6ZgoyNnuToSCzjUEuFQlyDhVdRflh5JL4tsbvtRL8yK1z2CqS3hINQjyGv7wXVg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <title>e-Service</title>
</head>

<body>
    <header>
        <div class="bonjour">Bonjour! <?php echo "<span class='nom'> $nom</span>"; ?></div>

        <a href="javascript:void(0);" onclick="window.location.href='http://localhost/e_service/professeur/index.php'">
            <button class="changer">Accéder à la zone prof</button></a>
        <div class="groupnav">
            <button class="messages"><svg xmlns="http://www.w3.org/2000/svg" height="48" viewBox="0 -960 960 960" width="48">

                    <path d="M149-135q-39.05 0-66.525-27.475Q55-189.95 55-229v-502q0-39.463 27.475-67.231Q109.95-826 149-826h662q39.463 0 67.231 27.769Q906-770.463 906-731v502q0 39.05-27.769 66.525Q850.463-135 811-135H149Zm331-295L149-653v424h662v-424L480-430Zm0-83 327-218H154l326 218ZM149-653v-78 502-424Z" />
                </svg></button>
            <button class="notification"><svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                    <path d="M160-200v-80h80v-280q0-83 50-147.5T420-792v-28q0-25 17.5-42.5T480-880q25 0 42.5 17.5T540-820v28q80 20 130 84.5T720-560v280h80v80H160Zm320-300Zm0 420q-33 0-56.5-23.5T400-160h160q0 33-23.5 56.5T480-80ZM320-280h320v-280q0-66-47-113t-113-47q-66 0-113 47t-47 113v280Z" />
                </svg></button>
            <button class="profile"><svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                    <path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-160v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v112H160Zm80-80h480v-32q0-11-5.5-20T700-306q-54-27-109-40.5T480-360q-56 0-111 13.5T260-306q-9 5-14.5 14t-5.5 20v32Zm240-320q33 0 56.5-23.5T560-640q0-33-23.5-56.5T480-720q-33 0-56.5 23.5T400-640q0 33 23.5 56.5T480-560Zm0-80Zm0 400Z" />
                </svg></button>
            <div class="dropdownProfile">
                <a href="javascript:void(0);" onclick="window.location.href='http://localhost/e_service/chef_departement/profil.php'"><i class="fas fa-user"></i>
                    <div>Profil</div>
                </a>
                <a href="javascript:void(0);" onclick="window.location.href='http://localhost/e_service/se_deconnecter.php'"><i class="fas fa-sign-out-alt"></i>
                    <div>Se déconnecter </div>
                </a>
                <a href="javascript:void(0);" onclick="window.location.href='http://localhost/e_service/chef_departement/changer_mot_de_passe.php'"><i class="fas fa-lock"></i>
                    <div>Changer mot de passe</div>
                </a>

            </div>

        </div>

    </header>


    <div id="overlay" style="display: none;"></div>
    <nav id="sidebar" class="sideBarHidden">
        <div id="hideNav"><svg xmlns="http://www.w3.org/2000/svg" height="19" viewBox="0 -960 960 960" width="19">
                <path d="m321-80-71-71 329-329-329-329 71-71 400 400L321-80Z" />
            </svg></div>
        <div class="imgcontainer">
            <img class="logo" src="/e_service/public/images/logo-ensah.png" alt="ensah">
        </div>
        <ul class="nav-list">
            <li class="liHome active">
                <a href="javascript:void(0);" onclick="window.location.href='http://localhost/e_service/chef_departement/index.php'" aria-current="page">
                    <div class="group"><svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                            <path d="M240-200h120v-240h240v240h120v-360L480-740 240-560v360Zm-80 80v-480l320-240 320 240v480H520v-240h-80v240H160Zm320-350Z" />
                        </svg> <span>Acceuille</span> </div>
                    <div class="arrow-left"></div>
                </a>
            </li>



            <li class="liPersonnel">
                <a href="javascript:void(0);" onclick="window.location.href='http://localhost/e_service/chef_departement/personnel.php'">
                    <div class="group"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
                            <path d="M480-40 360-160H200q-33 0-56.5-23.5T120-240v-560q0-33 23.5-56.5T200-880h560q33 0 56.5 23.5T840-800v560q0 33-23.5 56.5T760-160H600L480-40ZM200-286q54-53 125.5-83.5T480-400q83 0 154.5 30.5T760-286v-514H200v514Zm280-194q58 0 99-41t41-99q0-58-41-99t-99-41q-58 0-99 41t-41 99q0 58 41 99t99 41ZM280-240h400v-10q-42-35-93-52.5T480-320q-56 0-107 17.5T280-250v10Zm200-320q-25 0-42.5-17.5T420-620q0-25 17.5-42.5T480-680q25 0 42.5 17.5T540-620q0 25-17.5 42.5T480-560Zm0 17Z" />
                        </svg><span>Personnel</span> </div>
                    <div class="arrow-left"></div>
                </a>
            </li>
            <li class="liModules">
                <a href="javascript:void(0);" onclick="window.location.href='http://localhost/e_service/chef_departement/gestion_modules/choisir_filiere.php'">
                    <div class="group"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
                            <path d="M400-400h160v-80H400v80Zm0-120h320v-80H400v80Zm0-120h320v-80H400v80Zm-80 400q-33 0-56.5-23.5T240-320v-480q0-33 23.5-56.5T320-880h480q33 0 56.5 23.5T880-800v480q0 33-23.5 56.5T800-240H320Zm0-80h480v-480H320v480ZM160-80q-33 0-56.5-23.5T80-160v-560h80v560h560v80H160Zm160-720v480-480Z" />
                        </svg> <span>Gestion des modules</span> </div>
                    <div class="arrow-left"></div>
                </a>
            </li>
            <li class="liEtudiants">
                <a href="/e_service/chef_departement/gestion_professeur/choisir_filiere.php">
                    <div class="group"><svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                            <path d="M440-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47Zm0-80q33 0 56.5-23.5T520-640q0-33-23.5-56.5T440-720q-33 0-56.5 23.5T360-640q0 33 23.5 56.5T440-560ZM884-20 756-148q-21 12-45 20t-51 8q-75 0-127.5-52.5T480-300q0-75 52.5-127.5T660-480q75 0 127.5 52.5T840-300q0 27-8 51t-20 45L940-76l-56 56ZM660-200q42 0 71-29t29-71q0-42-29-71t-71-29q-42 0-71 29t-29 71q0 42 29 71t71 29Zm-540 40v-111q0-34 17-63t47-44q51-26 115-44t142-18q-12 18-20.5 38.5T407-359q-60 5-107 20.5T221-306q-10 5-15.5 14.5T200-271v31h207q5 22 13.5 42t20.5 38H120Zm320-480Zm-33 400Z" />
                        </svg> <span>Gestion des professeurs</span> </div>
                    <div class="arrow-left"></div>
                </a>
            </li>
        </ul>
    </nav>


    <script>
        let profile = document.querySelector(".profile");
        let profileDropdown = document.querySelector(".dropdownProfile");

        profile.addEventListener("click", () => {
            profileDropdown.classList.toggle("show");
        });

        let showHide = document.getElementById("hideNav");
        let overlay = document.getElementById("overlay");
        showHide.addEventListener("click", () => {
            document.getElementById("sidebar").classList.toggle("sideBar");


            if (document.getElementById("sidebar").classList.contains("sideBar")) {
                showHide.style.rotate = "-180deg";
                overlay.style.display = "block";
            } else {
                showHide.style.rotate = "0deg";
                overlay.style.display = "none";
            }
        })


        overlay.addEventListener("click", () => {
            showHide.style.rotate = "0deg";
            overlay.style.display = "none";
            document.getElementById("sidebar").classList.remove("sideBar")
        })
    </script>

</body>

</html>