<?php
session_start();
if (!isset($_SESSION['user_id']) ||  ($_SESSION['user_type'] !== 'professeur' && $_SESSION['user_type'] !== 'chef_departement' &&  $_SESSION['user_type'] !== 'coordinateur_prof')) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['filiere_id'])) {
    header("Location: filiere.php");
    exit;
}

$filiereId = $_GET['filiere_id'];
$profId = $_SESSION['user_id'];  // ID du professeur connecté

require_once "../include/database.php";

try {
    // Préparer la requête pour filtrer par filière et ID du professeur
    $stmt_modules = $pdo->prepare("SELECT id, Nom_module FROM module WHERE id_filiere = :filiere_id AND id_prof = :prof_id");
    $stmt_modules->execute([
        'filiere_id' => $filiereId,
        'prof_id' => $profId
    ]);
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
    <link rel="stylesheet" href="assets/index.css">
    <link rel="stylesheet" href="assets/module.css">
    <link rel="stylesheet" href="assets/include/sidebarProf.css">
    <title>Acceuil</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-xgWvbC/GtpG27dbUMf057Ok6ZgoyNnuToSCzjUEuFQlyDhVdRflh5JL4tsbvtRL8yK1z2CqS3hINQjyGv7wXVg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .module-button {
            display: block;
            width: 100%;
            max-width: 300px;
            margin: 10px auto;
            padding: 5px 30px;
            font-size: 1rem;
            font-weight: bold;
            text-align: center;
            color: #fff;
            background-color: var(--nav-bg);
            height: 70px;
            border: none;
            border-radius: 15px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .module-button:hover {
            background-color: #0056b3;
            transform: scale(1.1);
        }

        .module-button:active {
            background-color: #004080;
        }

        form {
            text-align: center;
        }
        
        .Div {
            padding: 50px 250px;
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px #999999;
            text-align: center;
            display: flex;flex-direction: column;
            gap: 30px;
        }
    </style>
</head>

<body>
    <?php include './assets/include/sidebarProf.php'; ?>
    <div class="bodyDiv">
        <div class="Div">
            <h2>Modules de la Filière</h2>
        <?php if (empty($modules)) : ?>
            <p style="text-align: center;">Aucun module trouvé pour cette filière.</p>
        <?php else : ?>
            <?php foreach ($modules as $module) : ?>
                <form action="../professeur/exam.php" method="GET">
                    <input type="hidden" name="module_id" value="<?php echo $module['id']; ?>">
                    <button type="submit" class="module-button"><?php echo htmlspecialchars($module['Nom_module']); ?></button>
                </form>
            <?php endforeach; ?>
        <?php endif; ?>
        </div>
        

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