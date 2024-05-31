<?php
session_start();
include "../include/sidebar_chef_dep.php";
include "../../include/database.php";

$id_chef_departement = $_SESSION['user_id'];


$sql = "SELECT id, Nom_filiere, annee FROM filiere WHERE id_chef_departement = ? ORDER BY Nom_filiere ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id_chef_departement]);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>

<head>
    <title>Gestion des Filières</title>
    <link rel="stylesheet" href="../include/sidbar_chef_dep.css">
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
            background-color: #5cb85c;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #4cae4c;
        }
    </style>
</head>

<body>
    <?php 
   
    ?>
    <div class="bodyDiv">
        <form action="consulter_module.php" method="get">
            <label for="filiere">Sélectionner une filière :</label>
            <select id="filiere" name="filiere">
                <?php
                foreach ($result as $row) {
                    $displayText = $row['Nom_filiere'] . ' - ' . $row['annee'];
                    echo '<option value="' . $row['id'] . '">' . $displayText . '</option>';
                }
                ?>
            </select>
            <input type="submit" value="consulter la liste des modules ">
        </form>
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