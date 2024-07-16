<?php
session_start();
include "../config.php";

$examId = $_POST['exam_id'];
$questionNumber = $_POST['question_number'];

// Query to fetch question and options
$sql = "SELECT q.*, o.id AS option_id, o.option_text, o.is_correct 
        FROM questions q 
        LEFT JOIN options o ON q.question_number = o.question_number 
        WHERE q.exam_id = $examId AND q.question_number = $questionNumber";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

// Display question and options
if ($row) {
    echo '<h4>Question ' . $row['question_number'] . '</h4>';
    echo '<p>' . $row['question_text'] . '</p>';
    echo '<ul>';
    while ($row && $row['question_number'] == $questionNumber) {
        $optionId = $row['option_id'];
        echo '<li><input type="radio" name="option_' . $row['question_number'] . '" value="' . $optionId . '"> ' . $row['option_text'] . '</li>';
        $row = mysqli_fetch_assoc($result);
    }
    echo '</ul>';
} else {
    echo 'Question not found.';
}
?>
