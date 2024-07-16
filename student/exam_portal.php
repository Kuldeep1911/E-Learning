<?php
session_start();
include "../config.php";
date_default_timezone_set('Asia/Kolkata');

if (!isset($_SESSION['exam_id'])) {
    header("Location: index.php");
    exit();
}

$examId = $_SESSION['exam_id'];

// Get exam details from the database to calculate end time
$sql = "SELECT exam_date, start_time, end_time FROM exams WHERE id = '$examId'";
$result = mysqli_query($conn, $sql);
$exam = mysqli_fetch_assoc($result);

if (!$exam) {
    die("Exam not found.");
}

$examId = $_SESSION['exam_id'];
$admitCardId  = $_SESSION['admit_card_id'];
$check_sql = "SELECT COUNT(*) AS cnt FROM user_attempts WHERE id = '$admitCardId' AND exam_id = '$examId'";
$check_result = mysqli_query($conn, $check_sql);
$check_row = mysqli_fetch_assoc($check_result);


// if($check_result){
//     $check_row = mysqli_fetch_assoc($check_result);
//     if($check_row['cnt']> 0){
//         echo "Records found :".$check_row['cnt'];
//     }
//     else{
//         echo "No Records Found";
//     }


// }
// else{
//     echo "Error: " . mysqli_error($conn);
// }


if ($check_row['cnt'] > 0) {
    echo "<script>alert('Exam already submitted.'); window.location.href = 'result.php';</script>";
    exit();
}
$startTime = new DateTime($exam['exam_date'] . ' ' . $exam['start_time']);
$endTime = new DateTime($exam['exam_date'] . ' ' . $exam['end_time']);

$_SESSION['exam_start_time'] = $startTime->format('Y-m-d H:i:s');
$_SESSION['exam_end_time'] = $endTime->format('Y-m-d H:i:s');

