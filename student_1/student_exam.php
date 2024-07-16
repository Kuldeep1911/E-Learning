<?php
session_start();
include "../config.php";

if (!isset($_SESSION['student_id'])) {
    die("Student not logged in");
}

$query = "SELECT * FROM questions"; 
$total_questions = mysqli_num_rows(mysqli_query($conn, $query));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Student Exam</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .exam-container {
            background: #fff;
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .exam-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .exam-details li {
            list-style: none;
            padding: 0.5rem 0;
        }
        .btn-start {
            background-color: #28a745;
            border: none;
            padding: 0.75rem 1.5rem;
            font-size: 1.2rem;
        }
        .btn-start:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="exam-container">
                    <div class="exam-header">
                        <h2>Test Your Knowledge</h2>
                        <p>This is a multiple choice quiz to test your PHP knowledge.</p>
                    </div>
                    <ul class="exam-details">
                        <li><strong>Number of Questions:</strong> <?php echo $total_questions; ?></li>
                        <li><strong>Type:</strong> Multiple Choice</li>
                        <li><strong>Estimated Time:</strong> <?php echo $total_questions * 1.5; ?> Minutes</li>
                    </ul>
                    <div class="text-center mt-4">
                        <a href="questions.php?n=1" class="btn btn-start text-white">Start Exam</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
