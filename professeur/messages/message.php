
<?php
session_start();
if (!isset($_SESSION['user_id']) ||  ($_SESSION['user_type'] !== 'professeur' && $_SESSION['user_type'] !== 'coordinateur_prof') ) {
    header("Location: index.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message</title>
    
    <link rel="stylesheet" href="../assets/message.css">
    <link rel="stylesheet" href="../assets/include/sidebarProf.css">
</head>
<body>
    <?php
        require_once '../../include/database.php';
        include_once '../assets/include/sidebarProf.php'; 
    ?>

    
        <div class="bodyDiv">
                    <h1>Page de messages</h1>
        <form action="envoyer_message.php" method="POST">
            <button class="no-button" type="submit">Envoyer un message</button>
        </form>
        <form action="liste_message.php" method="GET">
            <button class="no-button" type="submit">Consulter la liste des messages</button>
        </form>
        </div>
        <script>

        document.querySelectorAll("li").forEach(function(li) {
            if(li.classList.contains("active")){
                li.classList.remove("active");
            }
        });

        document.querySelector(".liMessage").classList.add("active");
        
    </script>
</body>
</html>
