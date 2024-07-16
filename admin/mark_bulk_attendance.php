<?php
session_start();
include "../config.php";

if (empty($_SESSION['user_email'])) {
    echo "<script>window.location.href='index.php';</script>";
}

if (isset($_POST['student_ids'], $_POST['attendance_date'], $_POST['attendance_status'])) {
    $studentIds = explode(',', $_POST['student_ids']);
    $attendanceDate = $_POST['attendance_date'];
    $attendanceStatus = $_POST['attendance_status'];

    // Prepare the SQL statement outside the loop
    $sql_select = "SELECT COUNT(*) AS count FROM tb_attendance WHERE student_id = ? AND attendance_date = ?";
    $stmt_select = $conn->prepare($sql_select);
    $stmt_select->bind_param('is', $studentId, $attendanceDate);

    // Prepare the SQL statement for insertion/update
    $sql_insert_update = "INSERT INTO tb_attendance (student_id, attendance_date, attendance_status) VALUES (?, ?, ?)
                        ON DUPLICATE KEY UPDATE attendance_status = ?";
    $stmt_insert_update = $conn->prepare($sql_insert_update);
    $stmt_insert_update->bind_param('isss', $studentId, $attendanceDate, $attendanceStatus, $attendanceStatus);

    if (!$stmt_select || !$stmt_insert_update) {
        die('Error preparing statement: ' . $conn->error);
    }

    foreach ($studentIds as $studentId) {
        // Check if attendance already marked
        $stmt_select->execute();
        $result = $stmt_select->get_result();
        $row = $result->fetch_assoc();

        if ($row['count'] > 0) {
            // Attendance already marked
            echo "<script>alert('Attendance for student ID $studentId on $attendanceDate is already marked.');</script>";
        } else {
            // Insert or update attendance
            $stmt_insert_update->execute();
        }
    }

    echo "<script>alert('Attendance marked successfully!'); window.location.href='attendence.php';</script>";
} else {
    echo "<script>alert('Failed to mark attendance. Please try again.'); window.location.href='attendence.php';</script>";
}
?>
