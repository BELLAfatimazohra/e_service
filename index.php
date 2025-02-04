<?php
session_start();
if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'coordinateur_prof') {

    header("Location:coordinateur_prof/index.php");
}
if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'professeur') {

    header("Location:professeur/index.php");
}
if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'etudiant') {

    header("Location:etudiant/index.php");
}
if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'chef_departement') {

    header("Location:chef_departement/index.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="images/loginPage/style.css" />
    <title>ENSAH E-Services</title>
    <style>
        .error-message {
            color: red;
            font-weight: bold;
            margin-top: 10px;
        }

        .forgot-password {
            margin-top: 20px;
            color: #333;
            text-decoration: none;
        }
    </style>
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
            <form action="" method="post">
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
            <a class="forgot-password" href="forget_pass.php">Mot de passe oublié ?</a>
        </section>
    </main>
    <script>
        document.getElementById('showPwd').addEventListener('change', function() {
            const passwordField = document.getElementById('password');
            const pwdIcon = document.querySelector('.pwd');
            const pwdvIcon = document.querySelector('.pwdv');
            if (this.checked) {
                passwordField.type = 'text';
                pwdIcon.style.display = 'none';
                pwdvIcon.style.display = 'inline';
            } else {
                passwordField.type = 'password';
                pwdIcon.style.display = 'inline';
                pwdvIcon.style.display = 'none';
            }
        });
    </script>
</body>

</html>

<?php

session_start();

if (isset($_SESSION['user_type'])) {
    switch ($_SESSION['user_type']) {
        case 'etudiant':
            header("Location:etudiant/index.php");
            exit;
        case 'coordinateur_prof':
            header("Location:coordinateur_prof/index.php");
            exit;
        case 'professeur':
            header("Location:professeur/index.php");
            exit;
        case 'chef_departement':
            header("Location:chef_departement/index.php");
            exit;
    }
}

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

        $stmt_coordinateur = $pdo->prepare("SELECT * FROM coordinateur WHERE Email = :email AND Password = :password");
        $stmt_coordinateur->execute(['email' => $email, 'password' => $password]);
        $result_coordinateur = $stmt_coordinateur->fetch(PDO::FETCH_ASSOC);

        $stmt_chef_departement = $pdo->prepare("SELECT * FROM chef_departement WHERE Email = :email AND Password = :password");
        $stmt_chef_departement->execute(['email' => $email, 'password' => $password]);
        $result_chef_departement = $stmt_chef_departement->fetch(PDO::FETCH_ASSOC);

        if ($result_etudiant) {
            $_SESSION['user_type'] = 'etudiant';
            $_SESSION['user_id'] = $result_etudiant['id'];
            header("Location:etudiant/index.php");
            exit;
        } elseif ($result_professeur && $result_coordinateur) {
            $_SESSION['email'] = $result_professeur['Email'];
            $_SESSION['password'] = $result_professeur['Password'];
            $_SESSION['user_type'] = 'coordinateur_prof';
            $_SESSION['user_id'] = $result_professeur['id'];
            header("Location:coordinateur_prof/index.php");
            exit;
        } elseif ($result_professeur && $result_chef_departement) {

            session_start();
            $_SESSION['email'] = $result_chef_departement['Email'];
            $_SESSION['password'] = $result_chef_departement['Password'];
            $_SESSION['user_type'] = 'chef_departement';
            $_SESSION['user_id'] = $result_chef_departement['id'];
            header("Location:chef_departement/index.php");
            exit;
        } elseif ($result_professeur) {

            session_start();

            $_SESSION['user_type'] = 'professeur';
            $_SESSION['user_id'] = $result_professeur['id'];

            header("Location:professeur/index.php");
            exit;
        } else {
            echo '<div class="error-message">Email ou mot de passe incorrect.</div>';
        }
    } catch (PDOException $e) {
        echo "Erreur de connexion : " . $e->getMessage();
    }
}
?>