<?php
session_start();
if (!isset($_SESSION['user_id']) ||  ($_SESSION['user_type'] !== 'professeur' && $_SESSION['user_type'] !== 'chef_departement' &&  $_SESSION['user_type'] !== 'coordinateur_prof')) {
    header("Location: login.php");
    exit;
}
require_once '../../include/database.php';
$stmt = $pdo->prepare("SELECT m.*,CONCAT(f.Nom_filiere, ' ', f.annee)AS filiere_annee  FROM message_prof m
                      JOIN filiere f ON m.id_filiere = f.id
                      WHERE m.id_prof = ?");
$stmt->execute([$_SESSION['user_id']]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages envoyés</title>
    <link rel="stylesheet" href="../assets/include/sidebarProf.css">
    <link rel="stylesheet" href="../assets/">

    <style>
        h1 {
            text-align: center;
            margin-top: 20px;
            color: #333;
            margin-bottom: 50px;
        }

        table {
            width: 92%;
            margin: 20px auto;
            border-collapse: collapse;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        th,
        td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: var(--nav-bg);
            color: #fff;

        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        .Div {
            padding: 20px 0;
            margin: 40px auto;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px #999999;
            text-align: center;
            width: 90%;
        }
    </style>
</head>

<body>
    <?php
    include '../assets/include/sidebarProf.php';

    ?>
    <div class="bodyDiv">
        <div class="Div">
            <h1>Messages Envoyés</h1>
            <table>
                <tr>
                    <th>Filière</th>
                    <th>Date</th>
                    <th>Titre</th>
                    <th>Message</th>
                </tr>
                <?php foreach ($messages as $message) : ?>
                    <tr>
                        <td><?php echo $message['filiere_annee']; ?></td>
                        <td><?php echo $message['date_message']; ?></td>
                        <td><?php echo $message['titre']; ?></td>
                        <td><?php echo $message['message']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>

    </div>

</body>

</html>