<?php
session_start();
include "../config.php";

if(empty($_SESSION['user_email'])){
    echo "<script>window.location.href='mark_attendance.php';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'];
    $attendance_date = $_POST['attendance_date'];
    $attendance_status = $_POST['attendance_status'];

    // Check if the attendance for the student on the given date already exists
    $sql_check = "SELECT * FROM tb_attendance WHERE student_id = ? AND attendance_date = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("is", $student_id, $attendance_date);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        // Attendance already marked for this date
        echo "<script>alert('Attendance for this date is already marked.'); window.location.href='attendence.php';</script>";
    } else {
        // Insert the attendance record
        $sql_insert = "INSERT INTO tb_attendance (student_id, attendance_date, attendance_status) VALUES (?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("iss", $student_id, $attendance_date, $attendance_status);

        if ($stmt_insert->execute()) {
            echo "<script>alert('Attendance marked successfully.'); window.location.href='attendence.php';</script>";
        } else {
            echo "<script>alert('Error marking attendance. Please try again.'); window.location.href='attendence.php';</script>";
        }
    }

    $stmt_check->close();
    $stmt_insert->close();
}

$conn->close();
?>
