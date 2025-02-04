<?php
session_start();
if (!isset($_SESSION['user_id']) ||  ($_SESSION['user_type'] !== 'professeur' && $_SESSION['user_type'] !== 'chef_departement' &&  $_SESSION['user_type'] !== 'coordinateur_prof')) {
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

    // Calculate the total percentage
    $total_percentage = 0;
    foreach ($exams as $exam) {
        $total_percentage += $exam['pourcentage'];
    }
} catch (PDOException $e) {
    echo "Erreur lors de l'exécution de la requête : " . $e->getMessage();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="assets/exam.css">
    <link rel="stylesheet" href="assets/include/sidebarProf.css">
    <title>Liste des Examens</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .module-list {
            margin: 20px auto;
            background-color: #fff;
            padding: 20px 50px;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 80%;
        }

        .module-list h1 {
            color: #333;
            margin: 40px 0;
            text-align: center;
        }

        .module-list table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .module-list table th,
        .module-list table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .module-list table th {
            background-color: var(--nav-bg);
            color: #fff;
        }

        .module-list table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .module-list table tr:hover {
            background-color: #e9ecef;
        }

        .module-list a {
            color: #007bff;
            text-decoration: none;
        }

        .module-list a .fas {
            margin-right: 5px;
        }

        .module-list a .fa-plus {
            display: inline-block;
            padding: 10px 12px;
            background-color: var(--nav-bg);
            color: #fff;
            border-radius: 50%;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease;
        }

        .module-list a .fa-plus:hover {
            background-color: #0056b3;
        }

        .fa-edit {
            color: #28a745;
            margin-right: 10px;
        }

        .fa-trash {
            color: #dc3545;
        }

        .alert {
            color: red;
            text-align: center;
            margin: 20px 0;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <?php
    include './assets/include/sidebarProf.php';
    ?>
    <div class="bodyDiv">
        <div class="">
            <div class="module-list">
                <h1>Liste des Exams</h1>
                <?php if ($total_percentage !== 100) : ?>
                    <div class="alert">
                        Attention: Le total des pourcentages des examens est de <?php echo $total_percentage; ?>%. Il doit être égal à 100%.
                    </div>
                <?php endif; ?>
                <table>
                    <thead>
                        <tr>
                            <th>Type d'examen</th>
                            <th>Pourcentage</th>
                            <th>Options</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($exams as $exam) : ?>
                            <tr>
                                <td>
                                    <a class="check-exam" href="notes/saisir_notes.php?exam_id=<?php echo $exam['id']; ?>">
                                        <?php echo $exam['type']; ?>
                                    </a>
                                </td>
                                <td>
                                    <?php echo $exam['pourcentage']; ?>%
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

                <a href="ajouter_exam.php?module_id=<?php echo $_GET['module_id']; ?>">
                    <i class="fas fa-plus"></i>
                </a>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function() {
                var totalPercentage = <?php echo $total_percentage; ?>;
                $('.check-exam').on('click', function(event) {
                    if (totalPercentage !== 100) {
                        event.preventDefault();
                        alert("Le total des pourcentages des examens doit être égal à 100% avant de pouvoir accéder à cet examen.");
                    } else {
                        var href = $(this).attr('href');
                        var urlParams = new URLSearchParams(href.split('?')[1]);
                        var examId = urlParams.get('exam_id');

                        $.ajax({
                            url: 'check_exam.php',
                            method: 'GET',
                            data: {
                                exam_id: examId
                            },
                            dataType: 'json',
                            success: function(response) {
                                if (response.exists) {
                                    alert("Impossible de traiter, notes déjà en attente de traitement, veuillez contacter le coordinateur pour plus d'info");
                                } else {
                                    window.location.href = href;
                                }
                            },
                            error: function() {
                                alert("Erreur lors de la vérification des notes provisoires.");
                            }
                        });
                    }
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
