<?php
session_start();
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'professeur') {
    header("Location: index.php");
    exit;
}

if (!isset($_GET['exam_id'])) {
    header("Location: erreur.php");
    exit;
}

try {
    require_once '../../include/database.php';
} catch (PDOException $e) {
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
    exit;
}

$id_prof_connecte = $_SESSION['user_id'];
$exam_id = $_GET['exam_id'];

try {
    $stmt_exam = $pdo->prepare("SELECT * FROM exam WHERE id = :exam_id AND id_prof = :id_prof");
    $stmt_exam->execute(['exam_id' => $exam_id, 'id_prof' => $id_prof_connecte]);
    $exam = $stmt_exam->fetch(PDO::FETCH_ASSOC);

    if (!$exam) {
        header("Location: erreur.php");
        exit;
    }
} catch (PDOException $e) {
    echo "Erreur lors de l'exécution de la requête : " . $e->getMessage();
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $type = $_POST['type'];
    $pourcentage = $_POST['pourcentage'];

    try {
        $stmt_update_exam = $pdo->prepare("UPDATE exam SET type = :type, pourcentage = :pourcentage WHERE id = :exam_id");
        $stmt_update_exam->execute(['type' => $type, 'pourcentage' => $pourcentage, 'exam_id' => $exam_id]);
        header("Location: exam.php?module_id=" . $exam['id_module']);
        exit;
    } catch (PDOException $e) {
        echo "Erreur lors de la mise à jour de l'examen : " . $e->getMessage();
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Examen</title>
    <link rel="stylesheet" href="../professeur/assets/modifier_exam.css">
    <link rel="stylesheet" href="assets/include/sidebarProf.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
    <?php
    include 'assets/include/sidebarProf.php';
    ?>
    <div class="bodyDiv">
        <div class="form-container">
            <h1>Modifier un Examen</h1>
            <form action="" method="post">
                <label for="type">Type:</label>
                <input type="text" id="type" name="type" class="inpuut" value="<?php echo $exam['type']; ?>" required><br><br>
                <label for="pourcentage">Pourcentage:</label>
                <input type="text" id="pourcentage" name="pourcentage" class="inpuut" value="<?php echo $exam['pourcentage']; ?>" required><br><br>
                <button type="submit">Modifier</button>
            </form>
        </div>
    </div>



</body>

</html>