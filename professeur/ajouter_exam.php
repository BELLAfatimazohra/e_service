<?php
session_start();
if (!isset($_SESSION['user_id']) || ($_SESSION['user_type'] !== 'professeur' && $_SESSION['user_type'] !== 'chef_departement' &&  $_SESSION['user_type'] !== 'coordinateur_prof')) {
    header("Location: login.php");
    exit;
}
if (!isset($_GET['module_id'])) {
    header("Location: erreur.php");
    exit;
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once '../include/database.php';
    $type = $_POST['type'];
    $pourcentage = $_POST['pourcentage'];
    $id_module = $_GET['module_id'];
    $id_prof = $_SESSION['user_id'];

    // Vérifier si l'examen existe déjà
    $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM exam WHERE type = :type AND id_module = :id_module");
    $stmt_check->execute(['type' => $type, 'id_module' => $id_module]);
    $exam_exists = $stmt_check->fetchColumn();

    if ($exam_exists > 0) {
        $error_message = "Ce type d'examen existe déjà pour ce module.";
        echo $error_message;
    } else {
        $stmt = $pdo->prepare("INSERT INTO exam (type, pourcentage, id_module, id_prof) VALUES (:type, :pourcentage, :id_module, :id_prof)");
        $stmt->execute(['type' => $type, 'pourcentage' => $pourcentage, 'id_module' => $id_module, 'id_prof' => $id_prof]);
        header("Location: exam.php?module_id=" . $id_module);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Examen</title>
    <link rel="stylesheet" href="../professeur/assets/ajouter_exam.css">
    <link rel="stylesheet" href="./assets/include/sidebarProf.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .form-container {
            max-width: 300px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-container h1 {
            font-size: 24px;
            color: #333;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: black;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.25);
        }

        .submit-button {
            width: 100%;
            padding: 10px 15px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-left: 0px;
        }

        .submit-button:hover {
            background-color: #0056b3;
        }

        .error-message {
            color: red;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>

<body>
    <?php
    include './assets/include/sidebarProf.php';
    ?>

    <div class="">
        <div class="form-container">
            <h1 style="text-align: center; color: #333;">Ajouter un Examen</h1>
            <?php
            if (isset($error_message)) {
                echo '<div class="error-message">' . $error_message . '</div>';
            }
            ?>
            <form action="" method="post">
                <input type="hidden" name="id_module" value="<?php echo $_GET['module_id']; ?>">
                <div class="form-group">
                    <label for="type">Type:</label>
                    <select id="type" name="type" required>
                        <option value="DS">DS</option>
                        <option value="DL">DL</option>
                        <option value="Exam">EXAM</option>
                        <option value="Projet">Projet</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="pourcentage">Pourcentage:</label>
                    <input type="text" id="pourcentage" name="pourcentage" required>
                </div>
                <button class="submit-button" type="submit">Ajouter</button>
            </form>
        </div>
    </div>
</body>

</html>