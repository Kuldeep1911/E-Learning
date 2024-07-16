<?php
session_start();
include "../config.php";

if (!isset($_SESSION['student_id'])) {
    die("Student not logged in");
}

$student_id = $_SESSION['student_id']; // Fetch the student ID from the session

$year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
$month = isset($_GET['month']) ? (int)$_GET['month'] : date('m');

// Fetch attendance records
$sql = "SELECT attendance_date, attendance_status FROM tb_attendance WHERE student_id = ? AND MONTH(attendance_date) = ? AND YEAR(attendance_date) = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Error preparing statement: " . htmlspecialchars($conn->error));
}

$stmt->bind_param('iii', $student_id, $month, $year);
$stmt->execute();

if ($stmt->errno) {
    die("Error executing statement: " . htmlspecialchars($stmt->error));
}

$result = $stmt->get_result();

if ($result === false) {
    die("Error fetching result: " . htmlspecialchars($conn->error));
}

$attendance_records = array();
while ($row = $result->fetch_assoc()) {
    $day = (int)date('j', strtotime($row['attendance_date']));
    $attendance_records[$day] = $row['attendance_status'];
}

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Attendance Summary</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: #f0f2f5;
            font-family: 'Arial', sans-serif;
        }
        .navbar {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .container {
            margin-top: 30px;
        }
        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
        }
        .card-body {
            padding: 20px;
        }
        h2 {
            color: #333;
        }
        .form-inline {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
        }
        .form-control {
            margin-right: 10px;
            border-radius: 8px;
        }
        .btn-primary {
            border-radius: 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 0.9rem;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
            transition: background-color 0.2s;
        }
        th {
            background-color: #f8f9fa;
            color: #555;
        }
        .present {
            background-color: #c8e6c9;
        }
        .absent {
            background-color: #ffcdd2;
        }
        .leave {
            background-color: #fff9c4;
        }
        .no-record {
            background-color: #f0f2f5;
        }
        td {
            cursor: pointer;
        }
        td:hover {
            background-color: #f8f9fa;
        }
        .loader {
            display: none;
            width: 3rem;
            height: 3rem;
            border: 5px solid #f0f2f5;
            border-top-color: #007bff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 50px auto;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Student Portal</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item active">
                <a class="nav-link" href="student_dashboard.php">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Courses</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Profile</a>
            </li>
            <li class="nav-item mr-5">
                <a class="nav-link" href="student_attendance.php">Attendance</a>
            </li>
            <li class="nav-item">
                <a href="index.php" class="nav-link btn btn-transparent text-dark">Login</a>
            </li>
        </ul>
    </div>
</nav>
<div class="container">
    <div class="card">
        <div class="card-body">
            <h2 class="text-center mb-4">Student Attendance Summary</h2>
            <form id="filter-form" class="form-inline justify-content-center mb-4">
                <label for="month" class="mr-2">Month:</label>
                <select id="month" class="form-control mr-2" name="month">
                    <?php
                    for ($i = 1; $i <= 12; $i++) {
                        $selected = ($i == $month) ? 'selected' : '';
                        echo "<option value='$i' $selected>" . date('F', mktime(0, 0, 0, $i, 1)) . "</option>";
                    }
                    ?>
                </select>
                <label for="year" class="mr-2">Year:</label>
                <select id="year" class="form-control mr-2" name="year">
                    <?php
                    $current_year = date('Y');
                    for ($i = $current_year - 5; $i <= $current_year; $i++) {
                        $selected = ($i == $year) ? 'selected' : '';
                        echo "<option value='$i' $selected>$i</option>";
                    }
                    ?>
                </select>
                <button type="submit" class="btn btn-primary">Filter</button>
            </form>
            <div id="calendar-container">
                <div class="loader" id="loader"></div>
                <?php
                function generate_calendar($year, $month, $attendance) {
                    $first_day = mktime(0, 0, 0, $month, 1, $year);
                    $days_in_month = date('t', $first_day);
                    $first_day_of_week = date('w', $first_day);

                    echo "<table>";
                    echo "<tr><th>Sun</th><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th></tr>";
                    echo "<tr>";

                    for ($i = 0; $i < $first_day_of_week; $i++) {
                        echo "<td></td>";
                    }

                    for ($day = 1; $day <= $days_in_month; $day++) {
                        if (($i % 7) == 0) {
                            echo "</tr><tr>";
                        }
                        $class = isset($attendance[$day]) ? get_class_from_status($attendance[$day]) : 'no-record';
                        echo "<td class='$class'>$day</td>";
                        $i++;
                    }

                    while (($i % 7) != 0) {
                        echo "<td></td>";
                        $i++;
                    }
                    echo "</tr>";
                    echo "</table>";
                }

                function get_class_from_status($status) {
                    switch ($status) {
                        case 'present':
                            return 'present';
                        case 'absent':
                            return 'absent';
                        case 'leave':
                            return 'leave';
                        default:
                            return 'no-record';
                    }
                }

                generate_calendar($year, $month, $attendance_records);
                ?>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        $('#filter-form').on('submit', function(event) {
            event.preventDefault();
            var month = $('#month').val();
            var year = $('#year').val();
            $('#loader').show();
            $.ajax({
                url: 'student_attendance.php',
                method: 'GET',
                data: {
                    month: month,
                    year: year
                },
                success: function(data) {
                    $('#calendar-container').html($(data).find('#calendar-container').html());
                    $('#loader').hide();
                }
            });
        });
    });
</script>
</body>
</html>
