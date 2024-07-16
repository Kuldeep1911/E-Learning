<?php
session_start();
include("../config.php");

// Check if user is logged in
if (empty($_SESSION['user_email'])) {
    echo "<script>window.location.href='index.php';</script>";
    exit();
}

// Check if student ID is provided
if (isset($_GET['uid'])) {
    $studentId = $_GET['uid'];

    // Update verification status to 1 (verified)
    $updateVerificationQuery = "UPDATE tb_students_Verify SET verification_status = 1 WHERE vid = '$studentId'";
    mysqli_query($conn, $updateVerificationQuery);

    // Fetch data for the provided student ID from tb_students_Verify
    $fetchDataQuery = "SELECT * FROM tb_students_Verify WHERE vid='$studentId'";
    $fetchResult = mysqli_query($conn, $fetchDataQuery);

    // Check if data is fetched
    if ($row = mysqli_fetch_array($fetchResult)) {
        // Extract column values
        $admissionDate = $row['admission_date'];
        $fullName = $row['student_name'];
        $fatherName = $row['father_name'];
        $motherName = $row['mother_name'];
        $email = $row['email'];
        $phoneNo = $row['phone_number1'];
        $alternatePhoneNo = $row['phone_number2'];
        $dob = $row['dob'];
        $address = $row['address'];
        $city = $row['city'];
        $state = $row['state'];
        $profileImg = $row['profile_img'];
        $course = $row['course'];
        $cid = $row['cid'];

        // Construct the INSERT query for tb_students
        $insertQuery = "INSERT INTO tb_students (admission_date, student_name, father_name, mother_name, email, phone_number1, phone_number2, dob, address, city, state, profile_img, course, cid) 
                        VALUES ('$admissionDate', '$fullName', '$fatherName', '$motherName', '$email', '$phoneNo', '$alternatePhoneNo', '$dob', '$address', '$city', '$state', '$profileImg', '$course', '$cid')";

        // Execute the INSERT query
        mysqli_query($conn, $insertQuery);
        echo ">script>alert('Student verified and inserted in main table ');</script>";
        // Redirect back to the student management page
        echo "<script>window.location.href='students.php';</script>";
        exit();
    } else {
        // No data found for the provided student ID
        echo "No data found for the provided student ID.";
        exit();
    }
} else {
    // Student ID not provided
    echo "Student ID not provided.";
    exit();
}
?>
