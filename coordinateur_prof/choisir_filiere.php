<?php
session_start();

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'coordinateur_prof') {
    header("Location: index.php");
    exit;
}
$coordinateur_id = $_SESSION['user_id'];
include '../include/database.php';
$stmt = $pdo->prepare("SELECT DISTINCT id,CONCAT(Nom_filiere, ' ', annee) AS Nom_filiere_annee FROM filiere WHERE id_coordinateur = :coordinateur_id");
$stmt->execute(['coordinateur_id' => $coordinateur_id]);
$filieres = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php

include '../include/nav_cote_corr.php';
?>
<script>
    var bodyDiv = document.querySelector('.bodyDiv');

    bodyDiv.innerHTML = `
         <div class="change">
         <form action="choisir_filiere.php" method="post">
    <label for="filiere">Choisir une filière :</label>
    <select name="filiere" id="filiere">
        <?php foreach ($filieres as $filiere) : ?>
            <option value="<?php echo $filiere['id']; ?>"><?php echo htmlspecialchars($filiere['Nom_filiere_annee']); ?></option>
        <?php endforeach; ?>
    </select>
    <button type="submit">Afficher la liste des étudiants</button>
</form>
         `;
</script>


</body>