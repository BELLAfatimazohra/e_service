<?php
session_start();
if (!isset($_SESSION['user_id']) ||  ($_SESSION['user_type'] !== 'professeur' && $_SESSION['user_type'] !== 'coordinateur_prof') ) {
    header("Location: login.php");
    exit;
}

require_once '../../include/database.php';

if (!isset($_POST['exam_id']) && !isset($_GET['exam_id'])) {
    header("Location: erreur.php");
    exit;
}

$exam_id = $_POST['exam_id'] ?? $_GET['exam_id'];

try {
    $stmt_exam_info = $pdo->prepare("SELECT type, pourcentage, id_module FROM exam WHERE id = :exam_id");
    $stmt_exam_info->execute(['exam_id' => $exam_id]);
    $exam_info = $stmt_exam_info->fetch(PDO::FETCH_ASSOC);

    if (!$exam_info) {
        throw new Exception("Informations sur l'examen non trouvées.");
    }

    // Récupérer le nom du module et l'ID de la filière
    $stmt_module_info = $pdo->prepare("SELECT Nom_module, id_filiere FROM module WHERE id = :module_id");
    $stmt_module_info->execute(['module_id' => $exam_info['id_module']]);
    $module_info = $stmt_module_info->fetch(PDO::FETCH_ASSOC);

    if (!$module_info) {
        throw new Exception("Informations sur le module non trouvées.");
    }

    // Récupérer le nom de la filière et l'année
    $stmt_filiere_info = $pdo->prepare("SELECT Nom_filiere, annee FROM filiere WHERE id = :filiere_id");
    $stmt_filiere_info->execute(['filiere_id' => $module_info['id_filiere']]);
    $filiere_info = $stmt_filiere_info->fetch(PDO::FETCH_ASSOC);

    if (!$filiere_info) {
        throw new Exception("Informations sur la filière non trouvées.");
    }

    // Récupérer les données depuis le fichier CSV
    $filename = "notes_" . str_replace(' ', '_', strtolower($exam_info['type'])) . "_" . str_replace(' ', '_', strtolower($module_info['Nom_module'])) . "_" . str_replace(' ', '_', strtolower($filiere_info['Nom_filiere'])) . "_" . $filiere_info['annee'] . ".csv";

    if (!file_exists($filename)) {
        die("Le fichier de notes n'existe pas.");
    }

    // Lecture du fichier CSV
    $file = fopen($filename, "r");
    $students_data = []; // Stockage des données des étudiants

    // Lire le fichier ligne par ligne
    while (($data = fgetcsv($file, 0, ",")) !== FALSE) {
        $students_data[$data[0]] = [
            'NomPrenom' => $data[1] . ' ' . trim($data[2]),
            'note' => $data[3],
            'remarque' => $data[4]
        ];
    }

    fclose($file);

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['notes']) && isset($_POST['remarques'])) {
        $notes = $_POST['notes'];
        $remarques = $_POST['remarques'];

        // Mettre à jour les données dans le fichier CSV
        $file = fopen($filename, "w");

        if ($file === false) {
            die('Impossible d\'ouvrir le fichier pour l\'écriture');
        }

        foreach ($students_data as $student_id => $data) {
            if (isset($notes[$student_id])) {
                $students_data[$student_id]['note'] = $notes[$student_id];
                $students_data[$student_id]['remarque'] = $remarques[$student_id];
            }

            $data_to_write = [
                $student_id,
                explode(' ', $students_data[$student_id]['NomPrenom'])[0], // Nom
                trim(explode(' ', $students_data[$student_id]['NomPrenom'])[1]), // Prénom
                $students_data[$student_id]['note'],
                $students_data[$student_id]['remarque']
            ];

            fputcsv($file, $data_to_write);
        }

        fclose($file);

        echo "Les notes ont été mises à jour avec succès.";
    }
} catch (PDOException $e) {
    echo "Erreur lors de l'exécution de la requête : " . $e->getMessage();
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/note.css">
    <link rel="stylesheet" href="../assets/include/sidebarProf.css">
    <title>Modifier les notes</title>
    <style>
        h1 {
            color: #333;
            text-align: center;
            margin: 20px 0;
        }

        .container {
            padding: 20px;
            max-width: 900px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .bodyDiv {
            padding-top: 30px;
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
            margin-top: 60px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        table {
            padding-top: 50px;
            padding: 20px;
            max-width: 800px;
            margin: 20 auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f8f8f8;
            color: #333;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        input[type="number"],
        input[type="text"] {
            width: 100%;
            padding: 8px;
            margin: 4px 0;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .modifier {
            padding: 10px 20px;
            background-color: #007bff;
            border: none;
            color: white;
            cursor: pointer;
            border-radius: 8px;
            font-size: 16px;
            transition: background-color 0.4s;
            margin: 10px 5px 0 0;
        }

        .modifier:hover {
            background-color: #0056b3;
        }

        .modifier a {
            color: white;
            text-decoration: none;
        }

        form {
            display: inline-block;
        }

        .button-container {
            display: flex;
            justify-content: center;

            gap: 10px;

        }

        #success-message {
            color: green;
            font-weight: bold;
            text-align: center;
            margin-top: 20px;
        }

        .consulter {
            padding: 10px 20px;
            background-color: #007bff;
            border: none;
            color: white;
            cursor: pointer;
            border-radius: 8px;
            font-size: 16px;
            transition: background-color 0.4s;
            margin: 10px 5px 0 0;
        }

        .consulter a {
            color: white;
            text-decoration: none;
        }

        .consulter:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <?php include '../assets/include/sidebarProf.php'; ?>
    <script>
        // Select all elements with the class 'bodyDiv'
        var bodyDivs = document.querySelectorAll('.bodyDiv');

        // Loop through all elements and remove each one except the last
        for (var i = 0; i < bodyDivs.length - 1; i++) {
            bodyDivs[i].parentNode.removeChild(bodyDivs[i]);
        }

        // Now that only the last bodyDiv remains, you can modify its innerHTML
        var lastBodyDiv = bodyDivs[bodyDivs.length - 1]; // Get the last bodyDiv element
        lastBodyDiv.innerHTML = `
        <h1>Modifier les notes pour le <?php echo htmlspecialchars($exam_info['type']); ?></h1>
        <form method="POST">
            <input type="hidden" name="exam_id" value="<?php echo htmlspecialchars($exam_id); ?>">
            <table>
                <thead>
                    <tr>
                        <th>Étudiant</th>
                        <th>Note</th>
                        <th>Remarque</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students_data as $student_id => $data) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($data['NomPrenom']); ?></td>
                            <td><input type="number" name="notes[<?php echo htmlspecialchars($student_id); ?>]" value="<?php echo htmlspecialchars($data['note']); ?>" min="0" max="20"></td>
                            <td><input type="text" name="remarques[<?php echo htmlspecialchars($student_id); ?>]" value="<?php echo htmlspecialchars($data['remarque']); ?>"></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <button class="modifier" type="submit">Modifier</button>
            <button class="consulter"><a href="consulter_notes.php?exam_id=<?php echo htmlspecialchars($exam_id); ?>&module_id=<?php echo htmlspecialchars($exam_info['id_module']); ?>&filiere_id=<?php echo htmlspecialchars($module_info['id_filiere']); ?>">Consulter les Notes</a></button>
        </form>

    `;
    </script>


</body>

</html>