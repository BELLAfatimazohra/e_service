<?php
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type'])) {
    header("Location: index.php");
    exit;
}
require_once '../../include/database.php';
require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!isset($_POST['exam_id']) || !isset($_POST['module_id']) || !isset($_POST['filiere_id'])) {
    header("Location: erreur.php");
    exit;
}

$exam_id = $_POST['exam_id'];
$module_id = $_POST['module_id'];
$filiere_id = $_POST['filiere_id'];
$user_id = $_SESSION['user_id'];
function fetchInfo($pdo, $placeholder, $value, $query)
{
    $stmt = $pdo->prepare($query);
    $stmt->execute([$placeholder => $value]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

try {






    $exam_info = fetchInfo($pdo, 'exam_id', $exam_id, "SELECT type, id_module FROM exam WHERE id = :exam_id");
    $module_info = fetchInfo($pdo, 'module_id', $module_id, "SELECT Nom_module FROM module WHERE id = :module_id");
    $filiere_info = fetchInfo($pdo, 'filiere_id', $filiere_id, "SELECT Nom_filiere, id_coordinateur, annee FROM filiere WHERE id = :filiere_id");

    // Récupérer l'e-mail du coordinateur
    $coordinateur_id = $filiere_info['id_coordinateur'];
    $stmt_coordinateur_info = $pdo->prepare("SELECT Email , Nom ,Prenom FROM coordinateur WHERE id = :id_coordinateur");
    $stmt_coordinateur_info->execute(['id_coordinateur' => $coordinateur_id]);
    $coordinateur_info = $stmt_coordinateur_info->fetch(PDO::FETCH_ASSOC);
    $coordinateur_email = $coordinateur_info['Email'];
    $coordinateur_nom = $coordinateur_info['Nom'];
    $coordinateur_prenom = $coordinateur_info['Prenom'];
    $coordinateur_nom_complet = $coordinateur_info['Nom'] . ' ' . $coordinateur_info['Prenom'];

    // Récupérer l'email du professeur connecté
    $stmt_email = $pdo->prepare("SELECT Email , Nom,Prenom FROM professeur WHERE id = :user_id");
    $stmt_email->execute(['user_id' => $user_id]);
    $user_info = $stmt_email->fetch(PDO::FETCH_ASSOC);
    $prof_email = $user_info['Email'];
    $prof_nom = $user_info['Nom'];
    $prof_prenom = $user_info['Prenom'];
    $prof_nom_complet = $user_info['Nom'] . ' ' . $user_info['Prenom'];

    // Préparer le contenu de l'e-mail
    $subject = "Validation des notes pour l'examen {$exam_info['type']}";
    $message = "Les notes pour l'examen {$exam_info['type']} du module {$module_info['Nom_module']} pour la filière {$filiere_info['Nom_filiere']} sont pretes.";



    

    $table_name = "{$exam_info['type']}_{$module_info['Nom_module']}_{$filiere_info['Nom_filiere']}";
    $table_name = str_replace(" ", "_", $table_name); 
    $table_name = strtolower($table_name);
    echo $table_name;

    $sql = "CREATE TABLE {$table_name} (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        id_etudiant INT(6) UNSIGNED,
        note_value FLOAT,
        remarque VARCHAR(255)
    )";
    $pdo->exec($sql);

    $student_info = fetchInfo($pdo, 'filiere_id', $filiere_id, "SELECT id FROM etudiant WHERE id_filiere = :filiere_id");

    $filename = "notes_" . str_replace(' ', '_', strtolower($exam_info['type'])) . "_" . str_replace(' ', '_', strtolower($module_info['Nom_module'])) . "_" . str_replace(' ', '_', strtolower($filiere_info['Nom_filiere'])) . "_" . $filiere_info['annee'] . ".csv";

    $csvFile = $filename;

    try {
        if (($handle = fopen($csvFile, "r")) !== FALSE) {
            

            $sql = "INSERT INTO {$table_name} (id_etudiant, note_value, remarque) VALUES (:id_etudiant, :note_value, :remarque)";
            $stmt = $pdo->prepare($sql);

            // Bind parameters
            $stmt->bindParam(':id_etudiant', $id_etudiant);
            $stmt->bindParam(':note_value', $note_value);
            $stmt->bindParam(':remarque', $remarque);

            while (($data = fgetcsv($handle)) !== FALSE) {
                $id_etudiant = $data[0];
                // Extract note value and remark from CSV
                $note_value = $data[3];
                $remarque = $data[4];

                // Execute the SQL statement to insert data
                $stmt->execute();
            }

            // Close CSV file
            fclose($handle);
        }
        try {
            $sql_insert_table_name = "INSERT INTO notes_provisoire (exam_id, nom_exam) VALUES (:exam_id, :nom_exam)";
            $stmt_insert_table_name = $pdo->prepare($sql_insert_table_name);
            
            // Bind parameters
            $stmt_insert_table_name->bindParam(':exam_id', $exam_id);
            $stmt_insert_table_name->bindParam(':nom_exam', $table_name);
            
            // Execute the SQL statement
            $stmt_insert_table_name->execute();
            
            echo "Table name and exam details inserted into note_provisoire table successfully";
        } catch (PDOException $e) {
            // Handle any errors that occur during the execution of the SQL statement
            echo "Error inserting table name: " . $e->getMessage();
        }
        
        echo "Data inserted successfully";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }







    // Envoi de l'e-mail
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = "e.service.ensah@gmail.com";
    $mail->Password = 'bjtp ifey envd koar';

    $mail->Port = 587;
    $mail->setFrom($prof_email, $prof_nom_complet);
    $mail->addAddress($coordinateur_email, $coordinateur_nom_complet);
    $mail->addReplyTo($prof_email, $prof_nom_complet);
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body = $message;

    $mail->send();
    echo 'L\'e-mail a été envoyé avec succès au coordinateur de la filière.';
} catch (PDOException $e) {
    echo "Erreur lors de l'exécution de la requête : " . $e->getMessage();
} catch (Exception $e) {
    echo "Erreur lors de l'envoi de l'e-mail : {$mail->ErrorInfo}";
}
