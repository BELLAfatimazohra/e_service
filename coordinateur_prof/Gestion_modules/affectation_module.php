<?php
session_start();

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'coordinateur_prof') {
    header("Location: index.php");
    exit;
}

$coordinateur_id = $_SESSION['user_id'];

include '../../include/database.php';
$stmt = $pdo->prepare("SELECT DISTINCT id, CONCAT(Nom_filiere, ' ', annee) AS Nom_filiere_annee FROM filiere WHERE id_coordinateur = :coordinateur_id");
$stmt->execute(['coordinateur_id' => $coordinateur_id]);
$filieres = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id_filiere = $_GET['filiere'];
    $stmt_modules = $pdo->prepare("SELECT id, Nom_module FROM module WHERE id_filiere = :id_filiere");
    $stmt_modules->execute(['id_filiere' => $id_filiere]);
    $modules = $stmt_modules->fetchAll(PDO::FETCH_ASSOC);
    $stmt_professeurs = $pdo->prepare("SELECT id, Nom ,Prenom FROM professeur WHERE id_filiere =  $id_filiere ");
    $stmt_professeurs->execute();
    $professeurs = $stmt_professeurs->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Affectation des modules aux professeurs</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 5px;
        }

        select {
            padding: 8px;
            margin-bottom: 10px;
        }

        button {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Affectation des modules aux professeurs</h2>


        <form action="affectation_module.php" method="post">
            <?php foreach ($modules as $module) : ?>
                <label for="prof_<?php echo $module['id']; ?>">Choisir un professeur pour <?php echo $module['Nom_module']; ?>:</label>
                <select name="prof_<?php echo $module['id']; ?>" id="prof_<?php echo $module['id']; ?>">
                    <option value="">Sélectionner un professeur</option>
                    <?php foreach ($professeurs as $professeur) : ?>
                        <option value="<?php echo $professeur['id']; ?>">
                            <?php echo $professeur['Nom'] . ' ' . $professeur['Prenom']; ?>
                        </option>
                    <?php endforeach; ?>

                </select>
            <?php endforeach; ?>
            <button type="submit">Enregistrer l'affectation</button>
        </form>

    </div>
</body>

</html>