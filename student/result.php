<?php
include "../config.php";
session_start();

// Assume these values are provided by the session or form
$examId = $_SESSION['exam_id'] ?? null;
$admitCardId = $_SESSION['admit_card_id'] ?? null;

if (!$examId || !$admitCardId) {
    die("Exam ID or Admit Card ID not provided.");
}

// Query to fetch user attempts and calculate score
$sql = "SELECT 
            COUNT(*) AS total_questions,
            SUM(is_correct) AS total_correct
        FROM 
            user_attempts
        WHERE 
            admit_card_id = ? AND exam_id = ?";

$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
}

$stmt->bind_param("ii", $admitCardId, $examId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$total_questions = $row['total_questions'] ?: 0; // Avoid null, default to 0
$total_correct = $row['total_correct'] ?: 0; // Avoid null, default to 0
$total_incorrect = $total_questions - $total_correct; // Calculate total incorrect

// Calculate score
$score = $total_questions > 0 ? ($total_correct / $total_questions) * 100 : 0;

// Determine result text and color based on score
if ($score >= 55) {
    $resultText = "Pass";
    $resultColor = "#28a745"; // Green color
} elseif ($score >= 45 && $score <= 54) {
    $resultText = "Average";
    $resultColor = "#ffc107"; // Yellow color
} else {
    $resultText = "Failed";
    $resultColor = "#dc3545"; // Red color
}

// Query to fetch incorrect questions and user selected options
$sqlIncorrect =  "SELECT 
    q.question_number as question_id,
    q.question_text,
    q.description,
    ua.selected_option_id,
    (SELECT coption FROM options WHERE question_number = q.question_number AND id = ua.selected_option_id LIMIT 1) AS selected_option,
    (SELECT coption FROM options WHERE question_number = q.question_number AND is_correct = 1 LIMIT 1) AS correct_option
FROM 
    questions q
JOIN
    user_attempts ua ON q.question_number = ua.question_id
WHERE 
    q.exam_id = ?
    AND ua.is_correct = 0
    AND ua.exam_id = ?
    AND ua.admit_card_id = ?
ORDER BY 
    q.question_number";

$stmtIncorrect = $conn->prepare($sqlIncorrect);
if ($stmtIncorrect === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
}

$stmtIncorrect->bind_param("iii", $examId, $examId, $admitCardId);
$stmtIncorrect->execute();
$resultIncorrect = $stmtIncorrect->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Results</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: whitesmoke;
            color: #000;
            font-family: 'Arial', sans-serif;
            text-align: center;
        }
        .container {
            max-width: 900px;
            margin: 100px auto;
            position: relative;
            z-index: 0;
            padding: 20px;
            border-radius: 10px;
        }
        .card {
            background: linear-gradient(135deg, #89f7fe 0%, #66a6ff 100%);
            box-shadow: -10px 9px 32px -8px rgba(0,0,0,0.75);
            border-radius: 10px;
            padding: 20px;
        }
        .score {
            font-size: 3rem;
            font-weight: bold;
            color: <?php echo $resultColor; ?>;
            animation: bounce 1.5s infinite;
        }
        .details {
            font-size: 1.5rem;
            color: #000;
        }
        canvas {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
        }
        .btn-primary {
            background: linear-gradient(135deg, #36d1dc 0%, #5b86e5 100%);
            border: none;
            padding: 10px 20px;
            border-radius: 20px;
            font-size: 1.2rem;
            transition: background 0.3s ease;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #5b86e5 0%, #36d1dc 100%);
        }
        @keyframes bounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }
    </style>
</head>
<body>
<canvas id="canvas"></canvas>
    <div class="container">
        <div class="card ">
            <div class="card-body">
                <h3 class="card-title">Congratulations!</h3>
                <p class="score"><?php echo round($score, 2); ?>%</p>
                <div class="details">
                    <div class="row">
                        <p class="col-lg-4">Total Questions: <?php echo $total_questions; ?></p>
                        <p class="col-lg-4">Correct Answers: <?php echo $total_correct; ?></p>
                        <p class="col-lg-4">Incorrect Answers: <?php echo $total_incorrect; ?></p>
                    </div>
                </div>
                <hr>
                <?php if ($resultIncorrect->num_rows > 0): ?>
                    <h4>Incorrect Questions:</h4>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Question Text</th>
                                    <th>Your Answer</th>
                                    <th>Correct Option</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($rowIncorrect = $resultIncorrect->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($rowIncorrect['question_text']); ?></td>
                                        <td class="text-danger"><?php echo htmlspecialchars($rowIncorrect['selected_option']); ?></td>
                                        <td><?php echo htmlspecialchars($rowIncorrect['correct_option']); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p>No incorrect questions found.</p>
                <?php endif; ?>
                <a href="index.php" class="btn btn-primary">Go to Dashboard</a>
            </div>
        </div>
    </div>
</body>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        const canvas = document.getElementById('canvas');
        const ctx = canvas.getContext('2d');

        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;

        class Particle {
            constructor(x, y, hue) {
                this.x = x;
                this.y = y;
                this.angle = Math.random() * Math.PI * 2;
                this.speed = Math.random() * 5 + 1;
                this.gravity = 0.05;
                this.friction = 0.99;
                this.alpha = 1;
                this.hue = hue;
                this.brightness = Math.random() * 20 + 50;
                this.decay = Math.random() * 0.015 + 0.015;
                this.coordinates = [];
                this.coordinateCount = 5;

                while (this.coordinateCount--) {
                    this.coordinates.push([this.x, this.y]);
                }
            }

            update() {
                this.coordinates.pop();
                this.coordinates.unshift([this.x, this.y]);
                this.speed *= this.friction;
                this.x += Math.cos(this.angle) * this.speed;
                this.y += Math.sin(this.angle) * this.speed + this.gravity;
                this.alpha -= this.decay;
            }

            draw() {
                ctx.beginPath();
                ctx.moveTo(this.coordinates[this.coordinates.length - 1][0], this.coordinates[this.coordinates.length - 1][1]);
                ctx.lineTo(this.x, this.y);
                ctx.strokeStyle = `hsla(${this.hue}, 100%, ${this.brightness}%, ${this.alpha})`;
                ctx.stroke();
            }
        }

        class Firework {
            constructor(startX, startY, targetX, targetY) {
                this.x = startX;
                this.y = startY;
                this.targetX = targetX;
                this.targetY = targetY;
                this.angle = Math.atan2(targetY - startY, targetX - startX);
                this.speed = 2;
                this.acceleration = 1.05;
                this.brightness = Math.random() * 50 + 50;
                this.coordinates = [];
                this.coordinateCount = 3;
                this.hue = Math.random() * 360;
                this.particles = [];
                this.hasExploded = false;

                while (this.coordinateCount--) {
                    this.coordinates.push([this.x, this.y]);
                }
            }

            update(index) {
                if (!this.hasExploded) {
                    this.coordinates.pop();
                    this.coordinates.unshift([this.x, this.y]);

                    this.speed *= this.acceleration;
                    this.x += Math.cos(this.angle) * this.speed;
                    this.y += Math.sin(this.angle) * this.speed;

                    if (Math.abs(this.x - this.targetX) < this.speed && Math.abs(this.y - this.targetY) < this.speed) {
                        this.explode();
                        this.hasExploded = true;
                    }
                }

                if (this.hasExploded) {
                    this.particles.forEach((particle, i) => {
                        particle.update();
                        if (particle.alpha <= particle.decay) {
                            this.particles.splice(i, 1);
                        }
                    });

                    if (this.particles.length === 0) {
                        fireworks.splice(index, 1);
                    }
                }
            }

            explode() {
                for (let i = 0; i < 30; i++) {
                    this.particles.push(new Particle(this.x, this.y, this.hue));
                }
            }

            draw() {
                if (!this.hasExploded) {
                    ctx.beginPath();
                    ctx.moveTo(this.coordinates[this.coordinates.length - 1][0], this.coordinates[this.coordinates.length - 1][1]);
                    ctx.lineTo(this.x, this.y);
                    ctx.strokeStyle = `hsl(${this.hue}, 100%, ${this.brightness}%)`;
                    ctx.stroke();
                }

                if (this.hasExploded) {
                    this.particles.forEach(particle => particle.draw());
                }
            }
        }

        const fireworks = [];
        const random = (min, max) => Math.random() * (max - min) + min;
        
        function animate() {
            ctx.globalCompositeOperation = 'destination-out';
            ctx.fillStyle = 'rgba(0, 0, 0, 0.5)';
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            ctx.globalCompositeOperation = 'lighter';

            if (Math.random() < 0.05) {
                fireworks.push(new Firework(
                    canvas.width / 2,
                    canvas.height,
                    random(0, canvas.width),
                    random(0, canvas.height / 2)
                ));
            }

            fireworks.forEach((firework, index) => {
                firework.update(index);
                firework.draw();
            });

            requestAnimationFrame(animate);
        }

        animate();
        window.addEventListener('resize', () => {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        });
    </script>
</body>
</html>

<?php
// Close connection
$conn->close();
?>
