<?php
session_start();
if (!isset($_SESSION['user_id']) ||  ($_SESSION['user_type'] !== 'professeur' && $_SESSION['user_type'] !== 'coordinateur_prof') ) {
    header("Location: login.php");
    exit;
}
if (!isset($_GET['module_id'])) {
    header("Location: erreur.php");
    exit;
}

try {
    require_once '../include/database.php';
} catch (PDOException $e) {
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
    exit;
}

$id_prof_connecte = $_SESSION['user_id'];
$id_module_clique = isset($_GET['module_id']) ? $_GET['module_id'] : null;


if (isset($_GET['delete_exam_id'])) {
    $delete_exam_id = $_GET['delete_exam_id'];
    try {
        $stmt_delete_exam = $pdo->prepare("DELETE FROM exam WHERE id = :exam_id AND id_module = :id_module AND id_prof = :id_prof");
        $stmt_delete_exam->execute(['exam_id' => $delete_exam_id, 'id_module' => $id_module_clique, 'id_prof' => $id_prof_connecte]);

        header("Location: exam.php?module_id=" . $id_module_clique);
        exit;
    } catch (PDOException $e) {
        echo "Erreur lors de la suppression de l'examen : " . $e->getMessage();
    }
}

try {
    $stmt_exams = $pdo->prepare("SELECT id, type, pourcentage FROM exam WHERE id_prof = :id_prof AND id_module = :id_module");
    $stmt_exams->execute(['id_prof' => $id_prof_connecte, 'id_module' => $id_module_clique]);
    $exams = $stmt_exams->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erreur lors de l'exécution de la requête : " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../professeur/assets/exam.css">
    <link rel="stylesheet" href="assets/include/sidebarProf.css">
    <title>Liste des Examens</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
    <?php
    include './assets/include/sidebarProf.php';
    ?>
<div class="bodyDiv"> <div class="">
        <div class="module-list">
            <h2>Liste des Exams</h2>
            <table>
                <thead>
                    <tr>
                        <th>Type d'examen</th>
                        <th>Options</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($exams as $exam) : ?>
                        <tr>
                            <td>
                                <a href="notes/saisir_notes.php?exam_id=<?php echo $exam['id']; ?>">
                                    <?php echo $exam['type']; ?> - <?php echo $exam['pourcentage']; ?>%
                                </a>
                            </td>
                            <td>
                                <a href="modifier_exam.php?exam_id=<?php echo $exam['id']; ?>">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="exam.php?module_id=<?php echo $id_module_clique; ?>&delete_exam_id=<?php echo $exam['id']; ?>" onclick="return confirm('Voulez-vous vraiment supprimer cet examen ?');">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <!-- Bouton pour ajouter -->
            <a href="ajouter_exam.php?module_id=<?php echo $_GET['module_id']; ?>">
                <i class="fas fa-plus"></i>
            </a>
        </div></div>

   
</body>

</html>