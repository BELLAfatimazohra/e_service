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
require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Déclaration des jours et des heures
$jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
$heures = ['08:00 - 10:00', '10:00 - 12:00', '14:00 - 16:00', '16:00 - 18:00'];

// Activation du rapport d'erreurs PDO
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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

    // Récupérer le nom de la filière
    $sql_filiere_name = "SELECT Nom_filiere, annee FROM filiere WHERE id = :filiere_id";
    $stmt_filiere_name = $pdo->prepare($sql_filiere_name);
    $stmt_filiere_name->bindParam(':filiere_id', $filiere_id, PDO::PARAM_INT);
    $stmt_filiere_name->execute();
    $filiere = $stmt_filiere_name->fetch(PDO::FETCH_ASSOC);

    $nom_table = 'emploi_temps_' . strtolower(str_replace(' ', '_', $filiere['Nom_filiere'])) . '_' . $filiere['annee'];

    // Vérifier si la table existe dans la base de données
    $sql_check_table = "SHOW TABLES LIKE :table_name";
    $stmt_check_table = $pdo->prepare($sql_check_table);
    $stmt_check_table->bindParam(':table_name', $nom_table, PDO::PARAM_STR);
    $stmt_check_table->execute();
    $table_exists = $stmt_check_table->fetchColumn();

    if (!$table_exists) {
        echo "L'emploi du temps pour cette filière n'a pas encore été créé.";
        exit;
    }

    // Parcourir les jours et les heures pour mettre à jour l'emploi du temps
    foreach ($jours as $jour) {
        foreach ($heures as $heure) {
            $array_index = array_search($jour, $jours) * count($heures) + array_search($heure, $heures);
            if (isset($module_ids[$array_index])) {
                $module_id = $module_ids[$array_index];
                $prof_id = $prof_ids[$array_index];
                $bloc = $blocs[$array_index];
                $type_cour = $type_cours[$array_index];
                $nom_salle = $nom_salles[$array_index];

                // Récupérer le nom du module
                $sql_module_name = "SELECT Nom_module FROM module WHERE id = :module_id";
                $stmt_module_name = $pdo->prepare($sql_module_name);
                $stmt_module_name->bindParam(':module_id', $module_id, PDO::PARAM_INT);
                $stmt_module_name->execute();
                $module_name = $stmt_module_name->fetchColumn();

                // Récupérer le nom du professeur
                $sql_prof_name = "SELECT CONCAT(Nom, ' ', Prenom) AS Nom_prof FROM professeur WHERE id = :prof_id";
                $stmt_prof_name = $pdo->prepare($sql_prof_name);
                $stmt_prof_name->bindParam(':prof_id', $prof_id, PDO::PARAM_INT);
                $stmt_prof_name->execute();
                $prof_name = $stmt_prof_name->fetchColumn();

                // Effectuer la mise à jour dans la table de l'emploi du temps
                $sql_update = "UPDATE $nom_table SET Nom_module = :module_name, Nom_prof = :prof_name, Nom_salle = :nom_salle, bloc = :bloc, type_sceance = :type_cour WHERE nom_jour = :jour AND temps = :heure";
                $stmt_update = $pdo->prepare($sql_update);
                $stmt_update->bindParam(':module_name', $module_name, PDO::PARAM_STR);
                $stmt_update->bindParam(':prof_name', $prof_name, PDO::PARAM_STR);
                $stmt_update->bindParam(':nom_salle', $nom_salle, PDO::PARAM_STR);
                $stmt_update->bindParam(':bloc', $bloc, PDO::PARAM_STR);
                $stmt_update->bindParam(':type_cour', $type_cour, PDO::PARAM_STR);
                $stmt_update->bindParam(':jour', $jour, PDO::PARAM_STR);
                $stmt_update->bindParam(':heure', $heure, PDO::PARAM_STR);
                $stmt_update->execute();
            }
        }
    }






    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("SELECT id FROM filiere WHERE Nom_filiere = :nom_filiere AND annee = :annee");
        $stmt->execute(['nom_filiere' => $filiere['Nom_filiere'], 'annee' => $filiere['annee']]);
        $filiere = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$filiere) {
            throw new Exception("Filière introuvable.");
        }

        $id_filiere = $filiere['id'];
        $stmt = $pdo->prepare("SELECT Email FROM etudiant WHERE id_filiere = :id_filiere");
        $stmt->execute(['id_filiere' => $id_filiere]);
        $emails = $stmt->fetchAll(PDO::FETCH_COLUMN);

        if (!$emails) {
            throw new Exception("Aucun étudiant trouvé pour cette filière.");
        }
        // Avant d'utiliser les informations du coordinateur, vous devez les extraire de la base de données en utilisant l'ID du coordinateur connecté

        $stmt_coord = $pdo->prepare("SELECT Nom, Prenom, Email FROM coordinateur WHERE id = :id");
        $stmt_coord->execute(['id' => $_SESSION['user_id']]);
        $coordinateur = $stmt_coord->fetch(PDO::FETCH_ASSOC);

        if (!$coordinateur) {
            throw new Exception("Informations du coordinateur introuvables.");
        }


        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'bellafatimazahrae@gmail.com'; // il faut crerr un compte et fait le adresse email de il dans ca 
        $mail->Password = 'udrt vdly tvcs auim';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Maintenant, vous pouvez utiliser les informations extraites pour définir les informations de l'expéditeur et de la réponse
        $mail->setFrom($coordinateur['Email'], $coordinateur['Nom'] . ' ' . $coordinateur['Prenom']);
        $mail->addReplyTo($coordinateur['Email'], $coordinateur['Nom'] . ' ' . $coordinateur['Prenom']);

        foreach ($emails as $email) {
            $mail->addAddress($email);
        }
        $subject = "Modification de l'emploi du temps";
        $message = "Cher étudiant,<br><br>L'emploi du temps de votre filière a été modifié.<br>Veuillez vérifier les changements sur la plateforme E_service.<br><br>Cordialement,<br>ENSA EL HOCEIMA ";

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;
        $mail->send();



        $pdo->commit();

        echo "Message envoyé et enregistré avec succès.";
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Erreur: " . $e->getMessage();
    }
    header("location:emploi_du_temps.php");
} else {

    header("Location: erreur.php");
    exit;
}
