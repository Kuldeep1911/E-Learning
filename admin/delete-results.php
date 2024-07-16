<?php
session_start();
include("../config.php");
if (empty($_SESSION['user_email'])) {
    echo "<script>window.location.href='index.php';</script>";
}
$uid = $_GET['roll_no'];
$semester = $_GET['sem'];
$course = $_GET['course'];
$sql = "delete from tb_results where sid='$uid' and course_name='$course' and semester='$semester'";
$result = mysqli_query($conn,$sql);
if($result){
$del_tb_last_row = "delete from tb_last_result where sid='$uid' and course_id='$course' and semester ='$semester'";
$del_exec = mysqli_query($conn,$del_tb_last_row);
if($del_exec){
    echo '<script>alert("Result Deleted Successfully")</script>';
    echo '<script>window.location.href="results.php"</script>';
    }else{
        echo '<script>alert("Error occured")</script>';
        echo '<script>window.location.href="results.php"</script>';

    }
}else{
    echo "<script>alert('Please make sure that data exists');</script>";
}



?>