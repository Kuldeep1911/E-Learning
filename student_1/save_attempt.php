<?php
session_start();
include "../config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $admitCardId = $_SESSION['admit_card_id'];
    $questionNumber = $_POST['question_number'];
    $selectedOptionId = $_POST['selected_option_id'];

    // Validate data (you should add more validation as per your requirements)

    // Insert or update user attempt
    $sql = "INSERT INTO user_attempts (admit_card_id, question_number, option_id)
            VALUES ($admitCardId, $questionNumber, $selectedOptionId)
            ON DUPLICATE KEY UPDATE option_id = VALUES(option_id)";
    
    if (mysqli_query($conn, $sql)) {
        echo 'Attempt saved successfully.';
    } else {    
        echo 'Error: ' . mysqli_error($conn);
    }
} else {
    echo 'Invalid request.';
}
?>
