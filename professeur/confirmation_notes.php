<?php
$exam_id = $_GET['exam_id'];

$filename = "notes_exam_$exam_id.csv";

$file_path = "../professeur/$filename";

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $filename . '"');
readfile($file_path);
