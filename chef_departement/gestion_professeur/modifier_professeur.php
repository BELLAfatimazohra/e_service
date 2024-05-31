<?php
session_start();

include "../../include/database.php";


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id_chef_departement = $_SESSION['user_id'];

try {

    $sql_professeurs = "SELECT id, nom, prenom FROM professeur WHERE id_departement = ?";
    $stmt_professeurs = $pdo->prepare($sql_professeurs);
    $stmt_professeurs->execute([$id_chef_departement]);
    $professeurs = $stmt_professeurs->fetchAll(PDO::FETCH_ASSOC);

    $sql_filieres = "SELECT id, Nom_filiere ,annee FROM filiere WHERE id_chef_departement = ?ORDER BY Nom_filiere ASC";
    $stmt_filieres = $pdo->prepare($sql_filieres);
    $stmt_filieres->execute([$id_chef_departement]);
    $filieres = $stmt_filieres->fetchAll(PDO::FETCH_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['professeur_id'])) {
        $professeur_id = $_POST['professeur_id'];
        $nouvelle_filiere_id = $_POST['filiere_id'];

        $sql_update = "UPDATE professeur SET id_filiere = ? WHERE id = ?";
        $stmt_update = $pdo->prepare($sql_update);
        $stmt_update->execute([$nouvelle_filiere_id, $professeur_id]);

        header("Location: consulter_professeurs.php?filiere=$nouvelle_filiere_id");
        exit;
    }
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Modifier Professeur</title>
    <link rel="stylesheet" href="../include/sidbar_chef_dep.css">
    <style>
        .bodyDiv {
            margin-top: 150px;
            margin-left: 10px;
            color: black;
            max-width: 600px;
            margin-left: 350px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .bodyDiv h1 {
            font-size: 24px;
            margin-bottom: 20px;

            color: #333;

        }

        .bodyDiv label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;

            margin-left: 200px;
        }

        .bodyDiv select,
        .bodyDiv input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
            margin-left: 100px;
        }

        .bodyDiv input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        .bodyDiv input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <?php
    include "../include/sidebar_chef_dep.php";
    ?>
    <div class="bodyDiv">
        <h1>Modifier Professeur</h1>
        <form method="post">
            <label for="professeur">Sélectionner un professeur :</label>
            <select id="professeur" name="professeur_id" required>
                <option value="">Choisissez un professeur</option>
                <?php if (!empty($professeurs)) : ?>
                    <?php foreach ($professeurs as $prof) : ?>
                        <option value="<?= $prof['id'] ?>"><?= htmlspecialchars($prof['nom'] . ' ' . $prof['prenom']) ?></option>
                    <?php endforeach; ?>
                <?php else : ?>
                    <option value="">Aucun professeur trouvé</option>
                <?php endif; ?>
            </select>
            <br>

            <label for="filiere">Sélectionner une filière :</label>
            <select id="filiere" name="filiere_id" required>
                <option value="">Choisissez une filière</option>
                <?php if (!empty($filieres)) : ?>
                    <?php foreach ($filieres as $filiere) : ?>
                        <option value="<?= $filiere['id'] ?>"><?= htmlspecialchars($filiere['Nom_filiere'] . "_" . $filiere['annee']) ?></option>
                    <?php endforeach; ?>
                <?php else : ?>
                    <option value="">Aucune filière trouvée</option>
                <?php endif; ?>
            </select>
            <br>

            <input type="submit" value="Modifier">
        </form>
    </div>
    
    <script>
        document.querySelectorAll("li").forEach(function(li) {
            li.classList.remove("active");
        });

        document.querySelector(".liProf").classList.add("active");
    </script>
</body>

</html>