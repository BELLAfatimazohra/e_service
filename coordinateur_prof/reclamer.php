<?php
session_start();
require_once '../include/database.php';
require '../professeur/PHPMailer/src/Exception.php';
require '../professeur/PHPMailer/src/PHPMailer.php';
require '../professeur/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$response = array('status' => 'error', 'message' => 'An error occurred');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['anomalie_text']) && isset($_POST['exam_id']) && isset($_POST['nom_exam'])) {
    $exam_id = $_POST['exam_id'];
    $nom_exam = $_POST['nom_exam'];
    $anomalie_text = $_POST['anomalie_text'];

    $user_id = $_SESSION['user_id'];

    try {
        // Get coordinateur email
        $sql = "SELECT Email FROM coordinateur WHERE id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $coordinateur_email = $result['Email'];
        } else {
            throw new Exception("Coordinateur's email not found for user ID: " . htmlspecialchars($user_id));
        }

        // Get professor email
        $sql = "SELECT professeur.Email
                FROM exam
                INNER JOIN professeur ON exam.id_prof = professeur.id
                WHERE exam.id = :exam_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':exam_id', $exam_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $professor_email = $result['Email'];
        } else {
            throw new Exception("Professor's email not found for exam ID: " . htmlspecialchars($exam_id));
        }

        // Prepare email content
        $subject = "Anomalie detected in exam";
        $message = "Anomalie has been reported for the exam: " . htmlspecialchars($nom_exam) . "<br>";
        $message .= "Exam ID: " . htmlspecialchars($exam_id) . "<br>";
        $message .= "Anomalie description: " . htmlspecialchars($anomalie_text) . "<br>";

        // Send email
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'e.service.ensah@gmail.com';
        $mail->Password = 'bjtp ifey envd koar';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom($coordinateur_email, 'Coordinateur');
        $mail->addAddress($professor_email, 'Professor');
        $mail->addReplyTo($coordinateur_email, 'Coordinateur');
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;

        $mail->send();
        $response['status'] = 'success';
        $response['message'] = 'Anomalie reported successfully. Email sent to professor.';
    } catch (Exception $e) {
        $response['message'] = 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
    } catch (PDOException $e) {
        $response['message'] = 'Database error: ' . $e->getMessage();
    }
}

echo json_encode($response);

$sql_delete = "DELETE FROM notes_provisoire WHERE nom_exam = :nom_exam";
$stmt_delete = $pdo->prepare($sql_delete);
$stmt_delete->bindParam(':nom_exam', $nom_exam, PDO::PARAM_STR);
$stmt_delete->execute();

// Drop the table
$sql_drop = "DROP TABLE {$nom_exam}";
$pdo->exec($sql_drop);
