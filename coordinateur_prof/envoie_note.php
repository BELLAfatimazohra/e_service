
    <?php
    include "../include/database.php";

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nom_exam']) && isset($_POST['exam_id'])) {
            $nom_exam = $_POST['nom_exam'];
            $id_exam = $_POST['exam_id'];
            try {
                $sql = "INSERT INTO note (id_etudiant, ide_exam, remarque, note_value)
            SELECT id_etudiant, :id_exam, remarque, note_value
            FROM {$nom_exam};
            ";
                $stmt = $pdo->prepare($sql);
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id_exam', $id_exam, PDO::PARAM_INT);
                $stmt->execute();
                echo "sucess";
            } catch (PDOException $e) {
                echo "Database error: " . $e->getMessage();
            }
        } else {
            echo "No exam selected or wrong method used.";
        }