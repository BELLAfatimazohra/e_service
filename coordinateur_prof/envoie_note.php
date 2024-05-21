<?php

session_start();
require '../include/database.php';
require '../professeur/PHPMailer/src/Exception.php';
require '../professeur/PHPMailer/src/PHPMailer.php';
require '../professeur/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nom_exam']) && isset($_POST['exam_id'])) {
    $nom_exam = $_POST['nom_exam'];
    $id_exam = $_POST['exam_id'];

    try {
        // Start transaction
        $pdo->beginTransaction();

        // Insert data
        $sql = "INSERT INTO note (id_etudiant, ide_exam, remarque, note_value)
                SELECT id_etudiant, :id_exam, remarque, note_value
                FROM {$nom_exam}";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_exam', $id_exam, PDO::PARAM_INT);
        $stmt->execute();

        // Delete row from note_provisoire
        $sql_delete = "DELETE FROM notes_provisoire WHERE nom_exam = :nom_exam";
        $stmt_delete = $pdo->prepare($sql_delete);
        $stmt_delete->bindParam(':nom_exam', $nom_exam, PDO::PARAM_STR);
        $stmt_delete->execute();

        // Drop the table
        $sql_drop = "DROP TABLE {$nom_exam}";
        $pdo->exec($sql_drop);

        // Step 1: Get id_module from exam table
        $sql = "SELECT id_module FROM exam WHERE id = :exam_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':exam_id', $id_exam, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $id_module = $result['id_module'];

            // Step 2: Get id_filiere from module table
            $sql = "SELECT id_filiere FROM module WHERE id = :id_module";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id_module', $id_module, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                $id_filiere = $result['id_filiere'];

                // Step 3: Get emails from etudiant table
                $sql = "SELECT Email FROM etudiant WHERE id_filiere = :id_filiere";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id_filiere', $id_filiere, PDO::PARAM_INT);
                $stmt->execute();
                $emails = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if ($emails) {
                    // Get coordinateur info
                    $stmt_coord = $pdo->prepare("SELECT Nom, Prenom, Email FROM coordinateur WHERE id = :id");
                    $stmt_coord->execute(['id' => $_SESSION['user_id']]);
                    $coordinateur = $stmt_coord->fetch(PDO::FETCH_ASSOC);

                    if (!$coordinateur) {
                        throw new Exception("Informations du coordinateur introuvables.");
                    }

                    // Send email
                    $mail = new PHPMailer(true);

                    try {
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';
                        $mail->SMTPAuth = true;
                        $mail->Username = 'e.service.ensah@gmail.com';
                        $mail->Password = 'bjtp ifey envd koar';
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        $mail->Port = 587;

                        // SMTP Debugging
                        $mail->SMTPDebug = 2; // Enable verbose debug output

                        $mail->setFrom($coordinateur['Email'], $coordinateur['Nom'] . ' ' . $coordinateur['Prenom']);
                        $mail->addReplyTo($coordinateur['Email'], $coordinateur['Nom'] . ' ' . $coordinateur['Prenom']);

                        foreach ($emails as $email) {
                            $mail->addAddress($email['Email']);
                        }

                        $titre = "Nouvel Affichage Pour Module {$nom_exam}";
                        $message = "Veuillez consulter votre espace étudiant.";

                        $mail->isHTML(true);
                        $mail->Subject = $titre;
                        $mail->Body = $message;

                        $mail->send();
                        echo "Message envoyé avec succès.";
                    } catch (Exception $e) {
                        echo "Le message n'a pas pu être envoyé. Erreur de Mailer : {$mail->ErrorInfo}";
                    }
                } else {
                    echo "No students found for filiere ID: " . htmlspecialchars($id_filiere);
                }
            } else {
                echo "No filiere found for module ID: " . htmlspecialchars($id_module);
            }
        } else {
            echo "No module found for exam ID: " . htmlspecialchars($id_exam);
        }

        // Commit transaction
        $pdo->commit();
        echo "Success: All operations were successfully completed.";
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo "Error during transaction: " . $e->getMessage();
    }
} else {
    echo "No exam selected or wrong method used.";
}
?>
