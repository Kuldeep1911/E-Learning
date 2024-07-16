<?php
session_start();
include "../config.php";

$exam_id = $_GET['exam_id'] ?? null;

if (!$exam_id) {
    die("Invalid request.");
}

$sql = "SELECT q.question_number as question_id, q.question_text, q.description, o.id as option_id, o.coption, o.is_correct
        FROM questions q
        JOIN options o ON q.question_number = o.question_number
        WHERE q.exam_id = '$exam_id'
        ORDER BY q.question_number";

$result = mysqli_query($conn, $sql);
$questions = [];

while ($row = mysqli_fetch_assoc($result)) {
    $questions[$row['question_id']]['text'] = $row['question_text'];
    $questions[$row['question_id']]['description'] = $row['description'];
    $questions[$row['question_id']]['options'][] = [
        'id' => $row['option_id'],
        'value' => $row['coption'],
        'is_correct' => $row['is_correct']
    ];
}

echo json_encode(array_values($questions));
?>
