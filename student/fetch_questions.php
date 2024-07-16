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
    if (!isset($questions[$row['question_id']])) {
        $questions[$row['question_id']] = [
            'question_number' => $row['question_id'],
            'text' => $row['question_text'],
            'description' => $row['description'],
            'options' => []  // Initialize options array
        ];
    }

    // Add each option to the options array for the respective question
    $questions[$row['question_id']]['options'][] = [
        'id' => $row['option_id'],
        'value' => $row['coption'],
        'is_correct' => $row['is_correct']
    ];
}

// Convert associative array to indexed array to match JSON format
$questions = array_values($questions);

echo json_encode($questions);
?>
