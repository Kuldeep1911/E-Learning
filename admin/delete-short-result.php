<?php
session_start();
include("../config.php");
if (empty($_SESSION['user_email'])) {
    echo "<script>window.location.href='index.php';</script>";
}
$uid = $_GET['roll_no'];
$course = $_GET['course'];
$sql = "delete from tb_single_result where sid='$uid' and course_name='$course'";
$result = mysqli_query($conn,$sql);
if($result){
$del_tb_last_row = "delete from tb_last_single_result where sid='$uid' and course_id='$course' ";
$del_exec = mysqli_query($conn,$del_tb_last_row);
if($del_exec){
    echo '<script>alert("Result Deleted Successfully")</script>';
    echo '<script>window.location.href="short-result.php"</script>';
    }else{
        echo '<script>alert("Error occured")</script>';
        echo '<script>window.location.href="short-result.php"</script>';

    }
}else{
    echo "<script>alert('Please make sure that data exists');</script>";
    echo '<script>window.location.href="short-result.php"</script>';
}



?>