// Check if the user has a saved question index
$savedQuestionIndex = isset($_SESSION['current_question_index']) ? $_SESSION['current_question_index'] : 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Portal</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f5efef;
        }

        .question-card {
            margin-top: 20px;
        }

        .timer {
            font-size: 20px;
            color: red;
            font-weight: bold;
        }

        .question-list {
            list-style: none;
            padding: 0;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }

        .question-list-item {
            cursor: pointer;
            padding: 8px;
            margin: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 50px;
            text-align: center;
        }

        .question-list-item.attempted {
            background-color: #d4edda;
        }

        .question-list-item.not-attempted {
            background-color: #f8d7da;
        }

        .question-list-item:hover {
            background-color: #f1f1f1;
        }

        /* General container styling */
        .exam-container {
            background: #f8f9fc;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            font-family: 'Nunito', sans-serif;
        }

        /* Heading */
        h3 {
            font-weight: 600;
            color: #343a40;
        }

        /* Question container */
        .question-container {
            background: #ffffff;
            border: 1px solid #e0e0e0;

            min-height: 300px;
            font-size: 1rem;
            color: #495057;
            line-height: 1.6;
            transition: box-shadow 0.3s;
            margin-bottom: 1.5rem;
        }

        .question-container:hover {
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        }

        /* Timer */
        .timer {
            font-size: 1.25rem;
            font-weight: 600;
            color: #007bff;
            background: #e9f7ff;
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        /* Options */
        .options {
            padding: 15px;
            background: #f4f1f1;
            border-radius: 10px;
            border: 1px solid #e0e0e0;
        }

        #heading {
            text-shadow: 4px 5px 11px whitesmoke;
            letter-spacing: 3px;
            font-family: 'Times New Roman', Times, serif;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <h1 class="text-center mb-4" id="heading">Exam Portal</h1>
        <div class="row ">
            <div class="timer text-center mt-4 mx-4 p-2" id="question-timer"></div>


            <div class="col-md-9">
                <div id="question-container" class="questions shadow p-4 mb-4 mt-4 rounded bg-white"></div>

                <div class="text-center mt-4">
                    <button id="next-question" class="btn btn-primary shadow  bg-blue">Next Question</button>
                    <button id="submit-exam" class="btn btn-success mt-2" data-toggle="modal" data-target="#confirmSubmitModal" style="display: none;">Submit Exam</button>
                </div>
            </div>
            <div class="col-md-3">
                <div class="timer text-center mb-2" id="exam-timer"></div>
                <h5 class="mb-4 shadow-sm">Question List</h5>
                <div class="card p-1">

                    <ul class="question-list" id="question-list"></ul>

                </div>

            </div>
        </div>
    </div>

    <!-- Modal for Confirming Exam Submission -->
    <div class="modal fade" id="confirmSubmitModal" tabindex="-1" role="dialog" aria-labelledby="confirmSubmitModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmSubmitModalLabel">Submit Exam</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to submit the exam? You will not be able to make any changes after this.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" id="confirm-submit" class="btn btn-primary">Submit Exam</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            let examId = <?= $examId ?>;
            let questions = [];
            let currentQuestionIndex = <?= $savedQuestionIndex ?>;
            let questionTimer; // Timer for each question
            let examTimer; // Timer for the entire exam
            let userAnswers = [];
            let questionLoadTime = 15000; // 15 seconds for each question

            // Fetch questions from the server
            async function fetchQuestions() {
                try {
                    let response = await $.ajax({
                        url: 'fetch_questions.php',
                        method: 'GET',
                        data: {
                            exam_id: examId
                        },
                        dataType: 'json'
                    });



                    if (Array.isArray(response) && response.length > 0) {
                        questions = response;
                        displayQuestion();
                        displayQuestionList();
                        startQuestionTimer();
                        startExamTimer();
                    } else {
                        $('#question-container').html('<div class="alert alert-warning">No questions available for this exam.</div>');
                    }
                } catch (error) {
                    console.error('Failed to fetch questions:', error);
                    $('#question-container').html('<div class="alert alert-danger">Failed to load questions. Please try again.</div>');
                }
            }

            // Display the current question
            function displayQuestion() {
                let questionContainer = $('#question-container');
                questionContainer.empty();

                if (currentQuestionIndex >= questions.length) {
                    questionContainer.html('<div class="alert alert-success">Exam Completed!</div>');
                    clearInterval(questionTimer);
                    submitExam();
                    return;
                }

                let question = questions[currentQuestionIndex];
                let optionsHtml = question.options.map(option => `
                                                                <div class="form-check mb-3">
                                                                    <input class="form-check-input" type="radio" name="option" id="option-${option.id}" value="${option.id}">
                                                                    <label class="form-check-label" for="option-${option.id}">${option.value}</label>
                                                                </div>
                                                            `).join('');

                let questionHtml = `
                                                                <div class="card question-card mb-4">
                                                                    <div class="card-body">
                                                                        <h5 class="card-title">Question ${currentQuestionIndex + 1}</h5>
                                                                        <h6 class="card-subtitle mb-2 text-muted">${question.text}</h6>
                                                                        ${question.description ? `<p class="card-text"><small>${question.description}</small></p>` : ''}
                                                                        <form>${optionsHtml}</form>
                                                                    </div>
                                                                </div>
                                                            `;

                questionContainer.html(questionHtml);

                // Update the question list
                updateQuestionList();
                startQuestionTimer(); // Reset the question timer for each new question

                // Show the submit button only at the last question
                if (currentQuestionIndex === questions.length - 1) {
                    $('#submit-exam').show();
                } else {
                    $('#submit-exam').hide();
                }
            }



            // Display the list of questions
            function displayQuestionList() {
                let questionList = $('#question-list');
                questionList.empty();

                questions.forEach((question, index) => {
                    let listItemClass = userAnswers[index] !== undefined ? 'attempted' : 'not-attempted';
                    let listItem = $(`<li class="question-list-item ${listItemClass}">${index + 1}</li>`);
                    listItem.click(() => {
                        currentQuestionIndex = index;
                        displayQuestion();
                    });
                    questionList.append(listItem);
                });
            }

            // Update the question list (attempted/not attempted)
            function updateQuestionList() {
                $('#question-list .question-list-item').each(function(index) {
                    let listItemClass = userAnswers[index] !== undefined ? 'attempted' : 'not-attempted';
                    $(this).removeClass('attempted not-attempted').addClass(listItemClass);
                });
            }

            // Start the timer for each question
            function startQuestionTimer() {
                clearInterval(questionTimer);
                let timeLeft = questionLoadTime / 1000;
                $('#question-timer').text(`Time left for this question: ${timeLeft} seconds`);

                questionTimer = setInterval(() => {
                    timeLeft--;
                    $('#question-timer').text(`Time left for this question: ${timeLeft} seconds`);
                    if (timeLeft <= 0) {
                        clearInterval(questionTimer);
                        saveAttempt();
                        currentQuestionIndex++;
                        displayQuestion();
                    }
                }, 1000);
            }

            // Start the timer for the entire exam
            function startExamTimer() {
                let examEndTime = new Date("<?= $_SESSION['exam_end_time'] ?>").getTime();
                examTimer = setInterval(() => {
                    let now = new Date().getTime();
                    let distance = examEndTime - now;

                    let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    let seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    $('#exam-timer').text(` ${hours}h ${minutes}m ${seconds}s`);

                    if (distance < 0) {
                        clearInterval(examTimer);
                        $('#exam-timer').text("EXAM TIME OVER");
                        submitExam();
                    }
                }, 1000);
            }

            // Save the user's attempt for the current question
            function saveAttempt() {
                let currentQuestion = questions[currentQuestionIndex];

                if (!currentQuestion || !currentQuestion.options) {
                    console.error('Current question or options are undefined');
                    return;
                }

                let selectedOptionId = $('input[name="option"]:checked').val();
                let correctAnswer = currentQuestion.options.find(option => option.is_correct)?.id;

                if (!selectedOptionId || !correctAnswer) {
                    console.error('Selected option or correct answer is undefined');
                    return;
                }

                userAnswers[currentQuestionIndex] = {
                    questionId: currentQuestion.question_number,
                    selectedOptionId: selectedOptionId,
                    correctAnswer: correctAnswer,
                    questionNumber: currentQuestionIndex + 1,
                    sequenceNumber: currentQuestionIndex + 1
                };


                updateQuestionList();
            }

            // Submit the entire exam
            async function submitExam() {
                try {
                    let response = await $.ajax({
                        url: 'submit_exam.php',
                        method: 'POST',
                        contentType: 'application/json',
                        data: JSON.stringify({
                            exam_id: examId,
                            user_answers: userAnswers
                        }),
                        dataType: 'json'
                    });

                    if (response.success) {
                        alert('Exam submitted successfully!');
                        window.location.href = 'result.php';
                    } else {
                        alert('Failed to submit exam: ' + response.message);
                    }
                } catch (error) {
                    console.error('Failed to submit exam:', error);
                    alert('Failed to submit exam. Please try again.');
                }
            }
            document.addEventListener('visibilitychange', function() {
                if (document.visibilityState === 'hidden') {
                    saveCurrentQuestionIndex();
                }
            });

            // Save the current question index to the server
            async function saveCurrentQuestionIndex() {
                try {
                    await $.ajax({
                        url: 'save_question_index.php',
                        method: 'POST',
                        data: {
                            current_question_index: currentQuestionIndex
                        },
                        dataType: 'json'
                    });

                    // Logout after saving the current question index
                    window.location.href = 'logout.php';
                } catch (error) {
                    console.error('Failed to save current question index:', error);
                }
            }


            // Next question button click handler
            $('#next-question').click(() => {
                saveAttempt();
                currentQuestionIndex++;
                displayQuestion();
            });

            // Submit exam button click handler
            $('#submit-exam').click(() => {
                $('#confirmSubmitModal').modal('show');
            });

            // Confirm submit button click handler
            $('#confirm-submit').click(() => {
                saveAttempt();
                submitExam();
            });

            // Fetch questions on page load
            fetchQuestions();
        });
    </script>
</body>

</html>