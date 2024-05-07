<?php
session_start();
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'coordinateur_prof') {
    header("Location: index.php");
    exit;
}
if (!isset($_GET['module_id'])) {
    header("Location: erreur.php");
    exit;
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once '../include/database.php';
    $type = $_POST['type'];
    $pourcentage = $_POST['pourcentage'];
    $id_module = $_GET['module_id']; 
    $id_prof = $_SESSION['user_id'];
    $stmt = $pdo->prepare("INSERT INTO exam (type, pourcentage, id_module, id_prof) VALUES (:type, :pourcentage, :id_module, :id_prof)");
    $stmt->execute(['type' => $type, 'pourcentage' => $pourcentage, 'id_module' => $id_module, 'id_prof' => $id_prof]);
    header("Location: exam.php?module_id=" . $id_module);
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Examen</title>
    <link rel="stylesheet" href="../professeur/assets/ajouter_exam.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
</head>

<body>
    <?php
    include '../include/nav_cote_corr.php';
    ?>
    <script>
        var bodyDiv = document.querySelector('.bodyDiv');
        bodyDiv.innerHTML = `
        <div class="form-container">
        <h1 style="text-align: center; color: #333;">Ajouter un Examen</h1>
        <form action="" method="post">
            <input type="hidden" name="id_module" value="<?php echo $_GET['module_id']; ?>">
            <div class="form-group">
                <label for="type">Type:</label>
                <input type="text" id="type" name="type" required>
            </div>
            <div class="form-group">
                <label for="pourcentage">Pourcentage:</label>
                <input type="text" id="pourcentage" name="pourcentage" required>
            </div>
            <button class="submit-button" type="submit">Ajouter</button>
        </form>
    </div>
        `;
    </script>

</body>

</html>