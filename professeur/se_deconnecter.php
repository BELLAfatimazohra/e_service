<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../loginPage/style.css" />
    <script src="loginPage/script.js"></script>
    <title>ENSAH E-Services</title>
</head>

<body>
    <div class="curved"></div>
    <main>
        <div class="logoContainer">
            <img class="logo" src="../public/images/logo-ensah.png" alt="ensah logo" />
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
            <p class="forgot">Forget Your Password?</p>
        </section>
    </main>
</body>

</html>

<?php

require_once '../include/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {

    $email = $_POST['mail'];
    $password = $_POST['password'];

    try {

        $pdo = new PDO('mysql:host=localhost;dbname=ensah_eservice', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        $stmt_etudiant = $pdo->prepare("SELECT * FROM etudiant WHERE Email = :email AND Password = :password");
        $stmt_etudiant->execute(['email' => $email, 'password' => $password]);
        $result_etudiant = $stmt_etudiant->fetch(PDO::FETCH_ASSOC);

        $stmt_professeur = $pdo->prepare("SELECT * FROM professeur WHERE Email = :email AND Password = :password");
        $stmt_professeur->execute(['email' => $email, 'password' => $password]);
        $result_professeur = $stmt_professeur->fetch(PDO::FETCH_ASSOC);

        if ($result_etudiant) {

            session_start();

            $_SESSION['user_type'] = 'etudiant';
            $_SESSION['user_id'] = $result_etudiant['id'];

            header("Location:etudiant/index.php");
            exit;
        } elseif ($result_professeur) {

            session_start();

            $_SESSION['user_type'] = 'professeur';
            $_SESSION['user_id'] = $result_professeur['id'];

            header("Location:professeur/index.php");
            exit;
        } else {

            echo "Email ou mot de passe incorrect.";
        }
    } catch (PDOException $e) {

        echo "Erreur de connexion : " . $e->getMessage();
    }
}
?>