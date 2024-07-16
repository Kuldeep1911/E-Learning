<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Portal</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .question-number {
            cursor: pointer;
        }
        .answered {
            background-color: #b2ebf2; /* Light blue */
        }
        .unanswered {
            background-color: #ffcdd2; /* Light red */
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h3>Exam Portal</h3>
        <div id="exam-content">
            <!-- Question content will be loaded dynamically here -->
        </div>
        <div id="question-list" class="mt-4">
            <!-- Question numbers will be displayed here dynamically -->
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            var examId = <?php echo $_SESSION['exam_id']; ?>;
            var admitCardId = <?php echo $_SESSION['admit_card_id']; ?>;
            var totalQuestions = <?php echo $totalQuestions; ?>; // Total number of questions

            // Load initial question on page load
            loadQuestion(1);

            // Function to load question by question number
            function loadQuestion(questionNumber) {
                $.ajax({
                    url: 'get_question.php',
                    method: 'POST',
                    data: { exam_id: examId, question_number: questionNumber },
                    success: function(response) {
                        $('#exam-content').html(response);
                        highlightQuestion(questionNumber);
                    }
                });
            }

            // Function to highlight answered question numbers
            function highlightQuestion(questionNumber) {
                $('.question-number').removeClass('answered unanswered');
                $('.question-number').each(function() {
                    var qNumber = parseInt($(this).text());
                    if (qNumber === questionNumber) {
                        $(this).addClass('answered');
                    } else {
                        var isAnswered = $(this).hasClass('answered');
                        if (!isAnswered) {
                            $(this).addClass('unanswered');
                        }
                    }
                });
            }

            // Click handler for question numbers
            $(document).on('click', '.question-number', function() {
                var questionNumber = parseInt($(this).text());
                loadQuestion(questionNumber);
            });

            // Automatically submit exam when end time is reached
            var endDateTime = new Date(<?php echo json_encode($endDateTime); ?>); // Convert PHP datetime to JavaScript Date object
            var now = new Date();
            if (now >= endDateTime) {
                // Redirect to scorecard or completion page
                window.location.href = 'scorecard.php';
            }
        });
    </script>
</body>
</html>
