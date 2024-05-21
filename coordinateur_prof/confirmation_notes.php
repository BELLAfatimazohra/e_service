<?php

session_start();

include "../include/database.php";
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'coordinateur_prof') {
    header("Location: index.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="include/sidebarCoor.css">
    <title>Document</title>
    <style>
        .bodyDiv {
            color: black;
        }


                #success-message {
                    color: green;
                    font-weight: bold;
                    text-align: center;
                    margin-top: 20px;
                }
                form{
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    width: 55%;
                    border: solid rgb(0, 0, 0,0.2) 1px;
                    border-radius: 5px;
                    background-color: rgb(2000,2000,2000);
                    margin-bottom: 10px;
                }
                .consulter {
                    font-size: large;
                    font-weight: 500;
                    padding: 2rem 2rem;
                    border-radius: 15px;
                    display: inline-block;
                    margin-right: 1rem;
                    text-transform: capitalize;
                }
                .btn{
                    margin: 1rem;
                }


              
    </style>
</head>

<body>
    <?php
    include 'include/sidebarCoor.php';
    $user_id = $_SESSION["user_id"];
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
                    echo "<div class='container'>";
                    echo "<form action='consulter_note.php' method='post'>";
                    echo "<input type='hidden' name='nom_exam' value='{$result["nom_exam"]}'><input type='hidden' name='exam_id' value='{$result["exam_id"]}'><div class='consulter'> " . "&#8226; " . htmlspecialchars(str_replace("_", " ", $result["nom_exam"])) . "</div>";
                    echo "<button class='btn' type='submit'>" . "Consulter" . "</button>";
                    echo "</form>";
                    echo "</div>";
                }
                echo "</div>";
            } else {
                echo "No data found.";
            }
        } catch (PDOException $e) {
            echo "Error retrieving data: " . $e->getMessage();
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