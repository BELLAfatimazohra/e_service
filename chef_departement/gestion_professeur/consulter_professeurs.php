<?php
session_start();

include "../../include/database.php";
$filiere_id = isset($_GET['filiere']) ? (int)$_GET['filiere'] : 0;

if ($filiere_id > 0) {

    $sql = "SELECT p.id AS prof_id, p.nom AS prof_nom, p.prenom AS prof_prenom, m.id AS module_id, m.Nom_module 
            FROM professeur p
            LEFT JOIN module m ON p.id = m.id_prof
            WHERE p.id_filiere = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$filiere_id]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $results = [];
}
$sql = "SELECT Nom_filiere ,annee FROM filiere WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$filiere_id]);
$filiere = $stmt->fetch(PDO::FETCH_ASSOC);
$professeurs = [];
foreach ($results as $row) {
    $prof_id = $row['prof_id'];
    if (!isset($professeurs[$prof_id])) {
        $professeurs[$prof_id] = [
            'nom' => $row['prof_nom'],
            'prenom' => $row['prof_prenom'],
            'modules' => []
        ];
    }
    if ($row['module_id']) {
        $professeurs[$prof_id]['modules'][] = $row['Nom_module'];
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Liste des Professeurs et Modules</title>
    <link rel="stylesheet" href="../include/sidbar_chef_dep.css">
    <style>

        .bodyDiv {
            padding-top: 500px;
            width: 80%;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .bodyDiv h1 {
            text-align: center;
            color: #007bff;
            margin-bottom: 20px;
        }

        .bodyDiv ul {
            list-style-type: none;
            padding: 0;
        }

        .bodyDiv ul>li {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .bodyDiv ul>li strong {
            font-size: 1.2em;
            color: #555;
        }

        .bodyDiv ul ul {
            list-style-type: disc;
            margin-left: 20px;
            padding-left: 20px;
        }

        .bodyDiv ul ul li {
            font-size: 0.9em;
            color: #777;
        }

        .bodyDiv p {
            color: #999;
            text-align: center;
            margin: 20px 0;
        }

        .bodyDiv button {
            display: inline-block;
            margin: 10px 5px;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .bodyDiv button:hover {
            background-color: #0056b3;

        }

        .bodyDiv button a {
            color: #fff;
            text-decoration: none;
        }
    </style>

</head>

<body>

    <?php
    include "../include/sidebar_chef_dep.php";

    ?>
    <div class="bodyDiv">
        <h1>Liste des Professeurs et Modules pour la filière <?php echo $filiere['Nom_filiere'] . "_" . $filiere['annee'] ?></h1>
        <?php if (!empty($professeurs)) : ?>
            <ul>
                <?php foreach ($professeurs as $professeur) : ?>
                    <li>
                        <strong><?php echo htmlspecialchars($professeur['prenom'] . ' ' . $professeur['nom']); ?></strong>
                        <?php if (!empty($professeur['modules'])) : ?>
                            <ul>
                                <?php foreach ($professeur['modules'] as $module) : ?>
                                    <li><?php echo htmlspecialchars($module); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else : ?>
                            <p>Aucun module trouvé pour ce professeur.</p>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
                <button> <a href="modifier_professeur.php?filiere_id=<?php echo $filiere_id; ?>" class="button">Modifier un professeur</a></button>
                <button> <a href="supprimer_professeur.php?filiere_id=<?php echo $filiere_id; ?>" class="button">Supprimer un professeur</a></button>
            </ul>
        <?php else : ?>
            <p>Aucun professeur trouvé pour cette filière.</p>
        <?php endif; ?>
        <button> <a href="ajouter_professeur.php?filiere_id=<?php echo $filiere_id; ?>" class="button">Ajouter un professeur</a></button>
    </div>
    
    <script>
        document.querySelectorAll("li").forEach(function(li) {
            li.classList.remove("active");
        });

        document.querySelector(".liProf").classList.add("active");
    </script>
</body>

</html>