<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="include/sidebarCoor.css">
    <title>Document</title>
    <style>.bodyDiv{color: black;}</style>
</head>

<body>
    <?php
    session_start();
    include 'include/sidebarCoor.php';
    include "../include/database.php";
    $user_id = $_SESSION["user_id"];
    echo $user_id;
    $sql = "SELECT id_filiere FROM coordinateur WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $user_id, PDO::PARAM_INT); 
    $stmt->execute();
    $result_filiere = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    ?>
    <div class="bodyDiv"> 
        <?php
        try {
            $sql = "SELECT np.*
            FROM notes_provisoire np
            JOIN exam e ON np.exam_id = e.id
            JOIN module m ON e.id_module = m.id
            WHERE m.id_filiere = :filiere_id;
            ";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':filiere_id', $user_id, PDO::PARAM_INT); 
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo "<h1>Notes en attente</h1>";

            if ($results) {
                echo "<div>";
                foreach ($results as $result) {
                    // Each button is inside its own form
                    echo "<form action='consulter_note.php' method='post'>";
                    echo "<input type='hidden' name='nom_exam' value='{$result["nom_exam"]}'><input type='hidden' name='exam_id' value='{$result["exam_id"]}'> <div class='consulter'> ". htmlspecialchars(str_replace("_", " ", $result["nom_exam"])). "</div>"  ;
                    echo "<button type='submit'>" ."consulter" ."</button>";
                    echo "</form>";
                }
                echo "</div>";
            } else {
                echo "No data found in notes_provisoire.";
            }
        } catch (PDOException $e) {
            echo "Error retrieving data: " . $e->getMessage();
        }
        ?>
    </div>
</body>
</html>
