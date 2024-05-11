<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message</title>

    <link rel="stylesheet" href="../professeur/assets/message.css">
    <link rel="stylesheet" href="include/sidebarCoor.css">
</head>

<body>
    <?php
    session_start();
    $_SESSION['user_type'] = 'coordinateur_prof';
    if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'coordinateur_prof') {
        include 'include/sidebarCoor.php';
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



</body>

</html>