<?php
session_start();
if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'etudiant') {

    require_once "../include/database.php";
    include_once "include/sidebarEtud.php";
} else {
    header("Location:/e_service/index.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="include/sidebarEtud.css">
    <title>Document</title>
    <style>
table {
    width: 100%; /* Largeur du tableau */
    border-collapse: collapse; /* Les bordures des cellules sont fusionnées */
    margin-top: 20px; /* Marge au-dessus du tableau */
}
th, td {
    border: 1px solid #ccc; /* Bordure des cellules */
    padding: 8px; /* Espacement intérieur */
    text-align: left; /* Alignement du texte */
    font-size: 16px; /* Taille de la police */
}
th {
    background-color: #3d44d1; /* Couleur de fond des en-têtes */
    color: white; /* Couleur du texte des en-têtes */
}
tr:nth-child(even) {
    background-color: #ffffff; /* Couleur de fond pour les lignes paires */
}
tr:hover {
    background-color: #ddd; /* Couleur de fond au survol des lignes */
}
    </style>
</head>

<body>
    <div class="bodyDiv">


        <?php


        try {
            // First query to get notes for the student
            $sql = "SELECT * FROM note WHERE id_etudiant = :user_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt->execute();
            $results_notes = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Array to hold the enriched results
            $enriched_results = [];

            // Loop through each note and fetch additional information
            foreach ($results_notes as $note) {
                // Fetch exam details
                $sql_exam = "SELECT type, id_module, pourcentage FROM exam WHERE id = :ide_exam";
                $stmt_exam = $pdo->prepare($sql_exam);
                $stmt_exam->bindParam(':ide_exam', $note['ide_exam'], PDO::PARAM_INT);
                $stmt_exam->execute();
                $exam_details = $stmt_exam->fetch(PDO::FETCH_ASSOC);

                if ($exam_details) {
                    // Fetch module name using id_module from exam_details
                    $sql_module = "SELECT Nom_module FROM module WHERE id = :id_module";
                    $stmt_module = $pdo->prepare($sql_module);
                    $stmt_module->bindParam(':id_module', $exam_details['id_module'], PDO::PARAM_INT);
                    $stmt_module->execute();
                    $module_details = $stmt_module->fetch(PDO::FETCH_ASSOC);

                    if ($module_details) {
                        // Combine all the details into one array
                        $enriched_result = array_merge($note, $exam_details, $module_details);
                        $enriched_results[] = $enriched_result;
                    } else {
                        // Handle case where module details are not found
                        $enriched_result = array_merge($note, $exam_details);
                        $enriched_results[] = $enriched_result;
                    }
                }
            }

            // Display or process $enriched_results as needed
            echo "<pre>";
            echo "</pre>";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }


        if ($enriched_results) {
            echo "<h1>Affichages Disponibles</h1>";
            echo "<table border='1'>";
            echo "<tr>";
            echo "<th>Module</th><th>Type</th><th>Pourcentage</th><th>Score</th><th>Remarks</th>";
            echo "</tr>";

            foreach ($enriched_results as $row) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['Nom_module']) . "</td>";
                echo "<td>" . htmlspecialchars($row['type']) . "</td>";
                echo "<td>" . htmlspecialchars($row['pourcentage']) . '%' . "</td>";
                echo "<td>" . htmlspecialchars($row['note_value']) . "</td>";
                echo "<td>" . htmlspecialchars($row['remarque']) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "No results found for the specified exam.";
        }

        ?>
    </div>
    
    <script>

document.querySelectorAll("li").forEach(function(li) {
    if(li.classList.contains("active")){
        li.classList.remove("active");
    }
});

document.querySelector(".liNote").classList.add("active");

</script>
</body>

</html>