<?php
session_start();
include "../../include/database.php";

$filiere_id = isset($_GET['filiere']) ? (int)$_GET['filiere'] : 0;

if ($filiere_id > 0) {
    // Prepare SQL to fetch module names and IDs
    $sql = "SELECT id, nom_module FROM module WHERE id_filiere = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$filiere_id]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $results = [];
}

// Prepare SQL to fetch filiere details
$sql = "SELECT Nom_filiere, annee FROM filiere WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$filiere_id]);
$filiere = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>

<head>
    <title>Liste des Modules</title>
    <link rel="stylesheet" href="../include/sidbar_chef_dep.css">
    <style>
        .bodyDiv {
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
            display: flex;
            justify-content: space-between;
            align-items: center;
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

        .success-message {
            color: green;
            font-weight: bold;
            margin-top: 20px;
            display: none;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>

    <?php include "../include/sidebar_chef_dep.php"; ?>

    <div class="bodyDiv">
        <h1>Liste des Modules pour la filière <?php echo htmlspecialchars($filiere['Nom_filiere']) . " " . htmlspecialchars($filiere['annee']); ?></h1>
        <?php if (!empty($results)) : ?>
            <ul>
                <?php foreach ($results as $result) : ?>
                    <li>
                        <strong><?php echo htmlspecialchars($result['nom_module']); ?></strong>
                        <div>
                            <button>
                                <a href="modifier_module.php?module_id=<?php echo $result['id']; ?>">Modifier</a>
                            </button>
                            <button class="delete-button" data-module-id="<?php echo $result['id']; ?>" data-module-name="<?php echo htmlspecialchars($result['nom_module']); ?>">Supprimer</button>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else : ?>
            <p>Aucun module trouvé pour cette filière.</p>
        <?php endif; ?>
        <button>
            <a href="ajouter_module.php?filiere_id=<?php echo $filiere_id; ?>">Ajouter un module</a>
        </button>
        <div class="success-message"></div>
    </div>

    <script>
        $(document).ready(function() {
            $('.delete-button').on('click', function() {
                var moduleId = $(this).data('module-id');
                var moduleName = $(this).data('module-name');
                var $this = $(this);

                if (confirm('Are you sure you want to delete the module "' + moduleName + '"?')) {
                    $.ajax({
                        url: 'supprimer_module.php',
                        type: 'POST',
                        data: { module_id: moduleId },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                $this.closest('li').remove();
                                $('.success-message').text('Module "' + moduleName + '" deleted successfully.').show();
                            } else {
                                alert('Une erreur est survenue lors de la suppression du module.');
                            }
                        },
                        error: function() {
                            alert('Une erreur est survenue lors de la suppression du module.');
                        }
                    });
                }
            });
        });
    </script>

    <script>
        document.querySelectorAll("li").forEach(function(li) {
            if (li.classList.contains("active")) {
                li.classList.remove("active");
            }
        });

        document.querySelector(".liModules").classList.add("active");
    </script>
</body>

</html>
