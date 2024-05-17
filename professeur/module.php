<?php
session_start();
if (!isset($_SESSION['user_id']) ||  ($_SESSION['user_type'] !== 'professeur' && $_SESSION['user_type'] !== 'coordinateur_prof') ) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['filiere_id'])) {
    header("Location: filiere.php");
    exit;
}


$filiereId = $_GET['filiere_id'];


require_once "../include/database.php";

try {

    $stmt_modules = $pdo->prepare("SELECT id, Nom_module FROM module WHERE id_filiere = :filiere_id");
    $stmt_modules->execute(['filiere_id' => $filiereId]);
    $modules = $stmt_modules->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erreur lors de l'exécution de la requête : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../professeur/assets/index.css">
    <link rel="stylesheet" href="../professeur/assets/module.css">
    <link rel="stylesheet" href="assets/include/sidebarProf.css">
    <title>Acceuil</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-xgWvbC/GtpG27dbUMf057Ok6ZgoyNnuToSCzjUEuFQlyDhVdRflh5JL4tsbvtRL8yK1z2CqS3hINQjyGv7wXVg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>

<body>


    <?php
    include './assets/include/sidebarProf.php';
    ?>
    <div class="bodyDiv">
        <h2>Modules de la filière</h2>
        <?php foreach ($modules as $module) : ?>
            <form action="../professeur/exam.php" method="GET">
                <!-- Inclure l'identifiant du module dans l'URL -->
                <input type="hidden" name="module_id" value="<?php echo $module['id']; ?>">
                <button type="submit" class="module-button"><?php echo $module['Nom_module']; ?></button>
            </form>
        <?php endforeach; ?>
    </div>
    </div>





    </div>
    </div>

</body>

</html>