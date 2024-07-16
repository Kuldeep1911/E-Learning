<?php
session_start();
include("../config.php");

// Check if user is logged in
if (empty($_SESSION['user_email'])) {
    echo "<script>window.location.href='index.php';</script>";
    exit();
}

// Check if student ID is provided
if (isset($_GET['id'])) {
    $studentId = $_GET['id'];

    // Query to fetch student details
    $fetchStudentQuery = "SELECT * FROM tb_students_Verify WHERE vid = '$studentId'";
    $studentResult = mysqli_query($conn, $fetchStudentQuery);

    if (mysqli_num_rows($studentResult) > 0) {
        $studentData = mysqli_fetch_assoc($studentResult);

        // Delete student profile photo
        $photoPath = 'img/students/' . $studentData['profile_img'];
        if (file_exists($photoPath)) {
            unlink($photoPath);
        }

        

        // Delete student Aadhar details
        $fetchAadharQuery = "SELECT * FROM tb_aadhar WHERE uid = '$studentId'";
        $aadharResult = mysqli_query($conn, $fetchAadharQuery);
        if (mysqli_num_rows($aadharResult) > 0) {
            $aadharData = mysqli_fetch_assoc($aadharResult);
            // Delete Aadhar front image
            $aadharFrontPath = 'img/aadhar_front/' . $aadharData['aadhar_front'];
            if (file_exists($aadharFrontPath)) {
                unlink($aadharFrontPath);
            }
            // Delete Aadhar back image
            $aadharBackPath = 'img/aadhar_back/' . $aadharData['aadhar_back'];
            if (file_exists($aadharBackPath)) {
                unlink($aadharBackPath);
            }
            // Delete Aadhar details from database
            $deleteAadharQuery = "DELETE FROM tb_aadhar WHERE uid = '$studentId'";
            mysqli_query($conn, $deleteAadharQuery);
        }

   
        // Delete student record from tb_students
        $deleteStudentQuery = "DELETE FROM tb_students_Verify WHERE vid = '$studentId'";
        mysqli_query($conn, $deleteStudentQuery);
        echo "<script>window.location.href='students.php';</script>";


        // Redirect back to the student management page
        echo "<script>window.location.href='verify-students.php';</script>";
        exit();
    } else {
        // Student record not found
        echo "Student record not found.";
        exit();
    }
} else {
    // Student ID not provided
    echo "Student ID not provided.";
    exit();
}
?>
