<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="include/sidebarCoor.css">
    <title>Document</title>
</head>

<body>
    <?php
    include "include/sidebarCoor.php";
    include "../include/database.php";
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
                    echo "<form action='envoie_note.php' method='post'>";
                    echo "<input type='hidden' name='nom_exam' value='{$nom_exam}'><input type='hidden' name='exam_id' value='{$id_exam}'> ";
                    echo "<button type='submit'>" . "evoyer au etudiants" . "</button>";
                    echo "</form>";
                    echo '<div id="success-message" style="display: none;">Notes sent successfully to students!</div>';
                } else {
                    echo "No results found for the specified exam.";
                }
            } catch (PDOException $e) {
                echo "Database error: " . $e->getMessage();
            }
        } else {
            echo "No exam selected or wrong method used.";
        }
        ?>
    </div>
    <script>
        $(document).ready(function() {
            $('form').submit(function(event) {
                event.preventDefault(); // Prevent form submission

                var formData = $(this).serialize(); // Serialize form data

                $.ajax({
                    type: 'POST',
                    url: 'envoie_note.php',
                    data: formData,
                    success: function(response) {
                        $('#success-message').show(); // Show success message
                        $('form')[0].reset(); // Reset form fields
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText); // Log any errors
                    }
                });
            });
        });
    </script>
</body>

</html>