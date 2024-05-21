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
    <style>
        .bodyDiv {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 600px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            margin-bottom: 10px;
        }

        .prof-name {
            font-weight: bold;
        }

        .module-list {
            margin-left: 20px;
        }

        .module-list li {
            margin-bottom: 5px;
        }

        .button {
            display: inline-block;

            padding: 10px 15px;

            color: black;
            text-align: center;
            text-decoration: none;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>

</head>

<body>
    <div class="bodyDiv">
        <h1>Liste des Professeurs et Modules pour la filière choisie</h1>
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
</body>

</html>