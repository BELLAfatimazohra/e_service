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
    <?php session_start();
    include 'include/sidebarCoor.php'; ?>
    <div class="bodyDiv">
        <?php





        if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'coordinateur_prof') {

            header("Location: index.php");
            exit;
        }
        include '../include/database.php';

        $exam_id = $_POST['exam_id'];
        $module_id = $_POST['module_id'];
        $filiere_id = $_POST['filiere_id'];
        $stmt_exam_info = $pdo->prepare("SELECT type FROM exam WHERE id = :exam_id");
        $stmt_exam_info->execute(['exam_id' => $exam_id]);
        $exam_info = $stmt_exam_info->fetch(PDO::FETCH_ASSOC);

        // Récupérer les informations sur le module
        $stmt_module_info = $pdo->prepare("SELECT Nom_module FROM module WHERE id = :module_id");
        $stmt_module_info->execute(['module_id' => $module_id]);
        $module_info = $stmt_module_info->fetch(PDO::FETCH_ASSOC);

        // Récupérer les informations sur la filière
        $stmt_filiere_info = $pdo->prepare("SELECT Nom_filiere, annee FROM filiere WHERE id = :filiere_id");
        $stmt_filiere_info->execute(['filiere_id' => $filiere_id]);
        $filiere_info = $stmt_filiere_info->fetch(PDO::FETCH_ASSOC);



        echo "nouvel affichage en attente de validtion <br>" . strtolower($exam_info['type']). "<br>" . strtolower($module_info['Nom_module']). "<br>" . strtolower($filiere_info['Nom_filiere']). " " . $filiere_info['annee'];


        ?>
    </div>
</body>

</html>