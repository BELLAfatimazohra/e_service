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
    <link rel="stylesheet" href="../include/sidbar_chef_dep.css">
    <style>
        .bodyDiv {
            margin-left: 10px;
            color: black;
            max-width: 600px;
          margin-top: 150px;
          margin-left: 350px;
            padding: 20px;
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

        .bodyDiv label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
           padding-left: 75px;
            margin-left: 100px;
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
            margin-left: 60px;
        }

        .bodyDiv input[type="submit"] {
            background-color: #f44336;
            color: white;
            border: none;
            cursor: pointer;
        }

        .bodyDiv input[type="submit"]:hover {
            background-color: #e53935;
        }
    </style>
</head>

<body>
    <?php
    include "../include/sidebar_chef_dep.php";
    ?>
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
    
    <script>
        document.querySelectorAll("li").forEach(function(li) {
            li.classList.remove("active");
        });

        document.querySelector(".liProf").classList.add("active");
    </script>
</body>

</html>