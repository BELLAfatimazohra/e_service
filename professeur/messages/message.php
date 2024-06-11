
<?php
session_start();
if (!isset($_SESSION['user_id']) ||  ($_SESSION['user_type'] !== 'professeur' && $_SESSION['user_type'] !== 'chef_departement' &&  $_SESSION['user_type'] !== 'coordinateur_prof') ) {
    header("Location: index.php");
    exit;
}

?>
    <?php
        require_once '../../include/database.php';
        include_once '../assets/include/sidebarProf.php'; 
    ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message</title>
    
    <link rel="stylesheet" href="../assets/message.css">
    <link rel="stylesheet" href="../assets/include/sidebarProf.css">
    <style>


        .Div {
            padding: 20px;
            max-width: 800px;
            margin: 40px auto;
            margin-top: 100px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px #999999;
            text-align: center;
        }

        h1 {
            text-align: center;
            color: #333;
            margin: 20px;
        }

        button.no-button {
            display: block;
            width: 250px;
            margin: 20px auto;
            padding: 15px;
            background-color: var(--nav-bg);
            color: white;
            font-size: 16px;
            border: 1px solid var(--nav-bg);
            cursor: pointer;
            border-radius: 15px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        button.no-button:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        button.no-button:active {
            background-color: #003f7f;
            transform: scale(1);
        }

        form {
            margin: 30px;
        }
    </style>
</head>
<body>


    
        <div class="Div">
                    <h1>Page de messages</h1>
        <form action="envoyer_message.php" method="POST">
            <button class="no-button" type="submit">Envoyer Message</button>
        </form>
        <form action="liste_message.php" method="GET">
            <button class="no-button" type="submit">Consulter Messages</button>
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
