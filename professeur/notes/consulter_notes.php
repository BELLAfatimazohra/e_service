<?php
session_start();
if (!isset($_SESSION['user_id']) ||  ($_SESSION['user_type'] !== 'professeur' && $_SESSION['user_type'] !== 'chef_departement' &&  $_SESSION['user_type'] !== 'coordinateur_prof')) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['exam_id']) || !isset($_GET['module_id']) || !isset($_GET['filiere_id'])) {
    header("Location: erreur.php");
    exit;
}

include '../../include/database.php';

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
            <link rel="stylesheet" href="../assets/include/sidebarProf.css">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
            <link rel="stylesheet" href="../assets/consulter_notes.css">
            <link rel="stylesheet" href="../assets/exam.css">
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
            <style>
                h1 {
                    color: #333;
                    text-align: center;
                    margin: 20px 0;
                    margin-top: 50px;
                }

                .bodyDiv {
                    padding: 20px;
                    max-width: 900px;
                    margin: 7rem auto;
                    background-color: #fff;
                    border-radius: 8px;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                }

                table {

            width: 80%;
            padding: 20px;
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

                form {
                    display: inline;
                    width: fit-content;
                }

                .btn {
                    padding: 10px 20px;
                    background-color: #007bff;
                    border: none;
                    color: white;
                    cursor: pointer;
                    border-radius: 8px;
                    font-size: 16px;
                    transition: background-color 0.4s;
                }

                .btn:hover {
                    background-color: #0056b3;
                }

                .btn a {
                    color: white;
                    text-decoration: none;
                }

                #success-message {
                    color: green;
                    font-weight: bold;
                    text-align: center;
                    margin-top: 20px;
                }

                .consulter {
                    background-color: #28a745;
                }

                .consulter:hover {
                    background-color: #218838;
                }

                .container {
                    width: 100%;
                    display: flex;
                    align-items: center;
                    justify-content: space-around;
                }
            </style>
        </head>

        <body>
            <?php include '../assets/include/sidebarProf.php'; ?>
            <script>
                var bodyDivs = document.querySelectorAll('.bodyDiv');

                // Loop through all elements and remove each one except the last
                for (var i = 0; i < bodyDivs.length; i++) {
                    bodyDivs[i].parentNode.removeChild(bodyDivs[i]);
                }
            </script>
            <div class="bodyDiv">
                <h1>Les Notes des Etudiants Sont :</h1>
                <table>
                    <?php
                    $file = fopen($filename, "r");
                    if ($file !== false) {
                        echo "<tr><th>ID</th><th>First name</th><th>Last Name</th><th>Score</th><th>Remarks</th></tr>";
                        while (($data = fgetcsv($file))) {
                            echo "<tr>";
                            foreach ($data as $value) {
                                echo "<td>$value</td>";
                            }
                            echo "</tr>";
                        }
                        fclose($file);
                    } else {
                        echo "<p>Unable to open the file.</p>";
                    }
                    ?>
                </table>
                <div class="container">
                    <form action="modifier_notes.php" method="POST">
                        <input type="hidden" name="exam_id" value="<?php echo $exam_id; ?>">
                        <input type="hidden" name="module_id" value="<?php echo $module_id; ?>">
                        <input type="hidden" name="filiere_id" value="<?php echo $filiere_id; ?>">
                        <button class="btn" type="submit">Modifier les Notes</button>
                    </form>
                    <form action="telecharger_notes.php" method="POST">
                        <input type="hidden" name="exam_id" value="<?php echo $exam_id; ?>">
                        <input type="hidden" name="module_id" value="<?php echo $module_id; ?>">
                        <input type="hidden" name="filiere_id" value="<?php echo $filiere_id; ?>">
                        <button class="btn" type="submit">Télécharger les Notes</button>
                    </form>
                    <form action="valider_notes.php" method="POST" id="valider_notes">
                        <input type="hidden" name="exam_id" value="<?php echo $exam_id; ?>">
                        <input type="hidden" name="module_id" value="<?php echo $module_id; ?>">
                        <input type="hidden" name="filiere_id" value="<?php echo $filiere_id; ?>">
                        <button class="btn" type="submit">Valider les Notes</button>
                    </form></div>
                    <div class="container">
                        
                    <div id="success-message" style="display: none;">Notes validated successfully!</div>
                    <a style="display: none;" class="btn" href="/e_service/professeur/exam.php" id="return-button">Retour</a>
                
                    </div>
            </div>
            <script>
                $(document).ready(function() {
                    $('#valider_notes').submit(function(event) {
                        event.preventDefault();
                        var formData = $(this).serialize();
                        $.ajax({
                            type: 'POST',
                            url: 'valider_notes.php',
                            data: formData,
                            success: function(response) {
                                $('#success-message').show();
                                $('#return-button').show();
                                $('#valider_notes')[0].reset();
                            },
                            error: function(xhr, status, error) {
                                console.error(xhr.responseText);
                            }
                        });
                    });
                });
            </script>
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

<?php
    } else {
        echo "Le fichier CSV n'existe pas.<br>";
    }
} catch (PDOException $e) {
    echo "Erreur lors de l'exécution de la requête : " . $e->getMessage();
}
?>