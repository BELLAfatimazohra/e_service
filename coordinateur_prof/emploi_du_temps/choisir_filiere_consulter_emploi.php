<?php
session_start();
$_SESSION['user_type'] = 'coordinateur_prof';

// Vérifier si l'utilisateur est connecté en tant que coordinateur
if (isset($_SESSION['user_id']) && isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'coordinateur_prof') {
    include '../../include/nav_cote_corr.php';
} else {
    // Rediriger vers la page d'index si l'utilisateur n'est pas connecté ou s'il n'est pas un coordinateur
    header("Location: index.php");
    exit;
}

require_once '../../include/database.php';

// Récupérer l'identifiant du coordinateur connecté
$coordinateurId = $_SESSION['user_id'];

// Requête SQL pour sélectionner les filières du coordinateur avec le nom concaténé avec l'année
$sql = "SELECT f.id, CONCAT(f.Nom_filiere, ' ', f.annee) AS NomAnnee
        FROM filiere AS f
        JOIN coordinateur AS c ON f.id_coordinateur = c.id
        WHERE c.id_prof = :coordinateurId";


$stmt = $pdo->prepare($sql);
$stmt->bindParam(':coordinateurId', $coordinateurId, PDO::PARAM_INT);
$stmt->execute();
$filiereRows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emploi du Temps</title>
    <style>
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 30px;
        }

        h1 {
            color: #333;
            font-size: 24px;
            margin-bottom: 20px;
        }

        form {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .btn-create {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }

        .btn-create:hover {
            background-color: #0056b3;
        }

        .error-message {
            color: #ff0000;
            font-size: 14px;
            margin-top: 5px;
        }

        .success-message {
            color: #00cc00;
            font-size: 14px;
            margin-top: 5px;
        }
    </style>
</head>

<body>
    <script>
        var bodyDiv = document.querySelector('.bodyDiv');

        bodyDiv.innerHTML = `
    <form action="consulter_emploi_temps.php" method="get">
        <label for="filiere_id">Choisir une filière :</label>
        <select name="filiere_id" id="filiere_id">
            <?php foreach ($filiereRows as $filiere) : ?>
                <option value="<?php echo $filiere['id']; ?>"><?php echo $filiere['NomAnnee']; ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Consulter l'emploi du temps</button>
    </form>
    `;
    </script>
</body>

</html>