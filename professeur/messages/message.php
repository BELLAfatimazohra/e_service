
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
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .bodyDiv {
            padding: 20px;
            max-width: 800px;
            margin: 40px auto;
            margin-top: 100px;

            border-radius: 8px;
            box-shadow: 0 0 10px #999999;
            text-align: center;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        button.no-button {
            display: block;
            width: 250px;
            margin: 10px auto;
            padding: 15px;
            background-color: #007bff;
            border: none;
            color: white;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
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
            margin: 0;
        }
    </style>
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
