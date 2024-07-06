<?php
session_start();
include "../../include/database.php";

// Get the module ID from the query string
$module_id = isset($_GET['module_id']) ? (int)$_GET['module_id'] : 0;

// Initialize variables
$module = [];
$professor = [];
$filiere = [];

if ($module_id > 0) {
    // Prepare SQL to fetch module details
    $sql = "
        SELECT m.nom_module, m.id_prof, f.Nom_filiere, f.annee
        FROM module m
        JOIN filiere f ON m.id_filiere = f.id
        WHERE m.id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$module_id]);
    $module = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($module) {
        // Fetch professor details
        $sql_prof = "SELECT CONCAT(nom, ' ', prenom) AS nom_prof FROM professeur WHERE id = ?";
        $stmt_prof = $pdo->prepare($sql_prof);
        $stmt_prof->execute([$module['id_prof']]);
        $professor = $stmt_prof->fetch(PDO::FETCH_ASSOC);
    }
} else {
    // Handle the case where module_id is not provided or invalid
    // Redirect or display an error message
    header("Location: some_error_page.php");
    exit;
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Modifier Module</title>
    <link rel="stylesheet" href="../include/sidbar_chef_dep.css">
    <style>

        .bodyDiv {
            width: 80%;
            max-width: 800px;
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

        .bodyDiv form {
            display: flex;
            flex-direction: column;
            width: 90%;
            margin: 30px;
            margin-bottom: 0;
        }

        .bodyDiv form div {
            margin-bottom: 30px;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            width: 100%;
        }

        .bodyDiv form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .bodyDiv form input[type="text"] {
            width: 50%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .bodyDiv form button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .bodyDiv form button:hover {
            background-color: #0056b3;
        }

        .success-message {
            color: green;
            font-weight: bold;
            margin-top: 20px;
            display: none;
        }

        .error-message {
            color: red;
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
        <h1>Modifier Module</h1>
        <form id="updateForm">
            <input type="hidden" name="module_id" value="<?php echo htmlspecialchars($module_id); ?>">
            <div>
                <label for="nom_module">Nom Module:</label>
                <input type="text" id="nom_module" name="nom_module" value="<?php echo htmlspecialchars($module['nom_module']); ?>" required>
            </div>
            <div>
                <label for="nom_prof">Nom Professeur:</label>
                <input type="text" id="nom_prof" name="nom_prof" value="<?php echo htmlspecialchars($professor['nom_prof']); ?>" readonly>
            </div>
            <div>
                <label for="nom_filiere">Nom Filière:</label>
                <input type="text" id="nom_filiere" name="nom_filiere" value="<?php echo htmlspecialchars($module['Nom_filiere']); ?>" readonly>
            </div>
            <div>
                <label for="annee">Année:</label>
                <input type="text" id="annee" name="annee" value="<?php echo htmlspecialchars($module['annee']); ?>" readonly>
            </div>
            <div>
                <button type="submit">Mettre à jour</button>
            </div>
            <div class="success-message">Le module a été mis à jour avec succès !</div>
            <div class="error-message">Une erreur est survenue lors de la mise à jour du module.</div>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            $('.success-message').hide();
            $('.error-message').hide();
            $('#updateForm').on('submit', function(event) {
                event.preventDefault();

                $.ajax({
                    url: 'update_module.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $('.success-message').show();
                            $('.error-message').hide();
                        } else {
                            $('.error-message').text(response.error).show();
                            $('.success-message').hide();
                        }
                    },
                    error: function() {
                        $('.error-message').text('Une erreur est survenue lors de la mise à jour du module.').show();
                        $('.success-message').hide();
                    }
                });
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