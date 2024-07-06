<?php
session_start();

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'coordinateur_prof') {
    header("Location: index.php");
    exit;
}

$coordinateur_id = $_SESSION['user_id'];

include '../../include/database.php';
$stmt = $pdo->prepare("SELECT DISTINCT id, CONCAT(Nom_filiere, ' ', annee) AS Nom_filiere_annee FROM filiere WHERE id_coordinateur = :coordinateur_id");
$stmt->execute(['coordinateur_id' => $coordinateur_id]);
$filieres = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['filiere'])) {
    $id_filiere = $_GET['filiere'];
    $stmt_modules = $pdo->prepare("SELECT id, Nom_module FROM module WHERE id_filiere = :id_filiere");
    $stmt_modules->execute(['id_filiere' => $id_filiere]);
    $modules = $stmt_modules->fetchAll(PDO::FETCH_ASSOC);
    $stmt_professeurs = $pdo->prepare("SELECT id, Nom, Prenom FROM professeur WHERE id_filiere = 8"); // Assuming professors of filiere 8
    $stmt_professeurs->execute();
    $professeurs = $stmt_professeurs->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Affectation des modules aux professeurs</title>
    <link rel="stylesheet" href="../include/sidebarCoor.css">
    <style>
        .container {
            max-width: 800px;
            min-height: 200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        label {
            margin-bottom: 10px;
        }

        select {
            padding: 8px;
            margin-bottom: 10px;
            width: 100%;
        }

        button.enregistrer {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button.enregistrer:hover {
            background-color: #0056b3;
        }

        .message {
            margin-top: 10px;
            padding: 10px;
            border-radius: 5px;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>

<body>
    <?php
    include '../include/sidebarCoor.php';
    ?>

    <div class="bodyDiv">
        <div class="container">
            <h2>Affectation des modules aux professeurs</h2><br>

            <form id="affectationForm">
                <?php if (!empty($professeurs) && !empty($modules)) { ?>
                    <?php foreach ($modules as $module) : ?>
                        <label for="prof_<?php echo $module['id']; ?>">Choisir un professeur pour <?php echo $module['Nom_module']; ?>:</label>
                        <select name="prof_<?php echo $module['id']; ?>" id="prof_<?php echo $module['id']; ?>">
                            <option value="">Sélectionner un professeur</option>
                            <?php foreach ($professeurs as $professeur) : ?>
                                <option value="<?php echo $professeur['id']; ?>">
                                    <?php echo $professeur['Nom'] . ' ' . $professeur['Prenom']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select><br>
                    <?php endforeach; ?>
                    <button class="enregistrer" type="submit">Enregistrer l'affectation</button>
                <?php } else { ?>
                    <span>Aucune donnée trouvée</span>
                <?php } ?>
            </form>
            <div id="message" class="message" style="display:none;"></div>
        </div>
    </div>

    <script>
        document.querySelectorAll("li").forEach(function(li) {
            if (li.classList.contains("active")) {
                li.classList.remove("active");
            }
        });

        document.querySelector(".liModules").classList.add("active");

        document.getElementById('affectationForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'enregistrer_affectation.php', true);

            xhr.onload = function() {
                const messageDiv = document.getElementById('message');
                if (xhr.status === 200) {
                    messageDiv.className = 'message success';
                    messageDiv.textContent = 'Affectation enregistrée avec succès!';
                } else {
                    messageDiv.className = 'message error';
                    messageDiv.textContent = 'Une erreur est survenue. Veuillez réessayer.';
                }
                messageDiv.style.display = 'block';
            };

            xhr.send(formData);
        });
    </script>
</body>

</html>
