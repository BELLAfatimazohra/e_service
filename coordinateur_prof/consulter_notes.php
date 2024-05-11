<?php
session_start();

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'coordinateur_prof') {
    header("Location: index.php");
    exit;
}

if (!isset($_GET['exam_id']) || !isset($_GET['module_id']) || !isset($_GET['filiere_id'])) {
    header("Location: erreur.php");
    exit;
}

include '../include/database.php';

$exam_id = $_GET['exam_id'];
$module_id = $_GET['module_id'];
$filiere_id = $_GET['filiere_id'];

try {
    $stmt_exam_info = $pdo->prepare("SELECT type FROM exam WHERE id = :exam_id");
    $stmt_exam_info->execute(['exam_id' => $exam_id]);
    $exam_info = $stmt_exam_info->fetch(PDO::FETCH_ASSOC);

    $stmt_module_info = $pdo->prepare("SELECT Nom_module FROM module WHERE id = :module_id");
    $stmt_module_info->execute(['module_id' => $module_id]);
    $module_info = $stmt_module_info->fetch(PDO::FETCH_ASSOC);

    $stmt_filiere_info = $pdo->prepare("SELECT Nom_filiere, annee FROM filiere WHERE id = :filiere_id");
    $stmt_filiere_info->execute(['filiere_id' => $filiere_id]);
    $filiere_info = $stmt_filiere_info->fetch(PDO::FETCH_ASSOC);


    $filename = "notes_" . str_replace(' ', '_', strtolower($exam_info['type'])) . "_" . str_replace(' ', '_', strtolower($module_info['Nom_module'])) . "_" . str_replace(' ', '_', strtolower($filiere_info['Nom_filiere'])) . "_" . $filiere_info['annee'] . ".csv";


    if (file_exists($filename)) {
?>

        <!DOCTYPE html>
        <html lang="fr">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Contenu du fichier CSV</title>
            <link rel="stylesheet" href="../professeur/assets/exam.css">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
            <link rel="stylesheet" href="../professeur/assets/consulter_notes.css">

        </head>

        <body>
            <?php include '../include/nav_cote_corr.php'; ?>
            <script>
                var bodyDiv = document.querySelector('.bodyDiv');

                bodyDiv.innerHTML = `
       <h1>Les notes des etudiants sont  :</h1>
    <table>
        <?php
        $file = fopen($filename, "r");
        while (($data = fgetcsv($file))) {
            echo "<tr>";
            foreach ($data as $value) {
                echo "<td>$value</td>";
            }
            echo "</tr>";
        }
        fclose($file);
        ?>
    </table><br>
<form action="modifier_notes.php" method="POST">
    <input type="hidden" name="exam_id" value="<?php echo $exam_id; ?>">
    <input type="hidden" name="module_id" value="<?php echo $module_id; ?>">
    <input type="hidden" name="filiere_id" value="<?php echo $filiere_id; ?>">
    <button type="submit">Modifier les notes</button>
</form>
<form action="telecharger_notes.php" method="POST">
    <input type="hidden" name="exam_id" value="<?php echo $exam_id; ?>">
    <input type="hidden" name="module_id" value="<?php echo $module_id; ?>">
    <input type="hidden" name="filiere_id" value="<?php echo $filiere_id; ?>">
    <button type="submit">Télécharger les notes</button>
</form>
<form action="valider_notes.php" method="POST">
    <input type="hidden" name="exam_id" value="<?php echo $exam_id; ?>">
    <input type="hidden" name="module_id" value="<?php echo $module_id; ?>">
    <input type="hidden" name="filiere_id" value="<?php echo $filiere_id; ?>">
    <button type="submit">Valider les notes</button>
</form>
       `;
            </script>


        </body>

        </html>

<?php
    } else {
        echo "Le fichier CSV n'existe pas.";
    }
} catch (PDOException $e) {
    echo "Erreur lors de l'exécution de la requête : " . $e->getMessage();
}
?>