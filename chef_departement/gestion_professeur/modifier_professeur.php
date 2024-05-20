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
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin-top: 30px;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;

            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-top: -300px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
        }

        select,
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #5cb85c;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #4cae4c;
        }
    </style>
</head>

<body>
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
</body>

</html>