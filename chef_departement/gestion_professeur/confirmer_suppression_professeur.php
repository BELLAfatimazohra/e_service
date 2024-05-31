<?php
session_start();

include "../../include/database.php";
$prof_id = isset($_GET['prof_id']) ? (int)$_GET['prof_id'] : 0;
$filiere_id = isset($_GET['filiere_id']) ? (int)$_GET['filiere_id'] : 0;
if ($prof_id > 0) {
    $sql = "SELECT nom, prenom FROM professeur WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$prof_id]);
    $professeur = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    $professeur = null;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['confirm']) && $_POST['confirm'] === 'Oui') {
        $sql = "DELETE FROM professeur WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$prof_id]);
        header("Location: consulter_professeurs.php?filiere=$filiere_id");
        exit;
    } else {
        header("Location: consulter_professeurs.php?filiere=$filiere_id");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../include/sidbar_chef_dep.css">
    <title>Confirmer la Suppression du Professeur</title>
    <style>
        .bodyDiv {
            margin-top: 200px;
            margin-left: 350px;
            color: black;
            max-width: 600px;
          
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .bodyDiv h1 {
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
            color: #333;
        }

        .bodyDiv p {
            font-size: 18px;
            margin-bottom: 20px;
            color: #555;
            text-align: center;
        }

        .bodyDiv form {
            display: flex;
            justify-content: center;
            gap: 10px;
        }
       .bodyDiv .bt1{
        padding: 10px 20px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-left: 230px;
       }
        .bodyDiv button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
          
            margin-right: 0px;
        }

        .bodyDiv button:hover {
            background-color: #45a049;
        }

        .bodyDiv a {
            display: block;
            margin-top: 20px;
            text-align: center;
            color: #0066cc;
            text-decoration: none;
        }

        .bodyDiv a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <?php
    include "../include/sidebar_chef_dep.php";
    ?>
    <div class="bodyDiv">
        <h1>Confirmer la Suppression du Professeur</h1>
        <?php if ($professeur) : ?>
            <p>Êtes-vous sûr de vouloir supprimer le professeur <?php echo htmlspecialchars($professeur['prenom'] . ' ' . $professeur['nom']); ?> ?</p>
            <form method="post">
                <button class="bt1" type="submit" name="confirm" value="Oui">Oui</button>
                <button type="submit" name="confirm" value="Non">Non</button>
            </form>
        <?php else : ?>
            <p>Professeur introuvable.</p>
            <a href="consulter_professeurs.php?filiere=<?php echo $filiere_id; ?>">Retour à la liste des professeurs</a>
        <?php endif; ?>
    </div>


    <script>
        document.querySelectorAll("li").forEach(function(li) {
            li.classList.remove("active");
        });

        document.querySelector(".liProf").classList.add("active");
    </script>
</body>

</html>