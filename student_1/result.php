<?php 
include "../config.php";
session_start();

// Assuming the score is stored in the session
$score = $_SESSION['score'] ?? 0;
$student_id = $_SESSION['student_id'];

// Insert the score into the database
if ($score !== null && $student_id !== null) {
    $query = "INSERT INTO results (student_id, score) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ii", $student_id, $score);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    } else {
        die("Database query failed: " . mysqli_error($conn));
    }
}

// Clear the score from the session after saving it to the database
unset($_SESSION['score']);
?>
<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-dpuaG1suU0eT09tx5plTaGMLBsfDLzUCCUXOY2j/LSvXYuG6Bqs43ALlhIqAJVRb" crossorigin="anonymous">

    <!-- Custom CSS for background and animations -->
    <style>
        body {
            background: linear-gradient(135deg, #f06, #4a90e2);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            overflow: hidden;
            color: white;
            font-family: 'Arial', sans-serif;
        }
        .container {
            text-align: center;
            background: linear-gradient(135deg, #ff6b6b, #ffcc00);
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            animation: slideIn 1s ease-in-out;
            color: white;
        }
        .container h2 {
            font-size: 3rem;
            margin-bottom: 1rem;
            animation: bounceIn 2s infinite;
        }
        .container p {
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(50%);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @keyframes bounceIn {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-30px);
            }
            60% {
                transform: translateY(-15px);
            }
        }
        .confetti {
            position: absolute;
            width: 10px;
            height: 10px;
            background-color: #fff;
            opacity: 0.7;
            animation: confetti-fall linear infinite;
        }
        @keyframes confetti-fall {
            0% {
                transform: translateY(-100vh) rotate(0deg);
            }
            100% {
                transform: translateY(100vh) rotate(720deg);
            }
        }
    </style>

    <title>Exam End</title>
</head>
<body>
<div class="container">
    <h2>Congratulations!</h2>
    <p>You've successfully completed the test!</p>
    <p>Your <strong>score</strong> is <?= htmlspecialchars($score); ?></p>
</div>

<!-- Confetti -->
<script>
    const confettiCount = 200;
    const container = document.querySelector('body');

    for (let i = 0; i < confettiCount; i++) {
        const confetti = document.createElement('div');
        confetti.className = 'confetti';
        confetti.style.left = `${Math.random() * 100}vw`;
        confetti.style.animationDuration = `${Math.random() * 3 + 2}s`;
        container.appendChild(confetti);
    }
</script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
