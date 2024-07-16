<?php
session_start();
include "../config.php";

// Verify if the exam session is valid
if (!isset($_SESSION['exam_id']) || !isset($_SESSION['admit_card_id'])) {
    die("Unauthorized access.");
}

// Fetch questions and options for the exam
$exam_id = $_SESSION['exam_id'];
$sql = "SELECT * FROM questions WHERE exam_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $exam_id);
$stmt->execute();
$result = $stmt->get_result();
$questions = $result->fetch_all(MYSQLI_ASSOC);

// Fetch user's attempt details if available
$user_attempt = array();
$sql = "SELECT * FROM user_attempts WHERE admit_card_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['admit_card_id']);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $user_attempt[$row['question_number']] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Interface</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .question-container { margin-bottom: 30px; }
        .answered { background-color: #d4edda; } /* Bootstrap success alert color */
        .not-answered { background-color: #f8d7da; } /* Bootstrap danger alert color */
    </style>
</head>
<body>
    <div class="container mt-4">
        <h3>Exam</h3>
        <div id="exam-content">
            <!-- Display questions here -->
            <?php foreach ($questions as $question): ?>
                <?php
                $answered_class = isset($user_attempt[$question['question_number']]) ? 'answered' : 'not-answered';
                ?>
                <div class="question-container <?= $answered_class ?>" data-question-id="<?= $question['question_number'] ?>">
                    <h5><?= $question['question_text'] ?></h5>
                    <?php
                    $sql = "SELECT * FROM options WHERE question_number = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $question['question_number']);
                    $stmt->execute();
                    $options = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
                    ?>
                    <?php foreach ($options as $option): ?>
                        <div class="form-check">
                            <?php
                            $checked = isset($user_attempt[$question['question_number']]) && $user_attempt[$question['question_number']]['option_id'] == $option['id'] ? 'checked' : '';
                            ?>
                            <input class="form-check-input" type="radio" name="question_<?= $question['question_number'] ?>" value="<?= $option['id'] ?>" <?= $checked ?>>
                            <label class="form-check-label"><?= $option['coption'] ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <button id="next-question" class="btn btn-primary">Next Question</button>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Timer Script -->
    <script>
        let questionIndex = 0;
        const questions = document.querySelectorAll('.question-container');
        const totalQuestions = questions.length;
        
        function showQuestion(index) {
            questions.forEach((question, i) => {
                question.style.display = i === index ? 'block' : 'none';
            });
        }

        showQuestion(questionIndex);

        document.getElementById('next-question').addEventListener('click', () => {
            // Record user attempt for current question
            const selectedOption = document.querySelector('input[name="question_' + (questionIndex + 1) + '"]:checked');
            const optionId = selectedOption ? selectedOption.value : null;
            const questionNumber = questionIndex + 1;

            // Ajax call to store user attempt
            if (optionId !== null) {
                fetch('store_attempt.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        admit_card_id: <?= $_SESSION['admit_card_id'] ?>,
                        question_number: questionNumber,
                        option_id: optionId,
                        is_correct: selectedOption.getAttribute('data-is-correct') === '1' ? 1 : 0,
                    }),
                })
                .then(response => response.json())
                .then(data => console.log(data))
                .catch(error => console.error('Error:', error));
            }

            // Move to the next question
            if (questionIndex < totalQuestions - 1) {
                questionIndex++;
                showQuestion(questionIndex);
            } else {
                alert("You've completed the exam.");
                // You may want to handle exam submission here
            }
        });

        // Timer (you may need to adjust the timer logic based on your specific requirements)
        setInterval(() => {
            if (questionIndex < totalQuestions - 1) {
                questionIndex++;
                showQuestion(questionIndex);
            } else {
                alert("Time's up!");
                // Handle exam end here
            }
        }, 15000); // 15 seconds timer for each question
    </script>
</body>
</html>
