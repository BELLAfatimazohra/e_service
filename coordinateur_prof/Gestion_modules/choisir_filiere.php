<?php
session_start();
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'coordinateur_prof') {
    header("Location: index.php");
    exit;
}
$coordinateur_id = $_SESSION['user_id'];
include '../../include/database.php';
$stmt = $pdo->prepare("SELECT DISTINCT id,CONCAT(Nom_filiere, ' ', annee) AS Nom_filiere_annee FROM filiere WHERE id_coordinateur = :coordinateur_id");
$stmt->execute(['coordinateur_id' => $coordinateur_id]);
$filieres = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Choix de Filière</title>
    <link rel="stylesheet" href="../include/sidebarCoor.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .change {
            background-color: white;
            margin: 20px auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        form {
            display: flex;
            flex-direction: column;
            margin-left: 20px;
        }

        label {
            margin-bottom: 5px;
        }

        select {
            padding: 8px;
            margin-bottom: 10px;
            width: 220px;
        }

        button[type="submit"] {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }

        a {
            text-decoration: none;
            color: black;
        }
    </style>
</head>

<body>
    <?php include '../include/sidebarCoor.php'; ?>
    <div class="bodyDiv">
        <div class="change">
            <form action="affectation_module.php" method="get">
                <label for="filiere">Choisir une filière :</label>
                <select name="filiere" id="filiere">
                    <?php foreach ($filieres as $filiere) : ?>
                        <option value="<?php echo $filiere['id']; ?>"><?php echo htmlspecialchars($filiere['Nom_filiere_annee']); ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit">Affecter les modules</button>
            </form>
        </div>
    </div>
    <script>
        document.querySelectorAll("li").forEach(function(li) {
            if (li.classList.contains("active")) {
                li.classList.remove("active");
            }
        });

        document.querySelector(".liModules").classList.add("active");
    </script>
</body>

</html>