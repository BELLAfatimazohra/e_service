<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'professeur') {
    header("Location: login.php");
    exit;
}
require_once '../include/database.php';
$stmt = $pdo->prepare("SELECT m.*, f.Nom_filiere FROM message_prof m
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
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            margin-top: 20px;
            color: #333;
        }

        table {
            width: 80%;
            margin: 20px auto;
          border-collapse: collapse;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

       
    </style>
</head>
<body>
<?php
 include '../include/nav_cote.php'; 

?>
<script>
       
        var bodyDiv = document.querySelector('.body');
        
        
        bodyDiv.innerHTML = `
        <h1>Messages envoyés</h1>
    <table>
        <tr>
            <th>Filière</th>
            <th>Date</th>
            <th>Titre</th>
            <th>Message</th>
        </tr>
        <?php foreach ($messages as $message): ?>
        <tr>
            <td><?php echo $message['Nom_filiere']; ?></td>
            <td><?php echo $message['date_message']; ?></td>
            <td><?php echo $message['titre']; ?></td>
            <td><?php echo $message['message']; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
        `;
    </script>
    
</body>
</html>
