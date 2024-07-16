<?php
session_start();

if (!isset($_SESSION['exam_id'])) {
    echo json_encode(['message' => 'Session expired. Please log in again.']);
    exit();
}

$examId = $_SESSION['exam_id'];
$userId = $_SESSION['user_id'] ?? 0; // Ensure user_id is stored in the session

include 'config.php'; // Ensure this file contains the correct DB connection

$input = json_decode(file_get_contents('php://input'), true);
$userAnswers = $input['answers'] ?? [];

if (!$userAnswers) {
    echo json_encode(['message' => 'No answers submitted.']);
    exit();
}

$stmt = $conn->prepare("
    INSERT INTO user_attempts (exam_id, user_id, sequence_number, question_number, question_id, selected_option_id, correct_option_id) 
    VALUES (?, ?, ?, ?, ?, ?, ?)
");

foreach ($userAnswers as $answer) {
    $questionId = $answer['questionId'];
    $questionNumber = $answer['questionNumber'];
    $selectedOptionId = $answer['selectedOptionId'];
    $correctOptionId = $answer['correctAnswer'];
    $sequenceNumber = $answer['sequenceNumber'];

    if ($stmt) {
        $stmt->bind_param("iiiiiii", $examId, $userId, $sequenceNumber, $questionNumber, $questionId, $selectedOptionId, $correctOptionId);
        if (!$stmt->execute()) {
            echo json_encode(['message' => 'Error saving answers to the database.']);
            exit();
        }
    } else {
        echo json_encode(['message' => 'Database preparation error.']);
        exit();
    }
}

echo json_encode(['message' => 'Exam submitted successfully!']);
?>
