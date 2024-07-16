<?php
include "../config.php";
session_start();

// Set timezone to IST
date_default_timezone_set('Asia/Kolkata');

$sequence_no = $_POST['sequence_no'] ?? null;
$dob = $_POST['dob'] ?? null;

if (!$sequence_no || !$dob) {
    die("Please provide all login details.");
}

// Fetch admit card details
$sql = "SELECT * FROM admit_cards WHERE sequence_no = ? AND dob = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $sequence_no, $dob);
$stmt->execute();
$result = $stmt->get_result();
$admitCard = $result->fetch_assoc();

if (!$admitCard) {
    die("Invalid credentials.");
}

// Fetch exam details 
$exam_id = $admitCard['exam_id'];
$sql = "SELECT * FROM exams WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $exam_id);
$stmt->execute();
$result = $stmt->get_result();
$exam = $result->fetch_assoc();

if (!$exam) {
    die("Exam not found.");
}

// Check Exam Timing
$current_datetime = new DateTime();
$exam_date = new DateTime($exam['exam_date']);
$start_datetime = new DateTime($exam_date->format('Y-m-d') . ' ' . $exam['start_time']);
$end_datetime = new DateTime($exam_date->format('Y-m-d') . ' ' . $exam['end_time']);


$current_time = $current_datetime->format('Y-m-d H:i:s');
$start_time = $start_datetime->format('Y-m-d H:i:s');
$end_time = $end_datetime->format('Y-m-d H:i:s');


if ($current_datetime < $start_datetime || $current_datetime > $end_datetime) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Exam Timing Details</title>
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body {
                background-color: #f8f9fa;
                font-family: Arial, sans-serif;
                display: flex;
                align-items: center;
                justify-content: center;
                height: 100vh;
                margin: 0;
            }
            .container{
                justify-content: center;
                align-items: center;
                display: flex;
                
            }
            .card {
                width: 90%;
                max-width: 600px;
                text-align: center;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                border: none;
                position: relative;
            }
            .card-header {
                color: black;
                background-color: skyblue;
                font-size: 1.5rem;
                border-radius: 5px 5px 0 0;
            }
            .card-body {
                padding: 30px;
                background-color: white;
                border-radius: 0 0 5px 5px;
            }
            .card-footer {
                background-color: #f1f1f1;
                border-radius: 0 0 5px 5px;
                padding: 10px;
            }
            .bg-icon {
                position: absolute;
                opacity: 0.05;
                font-size: 12rem;
                top: 20px;
                right: 20px;
            }
        </style>
    </head>
    <body>

    <div class="container ">
        <div class="card shadow-lg">
            <div class="card-header">
                Exam Timing Details
            </div>
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-calendar-alt text-primary"></i> Current Time</h5>
                <p class="card-text"><span id="current_time"><?php echo $current_time; ?></span></p>
                <h5 class="card-title"><i class="fas fa-clock text-success"></i> Exam Start Time</h5>
                <p class="card-text text-primary"><?php echo $start_time; ?></p>
                <h5 class="card-title"><i class="fas fa-hourglass-end text-danger"></i> Exam End Time</h5>
                <p class="card-text text-danger"><?php echo $end_time; ?></p>
            </div>
            <div class="card-footer">
                Make sure to be on time!
            </div>
            <i class="bg-icon fas fa-alarm-clock"></i>
        </div>
    </div>

    <script>
        function updateTime() {
            const currentTimeElement = document.getElementById('current_time');
            const currentTimeString = currentTimeElement.textContent;
            let currentDate = new Date(currentTimeString);
            
            currentDate.setSeconds(currentDate.getSeconds() + 1);
            currentTimeElement.textContent = currentDate.toLocaleString();
        }
        setInterval(updateTime, 1000);
    </script>
    </body>
    </html>
    <?php
    exit();
}

// Redirect to disclaimer
$_SESSION['exam_id'] = $exam_id;
$_SESSION['admit_card_id'] = $admitCard['id'];
$examId = $_SESSION['exam_id'];
$sequence_no = $_SESSION['sequence_no'];
$check_sql = "SELECT COUNT(*) AS cnt FROM user_attempts WHERE id = '$admitCardId' AND exam_id = '$examId'";
$check_result = mysqli_query($conn, $check_sql);
$check_row = mysqli_fetch_assoc($check_result);

if ($check_row['cnt'] > 0) {
    echo "<script>alert('Exam already submitted.'); window.location.href = 'result.php';</script>";
    exit();
}
echo "<script>alert('Redirecting to disclaimer.'); window.location.href = 'disclaimer.php';</script>";

exit();
?>