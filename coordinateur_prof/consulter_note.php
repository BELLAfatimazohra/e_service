<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'coordinateur_prof') {
    header("Location: index.php");
    exit;
}

include "../include/database.php";




?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="include/sidebarCoor.css">
    <title>Document</title>
    <style>
        h6,
        h1 {
            color: black;
            text-transform: capitalize;
        }

        table {
            width: 100%;
            /* Largeur du tableau */
            border-collapse: collapse;
            /* Les bordures des cellules sont fusionnées */
            margin-top: 20px;
            /* Marge au-dessus du tableau */
        }

        th,
        td {
            border: 1px solid #ccc;
            /* Bordure des cellules */
            padding: 8px;
            /* Espacement intérieur */
            text-align: left;
            /* Alignement du texte */
            font-size: 16px;
            /* Taille de la police */
        }

        th {
            background-color: #3d44d1;
            /* Couleur de fond des en-têtes */
            color: white;
            /* Couleur du texte des en-têtes */
        }

        tr:nth-child(even) {
            background-color: #ffffff;
            /* Couleur de fond pour les lignes paires */
        }

        tr:hover {
            background-color: #ddd;
            /* Couleur de fond au survol des lignes */
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>

<body>
    <?php
    include "include/sidebarCoor.php";
    function getStudentNameById($pdo, $student_id)
    {
        try {
            $sql = "SELECT Prenom, Nom FROM etudiant WHERE id = :student_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
            $stmt->execute();
            $student = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($student) {
                return array(
                    'Prenom' => $student['Prenom'],
                    'Nom' => $student['Nom']
                );
            } else {
                return array('error' => "Student not found");
            }
        } catch (PDOException $e) {
            return array('error' => "Error: " . $e->getMessage());
        }
    }

    ?>
    <div class="bodyDiv">


        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nom_exam']) && isset($_POST['exam_id'])) {
            $nom_exam = $_POST['nom_exam'];
            $id_exam = $_POST['exam_id'];
            try {
                $sql = "SELECT * FROM `" . $nom_exam . "`";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if ($results) {
                    echo "<h1>Details for " . htmlspecialchars(str_replace("_", " ", $nom_exam)) . "</h1>";
                    echo "<table border='1'>"; // Basic table, consider adding CSS for styling
                    echo "<tr>";
                    echo "<th>ID</th><th>last name</th><th>first name</th><th>Score</th><th>Remarks</th>";
                    echo "</tr>";

                    foreach ($results as $row) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['id_etudiant']) . "</td>";
                        echo "<td>" . getStudentNameById($pdo, $row['id_etudiant'])['Nom'] . "</td>";
                        echo "<td>" . getStudentNameById($pdo, $row['id_etudiant'])['Prenom'] . "</td>";
                        echo "<td>" . htmlspecialchars($row['note_value']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['remarque']) . "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                    echo "<form id='noteForm' method='post'>";
                    echo "<input type='hidden' name='nom_exam' value='{$nom_exam}'><input type='hidden' name='exam_id' value='{$id_exam}'><input type='hidden' name='envoie' value='envoyer'>";
                    echo "<button id='env' class='btn' type='submit'>" . "evoyer au etudiants" . "</button>";
                    echo "</form>";
                    echo '<div id="success-message" style="display: none;">Notes sent successfully to students!</div>';
                    echo '<a style="display: none;" class="btn" href="/e_service/coordinateur_prof/confirmation_notes.php" id="return-button">Retour</a>
                ';



                echo '<button class="btn" id="reclamerAnomalieButton">Reclamer anomalie</button>';

                echo '<div id="anomalieForm" style="display: none;">';
                echo '    <form method="post" id="anomalieFormSubmit" >';
                echo '        <input type="hidden" name="nom_exam" value="' . (isset($_POST['nom_exam']) ? $_POST['nom_exam'] : '') . '">';
                echo '        <input type="hidden" name="exam_id" value="' . (isset($_POST['exam_id']) ? $_POST['exam_id'] : '') . '">';
                echo '        <textarea name="anomalie_text" rows="4" cols="50"></textarea><br>';
                echo '        <button class="btn" type="submit">Send</button>';
                echo '    </form>';
                echo '</div>';
                echo'<div id="successMessage" style="display: none;">Anomalie reported successfully. Email sent to professor.</div>
';
                


                    
                } else {
                    echo "No results found for the specified exam.";
                }
            } catch (PDOException $e) {
                echo "Database error: " . $e->getMessage();
            }
        }
        ?>
                        <script>
    // Show the form when the button is clicked
    document.getElementById("reclamerAnomalieButton").addEventListener("click", function() {
        document.getElementById("anomalieForm").style.display = "block";
    });

    // AJAX form submission
    $("#anomalieFormSubmit").on("submit", function(event) {
        event.preventDefault(); // Prevent the default form submission

        var formData = $(this).serialize(); // Serialize the form data

        $.ajax({
            type: "POST",
            url: "reclamer.php",
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    $("#anomalieForm").hide(); // Hide the form
                    $("#successMessage").show(); // Show success message
                    $(".btn").hide();
                    $("#return-button").show();
                    $("table").hide();
                } else {
                    $("#successMessage").hide(); // Hide success message
                }
            },
            error: function() {
                $("#errorMessage").text("An error occurred while reporting the anomaly. Please try again later.").show(); // Show error message
                $("#successMessage").hide(); // Hide success message
            }
        });
    });
</script>
<script>
$(document).ready(function() {
    $('#noteForm').on('submit', function(event) {
        event.preventDefault(); // Prevent default form submission

        $.ajax({
            url: 'envoie_note.php', // Your PHP script to handle the form submission
            type: 'POST',
            data: $(this).serialize(), // Serialize the form data
            success: function(response) {
                // Handle the response from the server
                $("#success-message").show();
                    $("#return-button").show();
                    $("#reclamerAnomalieButton").hide();
                    
                    $("#env").hide();
                $('#noteForm')[0].reset(); // Reset the form
            },
            error: function(xhr, status, error) {
                // Handle errors
                alert('Error: ' + xhr.responseText);
            }
        });
    });
});
</script>
        <?php

        ?>
    </div>
    <script>
        document.querySelectorAll("li").forEach(function(li) {
            if (li.classList.contains("active")) {
                li.classList.remove("active");
            }
        });

        document.querySelector(".liNote").classList.add("active");
    </script>
</body>

</html>