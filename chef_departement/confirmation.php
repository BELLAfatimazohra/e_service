<?php
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'chef_departement') {
    header("Location: index.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../professeur/assets/confirmation.css">
    <link rel="stylesheet" href="include/sidebarCoor.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Confirmation</title>
</head>

<body>
    <?php
    include "include/sidebar_chef_dep.php";

    ?>


    <div class="bodyDiv">
        <p class="confirm">Votre mot de passe a été changé avec succès <i class="fas fa-check-circle"></i></p> <br>
        <p class="secon">Vous pouvez maintenant vous connecter avec votre nouveau mot de passe.</p>
        <a href="index.php">Se connecter</a>
    </div>

</body>

</html>