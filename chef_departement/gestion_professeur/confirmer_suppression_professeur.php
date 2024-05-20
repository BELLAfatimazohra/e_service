<?php
session_start();
include "../../include/database.php";

$prof_id = isset($_GET['prof_id']) ? (int)$_GET['prof_id'] : 0;
$filiere_id = isset($_GET['filiere_id']) ? (int)$_GET['filiere_id'] : 0;

if ($prof_id > 0) {

    $sql = "SELECT nom, prenom FROM professeur WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$prof_id]);
    $professeur = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    $professeur = null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['confirm']) && $_POST['confirm'] === 'Oui') {

        $sql = "DELETE FROM professeur WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$prof_id]);


        header("Location: consulter_professeurs.php?filiere=$filiere_id");
        exit;
    } else {

        header("Location: consulter_professeurs.php?filiere=$filiere_id");
        exit;
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Confirmer la Suppression du Professeur</title>
</head>

<body>
    <h1>Confirmer la Suppression du Professeur</h1>
    <?php if ($professeur) : ?>
        <p>Êtes-vous sûr de vouloir supprimer le professeur <?php echo htmlspecialchars($professeur['prenom'] . ' ' . $professeur['nom']); ?> ?</p>
        <form method="post">
            <button type="submit" name="confirm" value="Oui">Oui</button>
            <button type="submit" name="confirm" value="Non">Non</button>
        </form>
    <?php else : ?>
        <p>Professeur introuvable.</p>
        <a href="consulter_professeurs.php?filiere=<?php echo $filiere_id; ?>">Retour à la liste des professeurs</a>
    <?php endif; ?>
</body>

</html>