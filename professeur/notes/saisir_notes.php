<?php
session_start();
if (!isset($_SESSION['user_id']) ||  ($_SESSION['user_type'] !== 'professeur' && $_SESSION['user_type'] !== 'coordinateur_prof') ) {
    header("Location: login.php");
    exit;
}

require_once '../../include/database.php';

if (!isset($_GET['exam_id'])) {
    header("Location: erreur.php");
    exit;
}

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

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $notes = $_POST['notes'];
        $remarques = $_POST['remarques'];

        // Création du nom de fichier
        $filename = "notes_" . str_replace(' ', '_', strtolower($exam_info['type'])) . "_" . str_replace(' ', '_', strtolower($module_info['Nom_module'])) . "_" . str_replace(' ', '_', strtolower($filiere_info['Nom_filiere'])) . "_" . $filiere_info['annee'] . ".csv";

        // Ouverture du fichier en mode écriture
        $file = fopen($filename, "w");

        // Vérification si l'ouverture du fichier a réussi
        if ($file === false) {
            die('Impossible d\'ouvrir le fichier pour l\'écriture');
        }

        // Boucle sur les étudiants pour écrire leurs notes et remarques dans le fichier CSV
        foreach ($students as $student) {
            $note = $notes[$student['id']] ?? ''; // Récupération de la note
            $remarque = $remarques[$student['id']] ?? ''; // Récupération de la remarque

            // Données à écrire dans le fichier CSV
            $data = [
                $student['id'],
                $student['nom'],
                $student['prenom'],
                $note,
                $remarque
            ];
            // Écriture des données dans le fichier CSV
            fputcsv($file, $data);
        }
        // Fermeture du fichier
        fclose($file);
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
        .bodyDiv {
            padding-top: 30px;
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
            margin-top: 10rem;
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
            padding: 10px;
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
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button.sauvegarder {
            padding: 10px 20px;
            background-color: #007bff;
            border: none;
            color: white;
            cursor: pointer;
            border-radius: 10px;
            font-size: 16px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        button.sauvegarder :hover {
            background-color: #0056b3;
            transform: scale(1.1);
        }

        button.sauvegarder a {
            color: white;
            text-decoration: none;
        }

        button.consulter a {
            color: white;
            text-decoration: none;
        }

        button.consulter {
            padding: 10px 20px;
            background-color: #007bff;
            border: none;
            color: white;
            cursor: pointer;
            border-radius: 10px;
            font-size: 16px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        button.consulter:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <?php
    include_once '../assets/include/sidebarProf.php';
    ?>
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
        <h1>Saisir les notes pour le <?php echo $exam_info['type']; ?></h1>
        <form id="noteForm" method="POST">
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
            <button class="sauvegarder" type="submit">Sauvegarder</button>
            <button class="consulter">
                <a href="consulter_notes.php?exam_id=<?php echo $exam_id; ?>&module_id=<?php echo $exam_info['id_module']; ?>&filiere_id=<?php echo $module_info['id_filiere']; ?>">
                    Consulter les Notes
                </a>
            </button>
        </form>
        <div id="successMessage" style="display: none; color: green; margin-top: 20px;">Notes sauvegardées avec succès!</div>
    
        `;
    </script>

</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#noteForm').on('submit', function(event) {
                event.preventDefault();
                var formData = $(this).serialize();
                
                $.ajax({
                    url: window.location.href,
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        $('#successMessage').show().delay(3000).fadeOut();
                        $("table").hide();
                    },
                    error: function() {
                        alert('Erreur lors de la sauvegarde des notes.');
                    }
                });
            });
        });
    </script>
</html>