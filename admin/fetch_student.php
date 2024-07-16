<?php
// Assuming you have established a database connection
include("../config.php");
// Fetching the roll number from the AJAX request
$rollNo = $_POST['roll_no'];

// Querying the database based on the roll number
$query = "SELECT * FROM tb_students WHERE sid = '$rollNo'";
$result = mysqli_query($conn, $query); // Assuming you are using MySQLi for database connectivity

// Checking if the query was successful
if ($result && mysqli_num_rows($result) > 0) {
    // Fetching the student details
    $student = mysqli_fetch_assoc($result);
    
    // Preparing the response as an array
    $response = array(
        'total_sem' => $student['course'],
        'student'=>$student['student_name'],
        'father'=>$student['father_name'],
        'course_name'=>$student['course'],
        'course_id'=>$student['cid'],
        
    );
    
    // Sending the JSON response back to the AJAX request
    echo json_encode($response);
} else {
    // If no student is found, send an empty response
    echo json_encode(null);
}
?>
