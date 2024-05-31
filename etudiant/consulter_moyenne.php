<?php
session_start();
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'etudiant') {
    header("Location:/e_service/index.php");
    exit;
}

require_once "../include/database.php";
include_once "include/sidebarEtud.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="include/sidebarEtud.css">
    <title>Student Grades</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
            font-size: 16px;
        }
        th {
            background-color: #3d44d1;
            color: white;
        }
        tr:nth-child(even):not(.failed) {
            background-color: #ffffff;
        }
        tr:hover {
            background-color: #ddd;
        }
        tr.failed {
            background-color: #ffcccc !important; /* Light red background for failed modules */
        }
    </style>
</head>
<body>
<div class="bodyDiv">
    <?php
    try {
        // Fetch all required exam types per module
        $sql = "SELECT id_module, type FROM exam GROUP BY id_module, type";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $module_exam_types = $stmt->fetchAll(PDO::FETCH_GROUP|PDO::FETCH_ASSOC);

        // Fetch grades for the student
        $sql = "SELECT note.*, exam.type, exam.id_module, module.Nom_module 
                FROM note 
                JOIN exam ON note.ide_exam = exam.id 
                JOIN module ON exam.id_module = module.id
                WHERE note.id_etudiant = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->execute();
        $student_grades = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Organize student grades by module and type
        $student_grades_by_module = [];
        foreach ($student_grades as $grade) {
            $student_grades_by_module[$grade['id_module']][$grade['type']] = $grade;
        }

        // Determine if all exam types are completed per module and calculate average
        $averages = [];
        foreach ($module_exam_types as $moduleId => $examTypes) {
            if (isset($student_grades_by_module[$moduleId]) && 
                count($student_grades_by_module[$moduleId]) === count($examTypes)) {
                // Calculate average
                $sum = 0;
                foreach ($student_grades_by_module[$moduleId] as $typeGrades) {
                    $sum += $typeGrades['note_value'];
                }
                $averages[$moduleId] = [
                    'average' => $sum / count($examTypes),
                    'module_name' => $student_grades_by_module[$moduleId][array_key_first($student_grades_by_module[$moduleId])]['Nom_module']
                ];
            }
        }

        // Display results
        echo "<h1>Moyennes Disponibles</h1>";
        echo "<table>";
        echo "<tr><th>Module</th><th>Moyenne</th><th>Status</th></tr>";
        foreach ($averages as $moduleId => $averageData) {
            $rowClass = ($averageData['average'] < 12) ? 'failed' : '';
            $status = ($averageData['average'] < 12) ? 'Rattrapage' : 'Module Validé';
            echo "<tr class='{$rowClass}'>";
            echo "<td>" . htmlspecialchars($averageData['module_name']) . "</td>";
            echo "<td>" . htmlspecialchars(number_format($averageData['average'], 2)) . "</td>";
            echo "<td>" . htmlspecialchars($status) . "</td>";
            echo "</tr>";
        }
        echo "</table>";

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
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
<footer>
    E-SERVICES © Copyright 2020 - Dévelopée par AMMARA ABDERRAHMANE & BELLA FATIMA ZOHRA
</footer>
</body>
</html>
