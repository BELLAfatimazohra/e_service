<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="loginPage/style.css" />
    <script src="loginPage/script.js"></script>
    <title>ENSAH E-Services</title>
</head>

<body>
    <div class="curved"></div>
    <main>
        <div class="logoContainer">
            <img class="logo" src="public/images/logo-ensah.png" alt="ensah logo" />
        </div>
        <section class="loginSignup">
            <header class="loginTitle">
                <img class="grad-cap" src="public/images/grad-cap.svg" alt="" />e-Services
            </header>
            <form action="index.php" method="post">
                <div class="form-group">
                    <input type="text" id="mail" name="mail" required />
                    <label for="mail">E-mail</label>
                    <img src="public/images/mail.svg" alt="" class="email" />
                </div>
                <div class="form-group">
                    <input type="password" id="password" name="password" required />
                    <label for="password">Password</label>
                </div>
                <label for="showPwd" class="checkbox-label">
                    <input type="checkbox" id="showPwd" />
                    <img src="public/images/visibility.svg" alt="" class="pwd" />
                    <img src="public/images/visibilityOff.svg" alt="" class="pwdv" />
                </label>

                <input class="button" type="submit" name="login" value="Login" />
                <br /><br />
                <hr />
            </form>
            <a href="forget_pass.php">Forget Your Password?</a>
        </section>
    </main>
</body>

</html>

<?php

require_once 'include/database.php';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    
    $email = $_POST['mail'];
    $password = $_POST['password'];
    
    try {
        $stmt_etudiant = $pdo->prepare("SELECT * FROM etudiant WHERE Email = :email AND Password = :password");
        $stmt_etudiant->execute(['email' => $email, 'password' => $password]);
        $result_etudiant = $stmt_etudiant->fetch(PDO::FETCH_ASSOC);
        $stmt_professeur = $pdo->prepare("SELECT * FROM professeur WHERE Email = :email AND Password = :password");
        $stmt_professeur->execute(['email' => $email, 'password' => $password]);
        $result_professeur = $stmt_professeur->fetch(PDO::FETCH_ASSOC);
        // pour coordinateur 
        $stmt_coordinateur = $pdo->prepare("SELECT * FROM coordinateur WHERE Email = :email AND Password = :password");
        $stmt_coordinateur->execute(['email' => $email, 'password' => $password]);
        $result_coordinateur = $stmt_coordinateur->fetch(PDO::FETCH_ASSOC);
        // pour chef de filiere 
        $stmt_chef_departement = $pdo->prepare("SELECT * FROM chef_departement WHERE Email = :email AND Password = :password");
        $stmt_chef_departement->execute(['email' => $email, 'password' => $password]);
        $result_chef_departement = $stmt_chef_departement->fetch(PDO::FETCH_ASSOC);
        // Vérifie si une ligne a été retournée de la table etudiant ou professeur ou les autres .
        if ($result_etudiant) {
            session_start();
            $_SESSION['user_type'] = 'etudiant';
            $_SESSION['user_id'] = $result_etudiant['id'];
            header("Location:etudiant/index.php");
            exit;
        } elseif (($result_professeur) &&  ($result_coordinateur)){

            session_start();

            $_SESSION['user_type'] = 'professeur';
            $_SESSION['user_id'] = $result_professeur['id'];

            header("Location:coordinateur_prof/index.php");
            exit;
        } elseif ($result_professeur) {

            session_start();

            $_SESSION['user_type'] = 'professeur';
            $_SESSION['user_id'] = $result_professeur['id'];

            header("Location:professeur/index.php");
            exit;
        } elseif ($result_chef_departement) {

            session_start();

            $_SESSION['user_type'] = 'chef_departement';
            $_SESSION['user_id'] = $result_chef_departement['id'];

            header("Location:chef_departement/index.php");
            exit;
        } else {

            echo "Email ou mot de passe incorrect.";
        }
    }
    catch (PDOException $e) {
        echo "Erreur de connexion : " . $e->getMessage();
    }
}
?>