<?php
include "../config.php";

// Assuming the data is sent via POST as JSON
$data = json_decode(file_get_contents('php://input'), true);

$admit_card_id = $data['admit_card_id'];
$question_number = $data['question_number'];
$option_id = $data['option_id'];
$is_correct = $data['is_correct'];

// Insert or update user attempt
$sql = "INSERT INTO user_attempts (admit_card_id, question_number, option_id, is_correct) VALUES (?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE option_id = VALUES(option_id), is_correct = VALUES(is_correct)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiii", $admit_card_id, $question_number, $option_id, $is_correct);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error']);
}

$stmt->close();
$conn->close();
?>
