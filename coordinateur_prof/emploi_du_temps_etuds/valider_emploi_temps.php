


<?php
session_start();
$_SESSION['user_type'] = 'coordinateur_prof';
// Vérifier si l'utilisateur est connecté en tant que coordinateur
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'coordinateur_prof') {
    // Rediriger vers la page d'index si l'utilisateur n'est pas connecté ou s'il n'est pas un coordinateur
    header("Location: index.php");
    exit;
}
require_once '../../include/database.php';
// Vérifier si les données POST ont été envoyées depuis le formulaire
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Récupérer les données du formulaire
    $module_ids = $_GET['module'];
    $prof_ids = $_GET['prof'];
    $blocs = $_GET['bloc'];
    $type_cours = $_GET['type_cours'];
    $nom_salles = $_GET['salle'];

    // Vérifier si l'identifiant de la filière est défini dans l'URL
    if (!isset($_GET['filiere_id'])) {
        // Rediriger vers une page d'erreur si le paramètre 'id' est manquant
        header("Location: erreur.php");
        exit;
    }

    $filiere_id = $_GET['filiere_id'];

    // Requête SQL pour récupérer le nom de la filière
    $sql_filiere_name = "SELECT Nom_filiere ,annee FROM filiere WHERE id = :filiere_id";
    $stmt_filiere_name = $pdo->prepare($sql_filiere_name);
    $stmt_filiere_name->bindParam(':filiere_id', $filiere_id, PDO::PARAM_INT);
    $stmt_filiere_name->execute();
    $filiere = $stmt_filiere_name->fetch(PDO::FETCH_ASSOC);

    $nom_table = 'emploi_temps_' . strtolower(str_replace(' ', '_', $filiere['Nom_filiere'])) . '_' . $filiere['annee'];

    // Vérifier si la table existe déjà dans la base de données
    $sql_check_table = "SHOW TABLES LIKE :table_name";
    $stmt_check_table = $pdo->prepare($sql_check_table);
    $stmt_check_table->bindParam(':table_name', $nom_table, PDO::PARAM_STR);
    $stmt_check_table->execute();
    $table_exists = $stmt_check_table->fetchColumn();

    if ($table_exists) {
        // Afficher un message si la table existe déjà
        echo "L'emploi du temps pour cette filière a déjà été créé. Vous pouvez le consulter.";
    } else {
        // Créer la table s'il n'existe pas déjà
        $sql_create_table = "CREATE TABLE IF NOT EXISTS $nom_table (
            id INT AUTO_INCREMENT PRIMARY KEY,
            Nom_prof VARCHAR(255),
            nom_jour VARCHAR(255),
            Nom_salle VARCHAR(255),
            temps VARCHAR(255),
            type_sceance VARCHAR(255),
            bloc VARCHAR(255),
            id_filiere INT,
            Nom_module VARCHAR(255)
        )";
        $stmt_create_table = $pdo->prepare($sql_create_table);
        $stmt_create_table->execute();
        $jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
        $heures = ['08:00 - 10:00', '10:00 - 12:00', '14:00 - 16:00', '16:00 - 18:00'];

        // Assuming we have a correct array structure

        foreach ($jours as $jour_index => $jour) {
            foreach ($heures as $heure_index => $heure) {
                $array_index = $jour_index * count($heures) + $heure_index; // Calculate the index in the flat list
                if (isset($module_ids[$array_index])) {
                    $module_id = $module_ids[$array_index];
                    $prof_id = $prof_ids[$array_index];
                    $bloc = $blocs[$array_index];
                    $type_cour = $type_cours[$array_index];
                    $nom_salle = $nom_salles[$array_index];

                    // Fetch module name
                    $sql_module_name = "SELECT Nom_module FROM module WHERE id = :module_id";
                    $stmt_module_name = $pdo->prepare($sql_module_name);
                    $stmt_module_name->bindParam(':module_id', $module_id, PDO::PARAM_INT);
                    $stmt_module_name->execute();
                    $module_name = $stmt_module_name->fetchColumn();

                    // Fetch professor name
                    $sql_prof_name = "SELECT CONCAT(Nom, ' ', Prenom) AS Nom_prof FROM professeur WHERE id = :prof_id";
                    $stmt_prof_name = $pdo->prepare($sql_prof_name);
                    $stmt_prof_name->bindParam(':prof_id', $prof_id, PDO::PARAM_INT);
                    $stmt_prof_name->execute();
                    $prof_name = $stmt_prof_name->fetchColumn();

                    // Insert into the timetable table
                    $sql_insert = "INSERT INTO $nom_table (Nom_prof, nom_jour, Nom_salle, temps, type_sceance, bloc, id_filiere, Nom_module) VALUES (:Nom_prof, :nom_jour, :Nom_salle, :temps, :type_sceance, :bloc, :id_filiere, :Nom_module)";
                    $stmt_insert = $pdo->prepare($sql_insert);
                    $stmt_insert->execute([
                        ':Nom_prof' => $prof_name,
                        ':nom_jour' => $jour,
                        ':Nom_salle' => $nom_salle,
                        ':temps' => $heure,
                        ':type_sceance' => $type_cour,
                        ':bloc' => $bloc,
                        ':id_filiere' => $filiere_id,
                        ':Nom_module' => $module_name
                    ]);
                }
            }
        }
    }
    // Rediriger vers une page de succès ou une autre page après l'insertion
    header("Location: emploi_du_temps.php");
    exit;
} else {
    // Rediriger vers une page d'erreur si les données POST ne sont pas envoyées
    header("Location: erreur.php");
    exit;
}
?>

