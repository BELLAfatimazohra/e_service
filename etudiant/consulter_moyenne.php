
<?php
session_start();
if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'etudiant') {

    require_once "../include/database.php";
    include_once "include/sidebarEtud.php";
} else {
    header("Location:/e_service/index.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>