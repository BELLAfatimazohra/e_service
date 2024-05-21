<?php
session_start();
include "../../include/database.php";


$filiere_id = isset($_GET['filiere_id']) ? (int)$_GET['filiere_id'] : 0;

if ($filiere_id > 0) {

    $sql = "SELECT id, nom, prenom FROM professeur WHERE id_filiere = ? ORDER BY nom";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$filiere_id]);
    $professeurs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $professeurs = [];
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Sélectionner un Professeur à Supprimer</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .bodyDiv {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
        }

        label {
            display: block;
            text-align: left;
            margin: 10px 0 5px;
            font-weight: bold;
        }

        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #d9534f;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #c9302c;
        }
    </style>
</head>

<body>
    <div class="bodyDiv">
        <h1>Sélectionner un Professeur à Supprimer</h1>
        <form method="get" action="confirmer_suppression_professeur.php">
            <label for="prof_id">Sélectionner un professeur :</label>
            <select id="prof_id" name="prof_id" required>
                <?php foreach ($professeurs as $professeur) : ?>
                    <option value="<?php echo $professeur['id']; ?>">
                        <?php echo htmlspecialchars($professeur['prenom'] . ' ' . $professeur['nom']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <input type="hidden" name="filiere_id" value="<?php echo $filiere_id; ?>">
            <input type="submit" value="Sélectionner">
        </form>
    </div>
</body>

</html>