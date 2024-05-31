<?php
session_start();
if (!isset($_SESSION['user_id']) ||  ($_SESSION['user_type'] !== 'professeur' &&  $_SESSION['user_type'] !== 'chef_departement' && $_SESSION['user_type'] !== 'coordinateur_prof')) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="assets/include/sidebarProf.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Confirmation</title>
    <style>
        .bodyDiv {
        text-align: center;
        margin-top: 50px;
    }
        .confirm {
            color: green;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .secon {
            font-size: 18px;
            color: #333;
        }

        .index {
            display: inline-block;
            background-color: #007bff;
            width: 150px;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            transition: background-color 0.3s ease;
            text-align: center;
            margin-left: 450px;
        }

        .index:hover {
            background-color: #0056b3;
        }
    </style>

</head>

<body>
    <?php
    include 'assets/include/sidebarProf.php';

    ?>
    <div class="bodyDiv">
        <p class="confirm">Votre mot de passe a été changé avec succès <i class="fas fa-check-circle"></i></p> <br>
        <p class="secon">Vous pouvez maintenant vous connecter avec votre nouveau mot de passe.</p>
        <a class="index" href="index.php">Se connecter</a>
    </div>

</body>

</html>