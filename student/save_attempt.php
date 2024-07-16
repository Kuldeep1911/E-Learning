<?php
session_start();
include "../config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $admitCardId = $_SESSION['admit_card_id'];
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['answers']) && is_array($data['answers'])) {
        foreach ($data['answers'] as $attempt) {
            $questionNumber = $attempt['questionId'];
            $selectedOptionId = $attempt['selectedOptionId'];

            $sql = "INSERT INTO user_attempts (admit_card_id, question_number, option_id)
                    VALUES ('$admitCardId', '$questionNumber', '$selectedOptionId')
                    ON DUPLICATE KEY UPDATE option_id = VALUES(option_id)";

            if (!mysqli_query($conn, $sql)) {
                echo 'Error: ' . mysqli_error($conn);
                exit();
            }
        }

        echo json_encode(['message' => 'Attempt saved successfully.']);
    } else {
        echo json_encode(['message' => 'No attempts to save.']);
    }
} else {
    echo 'Invalid request.';
}
?>
