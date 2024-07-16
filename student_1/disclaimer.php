<?php
session_start();

if (!isset($_SESSION['exam_id'])) {
    header("Location: login.php");
    exit();
}
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
                <form action="start_exam.php" method="POST">
                    <button type="submit" class="btn btn-primary btn-block">I Understand, Start Exam</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
