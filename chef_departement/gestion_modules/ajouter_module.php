<?php
session_start();
include "../../include/database.php";

// Retrieve filiere_id from the query string and ensure it is an integer
$filiere_id = isset($_GET['filiere_id']) ? (int)$_GET['filiere_id'] : 0;

// Prepare to fetch professors associated with the filiere_id
$professors = [];
try {

    $query = "SELECT id, nom, prenom FROM professeur WHERE id_filiere = :filiere_id OR id_filiere_2 = :filiere_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['filiere_id' => $filiere_id]);

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $professors[] = $row;
    }
} catch (PDOException $e) {
    die("Could not connect to the database:" . $e->getMessage());
}
?><!DOCTYPE html>
<html>
<head>
    <title>Ajouter Module</title>
    <link rel="stylesheet" href="../include/sidbar_chef_dep.css">
    <style>

        .bodyDiv {
            width: 70%;
            margin-top: 7rem;
            padding: 60px 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .bodyDiv h1 {
            text-align: center;
            color: #007bff;
        }

        .bodyDiv form {
            max-width: 400px;
            margin: 0 auto;
        }

        .bodyDiv form div {
            margin-bottom: 20px;
        }

        .bodyDiv form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .bodyDiv form input[type="text"],
        .bodyDiv form select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .bodyDiv form button {
            width: 100%;
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

        .success-message,
        .error-message {
            display: none;
            font-weight: bold;
            margin-top: 20px;
        }

        .success-message {
            color: green;
        }

        .error-message {
            color: red;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<?php include "../include/sidebar_chef_dep.php"; ?>

<div class="bodyDiv">
    <h1>Ajouter Module</h1>
    <form id="addModuleForm">
        <div>
            <input type="hidden" id="id_filiere" name="id_filiere" value="<?php echo $filiere_id; ?>">
        </div>
        <div>
            <label for="nom_module">Nom Module:</label>
            <input type="text" id="nom_module" name="nom_module" required>
        </div>
        <div>
            <label for="id_prof">Professeur:</label>
            <select id="id_prof" name="id_prof" required>
                <option value="">Sélectionner un professeur</option>
                <?php foreach ($professors as $prof) { ?>
                    <option value="<?php echo $prof['id']; ?>">
                        <?php echo htmlspecialchars($prof['nom'] . " " . $prof['prenom']); ?>
                    </option>
                <?php } ?>
            </select>
        </div>
        <button type="submit">Ajouter Module</button>
        <div class="success-message">Module ajouté avec succès!</div>
        <div class="error-message">Une erreur est survenue lors de l'ajout du module.</div>
    </form>
</div>

<script>
$(document).ready(function() {
    // AJAX form submission
    $('#addModuleForm').on('submit', function(event) {
        event.preventDefault(); // Prevent form submission

        // Debugging line: Log data to console to verify it before sending
        console.log($(this).serialize());

        // AJAX request
        $.ajax({
            url: 'insert_module.php',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                console.log('Success:', response);
                if (response.success) {
                    $('.success-message').show();
                    $('.error-message').hide();
                    $('#addModuleForm')[0].reset();
                } else {
                    $('.error-message').text(response.error || 'Failed without error message').show();
                    $('.success-message').hide();
                }
            },
            error: function(xhr, status, error) {
                console.log('AJAX Error:', xhr, status, error);
                $('.error-message').text('Une erreur est survenue lors de la requête AJAX.').show();
                $('.success-message').hide();
            }
        });
    });
});
</script>

<script>
    // Activate sidebar link
    $(document).ready(function() {
        document.querySelectorAll("li").forEach(function(li) {
            if (li.classList.contains("active")) {
                li.classList.remove("active");
            }
        });

        document.querySelector(".liModules").classList.add("active");
    });
</script>

</body>
</html>
