<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message</title>

    <link rel="stylesheet" href="../../professeur/assets/message.css">
    <link rel="stylesheet" href="../include/sidebarCoor.css">

    <style>

        .bodyDiv {
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
            margin-bottom: 20px;
        }

        button.no-button {
            display: block;
            width: 250px;
          margin-left: 250px;
           
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
    session_start();
    $_SESSION['user_type'] = 'coordinateur_prof';
    if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'coordinateur_prof') {
        include '../../include/database.php';
        include '../include/sidebarCoor.php';
    } else {

        header("Location: index.php");
        exit;
    }
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