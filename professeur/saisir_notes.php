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

include '../include/database.php';

$exam_id = $_GET['exam_id'];

try {

    $stmt_exam_info = $pdo->prepare("SELECT type, pourcentage, id_module FROM exam WHERE id = :exam_id");
    $stmt_exam_info->execute(['exam_id' => $exam_id]);
    $exam_info = $stmt_exam_info->fetch(PDO::FETCH_ASSOC);

    // Récupérer le nom du module et l'ID de la filière
    $stmt_module_info = $pdo->prepare("SELECT Nom_module, id_filiere FROM module WHERE id = :module_id");
    $stmt_module_info->execute(['module_id' => $exam_info['id_module']]);
    $module_info = $stmt_module_info->fetch(PDO::FETCH_ASSOC);

    // Récupérer le nom de la filière et l'année
    $stmt_filiere_info = $pdo->prepare("SELECT Nom_filiere, annee FROM filiere WHERE id = :filiere_id");
    $stmt_filiere_info->execute(['filiere_id' => $module_info['id_filiere']]);
    $filiere_info = $stmt_filiere_info->fetch(PDO::FETCH_ASSOC);

    $stmt_students = $pdo->prepare("SELECT id, nom, prenom FROM etudiant WHERE id_filiere = :filiere_id");
    $stmt_students->execute(['filiere_id' => $module_info['id_filiere']]);
    $students = $stmt_students->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erreur lors de l'exécution de la requête : " . $e->getMessage();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $notes = $_POST['notes'];
$remarques = $_POST['remarques'];

// Construct the filename using details from the form
$filename = "notes_" . str_replace(' ', '_', strtolower($exam_info['type'])) . "_" . str_replace(' ', '_', strtolower($module_info['Nom_module'])) . "_" . str_replace(' ', '_', strtolower($filiere_info['Nom_filiere'])) . "_" . $filiere_info['annee'] . ".csv";

// Open the file in write mode
$file = fopen($filename, "w");

// Check if file was opened successfully
if ($file === false) {
    die('Unable to open file for writing');
}

// Define the header row
$headers = ['Nom', 'Prenom', 'Note', 'Remarque'];

// Write the headers to the CSV
fputcsv($file, $headers);

// Loop through each student to get their data
foreach ($students as $student) {
    // Retrieve the note and remarque, handle missing data with null coalescing operator
    $note = $notes[$student['id']] ?? '';
    $remarque = $remarques[$student['id']] ?? '';

    // Array of data to write to the CSV file
    $data = [
        $student['nom'],
        $student['prenom'],
        $note,
        $remarque
    ];

    // Write the data to the CSV file
    fputcsv($file, $data);
}

// Close the file
fclose($file);

    // Téléchargement du fichier
    header("Content-disposition: attachment; filename=$filename");
    header("Content-type: application/csv");
    readfile($filename);
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../professeur/assets/note.css">
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
    include '../include/nav_cote.php';
    ?>
    <script>
        // Sélectionnez la div .body
        var bodyDiv = document.querySelector('.body');

        // Ajoutez votre propre contenu à la div .body
        bodyDiv.innerHTML = `
        <h1>Saisir les notes pour l'examen <?php echo $exam_info['type']; ?></h1>
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
                        <td><?php echo $student['nom']; ?></td>
                        <td><?php echo $student['prenom']; ?></td>
                        <td><input type="number" name="notes[<?php echo $student['id']; ?>]" min="0" max="20"></td>
                        <td><input type="text" name="remarques[<?php echo $student['id']; ?>]"></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <button type="submit"> Télécharger</button>
        <button class="consulter">
    <a href="consulter_notes.php?exam_id=<?php echo $exam_id; ?>&module_id=<?php echo $exam_info['id_module']; ?>&filiere_id=<?php echo $module_info['id_filiere']; ?>">
        Consulter les Notes
    </a>
</button>


    </form>
        `;
    </script>

</body>

</html>