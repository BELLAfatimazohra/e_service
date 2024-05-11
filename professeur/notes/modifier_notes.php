<?php
session_start();

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'professeur') {
    header("Location: index.php");
    exit;
}

require_once '../../include/database.php';

if (!isset($_POST['exam_id']) || !isset($_POST['module_id']) || !isset($_POST['filiere_id'])) {
    header("Location: erreur.php");
    exit;
}

$exam_id = $_POST['exam_id'];

try {
    $stmt_exam_info = $pdo->prepare("SELECT type, pourcentage, id_module FROM exam WHERE id = :exam_id");
    $stmt_exam_info->execute(['exam_id' => $exam_id]);
    $exam_info = $stmt_exam_info->fetch(PDO::FETCH_ASSOC);

    $stmt_module_info = $pdo->prepare("SELECT Nom_module, id_filiere FROM module WHERE id = :module_id");
    $stmt_module_info->execute(['module_id' => $exam_info['id_module']]);
    $module_info = $stmt_module_info->fetch(PDO::FETCH_ASSOC);

    $stmt_filiere_info = $pdo->prepare("SELECT Nom_filiere, annee FROM filiere WHERE id = :filiere_id");
    $stmt_filiere_info->execute(['filiere_id' => $module_info['id_filiere']]);
    $filiere_info = $stmt_filiere_info->fetch(PDO::FETCH_ASSOC);

    $stmt_students = $pdo->prepare("SELECT id, Nom, Prenom FROM etudiant WHERE id_filiere = :filiere_id");
    $stmt_students->execute(['filiere_id' => $module_info['id_filiere']]);
    $students = $stmt_students->fetchAll(PDO::FETCH_ASSOC);
    $filename = "notes_" . str_replace(' ', '_', strtolower($exam_info['type'])) . "_" . str_replace(' ', '_', strtolower($module_info['Nom_module'])) . "_" . str_replace(' ', '_', strtolower($filiere_info['Nom_filiere'])) . "_" . $filiere_info['annee'] . ".csv";
    $notes = [];
    if (file_exists($filename)) {
        if (($handle = fopen($filename, "r")) !== FALSE) {
            fgetcsv($handle);
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $notes[$data[0]] = ['note' => $data[2], 'remarque' => $data[3]];
            }
            fclose($handle);
        }
    }
} catch (PDOException $e) {
    echo "Erreur lors de l'exécution de la requête : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/note.css">
    <link rel="stylesheet" href="../assets/include/sidebarProf.css">
    <title>Saisir les notes</title>
    <style>
        .consulter {
            background-color: #007bff;
            border: none;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 8px;
            transition-duration: 0.4s;
        }

        .consulter a {
            text-align: center;
            text-decoration: none;
            color: white;
            font-size: 16px;
        }

        .consulter:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <?php
    include '../assets/include/sidebarProf.php'
    ?>
    <div class="bodyDiv">
        <h1>Modifier les notes pour l'examen <?php echo $exam_info['type']; ?></h1>
        <form method="POST">
            <input type="hidden" name="exam_id" value="<?php echo $exam_id; ?>">
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Note</th>
                        <th>Remarque</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student) : ?>
                        <tr>
                            <td><?php echo $student['Nom']; ?></td>
                            <td><?php echo $student['Prenom']; ?></td>
                            <td><input type="number" name="notes[<?php echo $student['id']; ?>]" value="<?php echo $notes[$student['id']]['note'] ?? ''; ?>" min="0" max="20"></td>
                            <td><input type="text" name="remarques[<?php echo $student['id']; ?>]" value="<?php echo $notes[$student['id']]['remarque'] ?? ''; ?>"></td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button type="submit">Sauvegarder</button>
            <button class="consulter"><a href="consulter_notes.php?exam_id=<?php echo $exam_id; ?>&module_id=<?php echo $exam_info['id_module']; ?>&filiere_id=<?php echo $module_info['id_filiere']; ?>">Consulter les Notes</a></button>
        </form>
    </div>


</body>

</html>