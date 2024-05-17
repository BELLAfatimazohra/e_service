<?php
session_start();

// Vérifier si l'utilisateur est connecté en tant que coordinateur
if (isset($_SESSION['user_id']) && isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'coordinateur_prof') {
require_once '../../include/database.php';
    include '../include/sidebarCoor.php';
} else {
    // Rediriger vers la page d'index si l'utilisateur n'est pas connecté ou s'il n'est pas un coordinateur
    header("Location: index.php");
    exit;
}


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
    <link rel="stylesheet" href="../include/sidebarCoor.css">
    <title>Emploi du Temps</title>
    <style>
        .bodyDiv {
            padding: 80px;
            max-width: 800px;
            margin: 40px auto;
            margin-top: 100px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .bodyDiv form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .bodyDiv label {
            font-size: 18px;
            margin-bottom: 10px;
            color: #333;
        }
        .bodyDiv select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        .bodyDiv button {
            padding: 10px 20px;
            background-color: #007bff;
            border: none;
            color: white;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .bodyDiv button:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }
    </style>
</head>

<body>
    <div class="bodyDiv">
        <form action="traitement_creer_emploi_temps.php" method="get">
            <label for="filiere_id">Choisir une filière :</label>
            <select name="filiere_id" id="filiere_id">
                <?php foreach ($filiereRows as $filiere) : ?>
                    <option value="<?php echo $filiere['id']; ?>"><?php echo $filiere['NomAnnee']; ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Créer l'emploi du temps</button>
        </form>
    </div>

    <script>

document.querySelectorAll("li").forEach(function(li) {
    if(li.classList.contains("active")){
        li.classList.remove("active");
    }
});

document.querySelector(".liEmp").classList.add("active");

</script>
</body>

</html>