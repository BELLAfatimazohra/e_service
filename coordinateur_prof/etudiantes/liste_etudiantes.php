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
    // Préparez et exécutez la requête SQL pour obtenir les étudiants de cette filière
    $stmt = $pdo->prepare("SELECT * FROM etudiant WHERE id_filiere = :filiere_id");
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
    <title>Liste des Étudiants</title>
    <link rel="stylesheet" href="../include/sidebarCoor.css">
</head>

<body>
    <?php
    include '../include/sidebarCoor.php';

    ?>
    <script>
        var bodyDiv = document.querySelector('.bodyDiv');


        bodyDiv.innerHTML = `
        <h1>Liste des Étudiants de la Filière</h1>
    <table border="2">
        <tr>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Email</th>
           
        </tr>
        <?php foreach ($etudiants as $etudiant) : ?>
            <tr>
                <td><?= htmlspecialchars($etudiant['Nom']) ?></td>
                <td><?= htmlspecialchars($etudiant['Prenom']) ?></td>
                <td><?= htmlspecialchars($etudiant['Email']) ?></td>
                
            </tr>
        <?php endforeach; ?>
    </table>
        `;
    </script>
    <script>

document.querySelectorAll("li").forEach(function(li) {
    if(li.classList.contains("active")){
        li.classList.remove("active");
    }
});

document.querySelector(".liEtudiants").classList.add("active");

</script>
</body>

</html>