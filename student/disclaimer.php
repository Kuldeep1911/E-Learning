<?php
session_start();
include("../config.php");
if (!isset($_SESSION['exam_id'])) {
    header("Location: index.php");
    exit();
}
$examId = $_SESSION['exam_id'];
$admitCardId  = $_SESSION['admit_card_id'];
$check_sql = "SELECT COUNT(*) AS cnt FROM user_attempts WHERE admit_card_id = '$admitCardId' AND exam_id = '$examId'";
$check_result = mysqli_query($conn, $check_sql);
$check_row = mysqli_fetch_assoc($check_result);

if ($check_row['cnt'] > 0) {
    echo "<script>alert('Exam already submitted.'); window.location.href = 'result.php';</script>";
    exit();
}
// Check if the disclaimer has already been viewed
if (isset($_SESSION['disclaimer_viewed']) && $_SESSION['disclaimer_viewed'] === true) {
    header("Location: exam_portal.php");
    exit();
}

// Set the disclaimer viewed flag
$_SESSION['disclaimer_viewed'] = true;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Disclaimer</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .disclaimer-container { max-width: 600px; margin: 50px auto; }
        .countdown { font-size: 2rem; text-align: center; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container disclaimer-container">
        <h3 class="text-center mb-4">Disclaimer</h3>
        <div class="card">
            <div class="card-body">
                <p>Please read the following instructions carefully before starting the exam:</p>
                <ul>
                    <li>Do not refresh or close the browser during the exam.</li>
                    <li>Each question has a 15-second timer. Answer before the time runs out.</li>
                    <li>Once you move to the next question, you cannot go back to the previous one.</li>
                    <li>Your answers will be automatically submitted at the end of the exam.</li>
                </ul>
                <div class="countdown" id="countdown">Starting in 20 seconds...</div>
                <form id="start-exam-form" action="exam_portal.php" method="POST">
                    <button type="submit" class="btn btn-primary btn-block" disabled>Start Exam</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        let countdownTime = 20;
        const countdownElement = document.getElementById('countdown');
        const startExamButton = document.querySelector('#start-exam-form button');

        const countdownInterval = setInterval(() => {
            countdownTime--;
            countdownElement.textContent = `Starting in ${countdownTime} seconds...`;

            if (countdownTime <= 0) {
                clearInterval(countdownInterval);
                startExamButton.disabled = false;
                startExamButton.textContent = 'Start Exam';
            }
        }, 1000);
    </script>
</body>
</html>
