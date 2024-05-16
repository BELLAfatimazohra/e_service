<?php
session_start();

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'professeur') {
    header("Location: index.php");
    exit;
}

require_once '../../include/database.php';

if (!isset($_POST['exam_id'])) {
    header("Location: erreur.php");
    exit;
}

$exam_id = $_POST['exam_id'];

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

    // Récupérer les données depuis le fichier CSV
    $filename = "notes_" . str_replace(' ', '_', strtolower($exam_info['type'])) . "_" . str_replace(' ', '_', strtolower($module_info['Nom_module'])) . "_" . str_replace(' ', '_', strtolower($filiere_info['Nom_filiere'])) . "_" . $filiere_info['annee'] . ".csv";

    if (!file_exists($filename)) {
        // Gérer le cas où le fichier n'existe pas
        die("Le fichier de notes n'existe pas.");
    }

    // Lecture du fichier CSV
    $file = fopen($filename, "r");
    $students_data = []; // Stockage des données des étudiants

    // Lire le fichier ligne par ligne
    while (($data = fgetcsv($file, 0, ",")) !== FALSE) {
        // Stocker les données de chaque étudiant
        $students_data[$data[0]] = ['Prenom' => $data[1], 'note' => $data[2], 'remarque' => $data[3]]; // Correction des index pour correspondre aux données dans le CSV
    }

    fclose($file);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $notes = $_POST['notes'];
        $remarques = $_POST['remarques'];

        // Mettre à jour les données dans le fichier CSV
        $file = fopen($filename, "w");

        // Vérifier si l'ouverture du fichier a réussi
        if ($file === false) {
            die('Impossible d\'ouvrir le fichier pour l\'écriture');
        }

        // Boucle sur les étudiants pour mettre à jour leurs notes et remarques dans le fichier CSV
        foreach ($students_data as $student_id => $data) {
            if (isset($notes[$student_id])) {
                // Mettre à jour la note et la remarque si elles ont été modifiées
                $students_data[$student_id]['note'] = $notes[$student_id];
                $students_data[$student_id]['remarque'] = $remarques[$student_id];
            }

            // Données à écrire dans le fichier CSV
            $data_to_write = [
                $student_id,
                $students_data[$student_id]['Prenom'], // Utilisation de 'Prenom' pour la colonne 'Prénom'
                $students_data[$student_id]['note'],
                $students_data[$student_id]['remarque']
            ];

            // Écriture des données dans le fichier CSV
            fputcsv($file, $data_to_write);
        }

        // Fermeture du fichier
        fclose($file);

        echo "Les notes ont été mises à jour avec succès.";
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
    <title>Modifier les notes</title>
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
    include '../assets/include/sidebarProf.php';
    ?>
    <div class="bodyDiv">
        <h1>Modifier les notes pour l'examen <?php echo $exam_info['type']; ?></h1>
        <form method="POST">
            <input type="hidden" name="exam_id" value="<?php echo $exam_id; ?>">
            <table>
                <thead>
                    <tr>
                        <th>ID Étudiant</th> <!-- Modification du titre de la colonne -->
                        <th>Prénom</th>
                        <th>Note</th>
                        <th>Remarque</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students_data as $student_id => $data) : ?>
                        <tr>
                            <td><?php echo $student_id; ?></td> <!-- Afficher l'ID de l'étudiant -->
                            <td><?php echo $data['Prenom']; ?></td> <!-- Assurez-vous que la colonne 'prenom' existe dans vos données -->
                            <td><input type="number" name="notes[<?php echo $student_id; ?>]" min="0" max="20" value="<?php echo $data['note']; ?>"></td>
                            <td><input type="text" name="remarques[<?php echo $student_id; ?>]" value="<?php echo $data['remarque']; ?>"></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button type="submit">Mettre à jour</button>
            <button class="consulter">
                <a href="consulter_notes.php?exam_id=<?php echo $exam_id; ?>&module_id=<?php echo $exam_info['id_module']; ?>&filiere_id=<?php echo $module_info['id_filiere']; ?>">
                    Consulter les Notes
                </a>
            </button>
        </form>
    </div>

</body>

</html>