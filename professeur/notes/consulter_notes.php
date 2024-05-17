<?php
session_start();
if (!isset($_SESSION['user_id']) ||  ($_SESSION['user_type'] !== 'professeur' && $_SESSION['user_type'] !== 'coordinateur_prof') ) {
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
    echo $filename;

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
                    margin: 0 auto;
                    background-color: #fff;
                    border-radius: 8px;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                }

                table {
                    width: 100%;

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

                form {
                    display: inline;
                }

                .btnnn {
                    padding: 10px 20px;
                    background-color: #007bff;
                    border: none;
                    color: white;
                    cursor: pointer;
                    border-radius: 8px;
                    font-size: 16px;
                    transition: background-color 0.4s;

                    margin-bottom: -16px;
                    margin-left: 320px;



                }

                .btnn {
                    padding: 10px 20px;
                    background-color: #007bff;
                    border: none;
                    color: white;
                    cursor: pointer;
                    border-radius: 8px;
                    font-size: 16px;
                    transition: background-color 0.4s;
                    margin-top: -68px;
                    margin-left: 500px;


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
                    margin-top: -100px;
                    margin-left: 700px;


                }

                .btn:hover {
                    background-color: #0056b3;
                }

                .btnn:hover {
                    background-color: #0056b3;
                }

                .btnnn:hover {
                    background-color: #0056b3;
                }

                .btnn a {
                    color: white;
                    text-decoration: none;
                }

                .btnnn a {
                    color: white;
                    text-decoration: none;
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
                <h1>Les notes des etudiants sont :</h1>
                <table>
                    <?php
                    $file = fopen($filename, "r");
                    echo "<tr>";
                    echo "<th>ID</th><th>First name</th><th>Last Name</th><th>Score</th><th>Remarks</th>";
                    echo "</tr>";
                    while (($data = fgetcsv($file))) {
                        echo "<tr>";
                        foreach ($data as $value) {
                            echo "<td>$value</td>";
                        }
                        echo "</tr>";
                    }
                    fclose($file);
                    ?>
                </table>
                <form action="modifier_notes.php" method="POST">
                    <input type="hidden" name="exam_id" value="<?php echo $exam_id; ?>">
                    <input type="hidden" name="module_id" value="<?php echo $module_id; ?>">
                    <input type="hidden" name="filiere_id" value="<?php echo $filiere_id; ?>">
                    <button  class="btnnn" type="submit">Modifier les notes</button>
                </form>
                <form action="telecharger_notes.php" method="POST">
                    <input type="hidden" name="exam_id" value="<?php echo $exam_id; ?>">
                    <input type="hidden" name="module_id" value="<?php echo $module_id; ?>">
                    <input type="hidden" name="filiere_id" value="<?php echo $filiere_id; ?>">
                    <button  class="btnn" type="submit">Télécharger les notes</button>
                </form>
                <form action="valider_notes.php" method="POST" id="valider_notes">
                    <input type="hidden" name="exam_id" value="<?php echo $exam_id; ?>">
                    <input type="hidden" name="module_id" value="<?php echo $module_id; ?>">
                    <input type="hidden" name="filiere_id" value="<?php echo $filiere_id; ?>">
                    <button class="btn" type="submit">Valider les notes</button>
                <div id="success-message" style="display: none;">Notes validated successfully!</div>
                </form>




                `;
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
                                $('#valider_notes')[0].reset();
                            },
                            error: function(xhr, status, error) {
                                console.error(xhr.responseText);
                            }
                        });
                    });
                });
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