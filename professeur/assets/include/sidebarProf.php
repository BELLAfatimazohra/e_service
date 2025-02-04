<?php
session_start();

try {
    $pdo = new PDO('mysql:host=localhost;dbname=ensah_eservice', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
    exit();
}

try {
    if (isset($_SESSION['original_user_id'])) {
        $user_id = $_SESSION['original_user_id'];
    } else {
        $user_id = $_SESSION['user_id'];
    }
    $user_type = $_SESSION['user_type'];

    if ($user_type === "chef_departement") {
        // Fetch the email and password from chef_departement table
        $sql = "SELECT email, password FROM chef_departement WHERE id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $chefDepResult = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($chefDepResult) {
            $email = $chefDepResult['email'];
            $password = $chefDepResult['password'];

            // Fetch the corresponding user ID from professeur table
            $sql = "SELECT id FROM professeur WHERE email = :email AND password = :password";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':password', $password, PDO::PARAM_STR);
            $stmt->execute();
            $profResult = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($profResult) {
                if (!isset($_SESSION['original_user_id'])) {
                    $_SESSION['original_user_id'] = $user_id;
                }
                $_SESSION['user_id'] = $profResult['id'];
                $user_id = $_SESSION['user_id'];
            } else {
                echo "No matching professeur record found for the given chef_departement.";
                exit();
            }
        } else {
            echo "No chef_departement found with user ID $user_id";
            exit();
        }
    } elseif ($user_type === "coordinateur_prof") {
        // Fetch the email and password from coordinateur table
        $sql = "SELECT email, password FROM coordinateur WHERE id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $coordResult = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($coordResult) {
            $email = $coordResult['email'];
            $password = $coordResult['password'];

            // Fetch the corresponding user ID from professeur table
            $sql = "SELECT id FROM professeur WHERE email = :email AND password = :password";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':password', $password, PDO::PARAM_STR);
            $stmt->execute();
            $profResult = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($profResult) {
                if (!isset($_SESSION['original_user_id'])) {
                    $_SESSION['original_user_id'] = $user_id;
                }
                $_SESSION['user_id'] = $profResult['id'];
                $user_id = $_SESSION['user_id'];
            } else {
                echo "No matching professeur record found for the given coordinateur.";
                exit();
            }
        } else {
            echo "No coordinateur found with user ID $user_id";
            exit();
        }
    }

    // Fetch the full name from professeur table using the updated user_id
    $sql = "SELECT CONCAT(Nom, ' ', Prenom) AS full_name FROM professeur WHERE id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $nom = $result['full_name'];
    } else {
        echo "No user found with user ID $user_id";
        exit();
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}
echo $_SESSION['user_id'];
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
        <?php
        if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === "coordinateur_prof") {
        ?>
            <a href="javascript:void(0);" onclick="window.location.href='http://localhost/e_service/coordinateur_prof/index.php'">
                <button class="changer">Accéder à la zone Coord</button>
            </a>
        <?php
        }
        ?>
        <?php
        if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === "chef_departement") {
        ?>
            <a href="javascript:void(0);" onclick="window.location.href='http://localhost/e_service/chef_departement/index.php'">
                <button class="changer">Accéder à la zone Chef Dep</button>
            </a>
        <?php
        }
        ?>

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
                <a href="javascript:void(0);" onclick="window.location.href='http://localhost/e_service/professeur/profil.php'"><i class="fas fa-user"></i>
                    <div>Profil</div>
                </a>
                <a href="javascript:void(0);" onclick="window.location.href='http://localhost/e_service/se_deconnecter.php'"><i class="fas fa-sign-out-alt"></i>
                    <div>Se déconnecter </div>
                </a>
                <a href="javascript:void(0);" onclick="window.location.href='http://localhost/e_service/professeur/changer_mot_de_passe.php'"><i class="fas fa-lock"></i>
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
                <a href="javascript:void(0);" onclick="window.location.href='http://localhost/e_service/professeur/index.php'" aria-current="page">
                    <div class="group"><svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                            <path d="M240-200h120v-240h240v240h120v-360L480-740 240-560v360Zm-80 80v-480l320-240 320 240v480H520v-240h-80v240H160Zm320-350Z" />
                        </svg> <span>Acceuille</span> </div>
                    <div class="arrow-left"></div>
                </a>
            </li>
            <li class="liEmp">
                <a href="javascript:void(0);" onclick="window.location.href='http://localhost/e_service/professeur/emploi_temps/creation_aut_emploi.php'">
                    <div class="group">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                            <path d="M320-400q-17 0-28.5-11.5T280-440q0-17 11.5-28.5T320-480q17 0 28.5 11.5T360-440q0 17-11.5 28.5T320-400Zm160 0q-17 0-28.5-11.5T440-440q0-17 11.5-28.5T480-480q17 0 28.5 11.5T520-440q0 17-11.5 28.5T480-400Zm160 0q-17 0-28.5-11.5T600-440q0-17 11.5-28.5T640-480q17 0 28.5 11.5T680-440q0 17-11.5 28.5T640-400ZM200-80q-33 0-56.5-23.5T120-160v-560q0-33 23.5-56.5T200-800h40v-80h80v80h320v-80h80v80h40q33 0 56.5 23.5T840-720v560q0 33-23.5 56.5T760-80H200Zm0-80h560v-400H200v400Zm0-480h560v-80H200v80Zm0 0v-80 80Z" />
                        </svg> <span>Emploi du temps</span>
                    </div>
                    <div class="arrow-left"></div>
                </a>
            </li>
            <li class="liNote">
                <a href="javascript:void(0);" onclick="window.location.href=' http://localhost/e_service/professeur/filiere.php'">
                    <div class="group"><svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                            <path d="M360-120q-33 0-56.5-23.5T280-200v-400q0-33 23.5-56.5T360-680h400q33 0 56.5 23.5T840-600v400q0 33-23.5 56.5T760-120H360Zm0-400h400v-80H360v80Zm160 160h80v-80h-80v80Zm0 160h80v-80h-80v80ZM360-360h80v-80h-80v80Zm320 0h80v-80h-80v80ZM360-200h80v-80h-80v80Zm320 0h80v-80h-80v80Zm-480-80q-33 0-56.5-23.5T120-360v-400q0-33 23.5-56.5T200-840h400q33 0 56.5 23.5T680-760v40h-80v-40H200v400h40v80h-40Z" />
                        </svg> <span>Affichage</span> </div>
                    <div class="arrow-left"></div>
                </a>
            </li>
            <li class="liMessage">
                <a href="javascript:void(0);" onclick="window.location.href='http://localhost/e_service/professeur/messages/message.php'">
                    <div class="group"><svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                            <path d="M160-160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h640q33 0 56.5 23.5T880-720v480q0 33-23.5 56.5T800-160H160Zm320-280L160-640v400h640v-400L480-440Zm0-80 320-200H160l320 200ZM160-640v-80 480-400Z" />
                        </svg> <span>Messagerie</span> </div>
                    <div class="arrow-left"></div>
                </a>
            </li>
            <li class="liPersonnel">
                <a href="javascript:void(0);" onclick="window.location.href='http://localhost/e_service/professeur/personnel.php'">
                    <div class="group"><svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                            <path d="M440-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47Zm0-80q33 0 56.5-23.5T520-640q0-33-23.5-56.5T440-720q-33 0-56.5 23.5T360-640q0 33 23.5 56.5T440-560ZM884-20 756-148q-21 12-45 20t-51 8q-75 0-127.5-52.5T480-300q0-75 52.5-127.5T660-480q75 0 127.5 52.5T840-300q0 27-8 51t-20 45L940-76l-56 56ZM660-200q42 0 71-29t29-71q0-42-29-71t-71-29q-42 0-71 29t-29 71q0 42 29 71t71 29Zm-540 40v-111q0-34 17-63t47-44q51-26 115-44t142-18q-12 18-20.5 38.5T407-359q-60 5-107 20.5T221-306q-10 5-15.5 14.5T200-271v31h207q5 22 13.5 42t20.5 38H120Zm320-480Zm-33 400Z" />
                        </svg> <span>Personnel</span> </div>
                    <div class="arrow-left"></div>
                </a>
            </li>
        </ul>
    </nav>
    <div class="bodyDiv">

    </div>


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