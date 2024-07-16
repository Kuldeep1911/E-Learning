<?php
session_start();

if (!isset($_SESSION['exam_id'])) {
    header("Location: login.php");
    exit();
}

$examId = $_SESSION['exam_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Portal</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .question-card { margin-top: 20px; }
        .timer { font-size: 20px; color: red; font-weight: bold; }
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
            background-color: #fff;
        }
        .question-list-item:hover {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h3 class="text-center mb-4">Exam Portal</h3>
        <div class="row">
            <div class="col-md-9">
                <div id="question-container"></div>
                <div class="timer text-center mt-4" id="timer"></div>
                <div class="text-center mt-4">
                    <button id="submit-exam" class="btn btn-success">Submit Exam</button>
                </div>
            </div>
            <div class="col-md-3">
                <h5>Question List</h5>
                <ul class="question-list" id="question-list"></ul>
            </div>
        </div>
    </div>

    <script>
        let examId = <?= $examId ?>;
        let questions = [];
        let currentQuestionIndex = 0;
        let timerDuration = 15;
        let timer;
        let userAnswers = [];

        document.addEventListener('DOMContentLoaded', async () => {
            await fetchQuestions();
            displayQuestion();
            startTimer();
            displayQuestionList();
        });

        async function fetchQuestions() {
            let response = await fetch(`fetch_questions.php?exam_id=${examId}`);
            questions = await response.json();
        }

        function displayQuestion() {
            let questionContainer = document.getElementById('question-container');
            questionContainer.innerHTML = '';

            if (currentQuestionIndex >= questions.length) {
                questionContainer.innerHTML = '<div class="alert alert-success">Exam Completed!</div>';
                clearInterval(timer);
                submitExam();
                return;
            }

            let question = questions[currentQuestionIndex];
            let optionsHtml = question.options.map(option => `
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="option" value="${option.id}">
                    <label class="form-check-label">${option.value}</label>
                </div>
            `).join('');

            questionContainer.innerHTML = `
                <div class="card question-card">
                    <div class="card-body">
                        <h5 class="card-title">Question ${currentQuestionIndex + 1}</h5>
                        <p class="card-text">${question.text}</p>
                        ${question.description ? `<p class="card-text"><small>${question.description}</small></p>` : ''}
                        <form>${optionsHtml}</form>
                    </div>
                </div>
            `;
        }

        function displayQuestionList() {
            let questionList = document.getElementById('question-list');
            questionList.innerHTML = '';

            questions.forEach((question, index) => {
                let listItemClass = userAnswers[index] !== undefined ? 'attempted' : 'not-attempted';
                let listItem = document.createElement('li');
                listItem.className = `question-list-item ${listItemClass}`;
                listItem.textContent = `${index + 1}`;
                listItem.addEventListener('click', () => {
                    currentQuestionIndex = index;
                    displayQuestion();
                    updateQuestionList();
                });
                questionList.appendChild(listItem);
            });
        }

        function updateQuestionList() {
            let questionItems = document.querySelectorAll('.question-list-item');
            questionItems.forEach((item, index) => {
                let isAttempted = userAnswers[index] !== undefined;
                item.className = `question-list-item ${isAttempted ? 'attempted' : 'not-attempted'}`;
            });
        }

        function startTimer() {
            let timerElement = document.getElementById('timer');
            timer = setInterval(() => {
                if (timerDuration <= 0) {
                    timerDuration = 15;
                    currentQuestionIndex++;
                    displayQuestion();
                    updateQuestionList();
                } else {
                    timerElement.textContent = `Time left: ${timerDuration} seconds`;
                    timerDuration--;
                }
            }, 1000);
        }

        document.getElementById('submit-exam').addEventListener('click', () => {
            submitExam();
        });

        function submitExam() {
            let form = document.querySelector('form');
            let selectedOption = form.querySelector('input[name="option"]:checked');

            if (selectedOption) {
                let questionId = questions[currentQuestionIndex].id;
                let correctAnswer = questions[currentQuestionIndex].correct_option_id; // Assuming you have correct option in the question object
                userAnswers[currentQuestionIndex] = {
                    questionId: questionId,
                    questionNumber: currentQuestionIndex + 1,
                    selectedOptionId: selectedOption.value,
                    correctAnswer: correctAnswer,
                    sequenceNumber: currentQuestionIndex + 1 // Assuming sequence number follows the question number order
                };
            }

            fetch('submit_exam.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ answers: userAnswers, exam_id: examId })
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                window.location.reload();
            });
        }
    </script>
</body>
</html>
