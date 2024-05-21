<?php
require_once '../include/database.php';

if (isset($_GET['exam_id'])) {
    $exam_id = $_GET['exam_id'];

    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM notes_provisoire WHERE exam_id = :exam_id");
        $stmt->execute(['exam_id' => $exam_id]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            echo json_encode(['exists' => true]);
        } else {
            echo json_encode(['exists' => false]);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Invalid request']);
}
