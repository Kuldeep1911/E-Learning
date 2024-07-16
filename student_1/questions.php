<?php
session_start();
include "../config.php";

if (!isset($_SESSION['student_id'])) {
    die("Student not logged in");
}

// Ensure `course_id` parameter is provided and is a valid number
if (isset($_GET['course_id']) && is_numeric($_GET['course_id'])) {
    $course_id = (int)$_GET['course_id'];
} else {
    die("Invalid course ID.");
}

// Ensure `n` parameter is provided and is a valid number
if (isset($_GET['n']) && is_numeric($_GET['n'])) {
    $number = (int)$_GET['n'];
} else {
    die("Invalid question number.");
}

// Query for the questions based on course_id
$query = "SELECT * FROM questions WHERE course_id = $course_id AND question_number = $number";
$result = mysqli_query($conn, $query);

// Check if there are questions
if (mysqli_num_rows($result) > 0) {
    // Fetch question
    $question = mysqli_fetch_assoc($result);

    // Query for choices
    $queryChoices = "SELECT * FROM options WHERE question_number = $number";
    $choicesResult = mysqli_query($conn, $queryChoices);

    // Get total questions for the course
    $queryTotal = "SELECT * FROM questions WHERE course_id = $course_id";
    $total_question = mysqli_num_rows(mysqli_query($conn, $queryTotal));

    // Determine next question number
    $nextQuestion = $number + 1 <= $total_question ? $number + 1 : 1; // Wrap around to the first question if it's the last one
} else {
    die("No questions found for this course.");
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Question <?= $number; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .question-container {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            padding: 2rem;
            max-width: 700px;
            width: 100%;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .question-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }
        .question-header {
            text-align: center;
            margin-bottom: 1.5rem;
            color: #007bff;
        }
        .question-title {
            font-size: 1.75rem;
            margin-bottom: 1.5rem;
            color: #343a40;
        }
        .option {
            background: #e9ecef;
            border: none;
            padding: 0.75rem 1rem;
            margin-bottom: 1rem;
            border-radius: 8px;
            transition: background-color 0.3s, border-color 0.3s;
        }
        .option:hover {
            background-color: #ced4da;
        }
        .option input[type="radio"] {
            margin-right: 0.75rem;
        }
        .btn-submit {
            background-color: #007bff;
            color: #fff;
            font-size: 1rem;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .btn-submit:hover {
            background-color: #0056b3;
        }
        .timer {
            font-size: 1.25rem;
            font-weight: bold;
            color: #dc3545;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="question-container">
        <div class="question-header">
            <h2>Question <?= $number; ?> of <?= $total_question; ?></h2>
            <div class="timer" id="timer">15</div>
        </div>
        <form id="questionForm" method="post" action="process.php">
            <div class="question-title"><?= $question['question_text']; ?></div>
            <?php while ($row = mysqli_fetch_assoc($choicesResult)): ?>
                <label class="option d-flex align-items-center">
                    <input type="radio" name="choice" value="<?= $row['id']; ?>" required>
                    <?= $row['coption']; ?>
                </label>
            <?php endwhile; ?>
            <input type="hidden" name="number" value="<?= $number; ?>">
            <input type="hidden" name="course_id" value="<?= $course_id; ?>">
            <div class="text-end mt-4">
                <button type="submit" name="submit" class="btn btn-submit">Submit</button>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Timer
        let timeLeft = 15;
        const timerElement = document.getElementById('timer');
        const nextQuestionUrl = `questions.php?course_id=<?= $course_id ?>&n=<?= $nextQuestion; ?>`;
        const timerInterval = setInterval(() => {
            timeLeft--;
            timerElement.textContent = timeLeft;
            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                window.location.href = nextQuestionUrl;
            }
        }, 1000);

        // Tab focus/blur detection
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                // Redirect to a logout or error page
                window.location.href = 'logout.php';
            }
        });
    </script>
</body>
</html>
