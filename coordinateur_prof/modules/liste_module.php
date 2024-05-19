<?php
session_start();

// Vérifiez si l'utilisateur est connecté et a le bon type d'utilisateur
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'coordinateur_prof') {
    header("Location: index.php");
    exit;
}

// Vérifiez si l'ID de la filière est passé dans l'URL
if (isset($_GET['filiere'])) {
    $filiere_id = $_GET['filiere'];
    include '../../include/database.php';

    $stmt = $pdo->prepare("SELECT Nom_filiere, annee FROM filiere WHERE id = :filiere_id");
    $stmt->execute(['filiere_id' => $filiere_id]);
    $filiere_info = $stmt->fetch(PDO::FETCH_ASSOC);
    // Préparez et exécutez la requête SQL pour obtenir les étudiants de cette filière
    $stmt = $pdo->prepare("SELECT * FROM module WHERE id_filiere = :filiere_id");
    $stmt->execute(['filiere_id' => $filiere_id]);
    $etudiants = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Si aucun ID de filière n'est fourni, redirigez vers une page d'erreur ou la page principale
    header("Location: error.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Liste des Modules pour ce filiere </title>
    <link rel="stylesheet" href="../include/sidebarCoor.css">
    <style>
        h1 {
            color: #333;
            text-align: center;
            margin-top: 20px;
        }

        .table-container {
            margin: 20px auto;
            width: 80%;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            color: #333;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .sidebar {
            width: 200px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            background-color: #333;
            padding-top: 20px;
        }

        .sidebar a {
            padding: 15px;
            text-decoration: none;
            font-size: 18px;
            color: white;
            display: block;
        }

        .sidebar a:hover {
            background-color: #575757;
        }

        .active {
            background-color: #04AA6D;
        }

        .bodyDiv {
            margin-left: 220px;
            /* Adjust this value based on your sidebar width */
            padding: 20px;
        }
    </style>
</head>

<body>
    <?php
    include '../include/sidebarCoor.php';

    ?>
    <div class="bodyDiv">
        <div class="">
            <h1>Liste des Modules de la Filière <?php echo htmlspecialchars($filiere_info['Nom_filiere']) . " " . htmlspecialchars($filiere_info['annee']); ?></h1>
            <table border="2">

                <?php foreach ($etudiants as $etudiant) : ?>
                    <tr>
                        <td><?= htmlspecialchars($etudiant['Nom_module']) ?></td>


                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>

    <script>
        document.querySelectorAll("li").forEach(function(li) {
            if (li.classList.contains("active")) {
                li.classList.remove("active");
            }
        });

        document.querySelector(".liModules").classList.add("active");
    </script>

</body>

</html>