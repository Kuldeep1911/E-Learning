<?php
session_start();
include "../config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $examId = $data['exam_id'];
    $userAnswers = $data['user_answers'];

    if (!empty($userAnswers)) {
        $admitCardId = $_SESSION['admit_card_id'];

        // Check if the exam is already submitted
        $check_sql = "SELECT COUNT(*) AS cnt FROM user_attempts WHERE admit_card_id = '$admitCardId' AND exam_id = '$examId'";
        $check_result = mysqli_query($conn, $check_sql);
        $check_row = mysqli_fetch_assoc($check_result);

        if ($check_row['cnt'] > 0) {
            echo json_encode(['success' => false, 'message' => 'Exam already submitted.']);
            exit();
        }

        // Start a transaction
        mysqli_begin_transaction($conn);

        try {
            foreach ($userAnswers as $answer) {
                $questionId = $answer['questionId'];
                $selectedOptionId = $answer['selectedOptionId'];
                $isCorrect = ($answer['correctAnswer'] == $selectedOptionId) ? 1 : 0;

                // Get the question_number from options table
                $get_question_number_query = "SELECT question_number FROM options WHERE id='$selectedOptionId'";
                $get_result = mysqli_query($conn, $get_question_number_query);

                if (!$get_result) {
                    throw new Exception('Query failed: ' . mysqli_error($conn));
                }

                $get_row = mysqli_fetch_assoc($get_result);

                if (!$get_row) {
                    throw new Exception('No question number found for selected option ID ' . $selectedOptionId);
                }

                $questionNumber = $get_row['question_number'];

                $sql = "
                    INSERT INTO user_attempts (admit_card_id, exam_id, question_id, selected_option_id, is_correct, question_number)
                    VALUES ('$admitCardId', '$examId', '$questionId', '$selectedOptionId', '$isCorrect', '$questionNumber')
                    ON DUPLICATE KEY UPDATE selected_option_id = VALUES(selected_option_id), is_correct = VALUES(is_correct)
                ";

                if (!mysqli_query($conn, $sql)) {
                    throw new Exception('Error executing query: ' . mysqli_error($conn));
                }
            }

            // Commit the transaction
            mysqli_commit($conn);
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            // Rollback the transaction in case of error
            mysqli_rollback($conn);
            echo json_encode(['success' => false, 'message' => 'Failed to submit exam. ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'No answers provided.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>
