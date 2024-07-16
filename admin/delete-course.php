<?php
session_start();
include("../config.php");
if (empty($_SESSION['user_email'])) {
    echo "<script>window.location.href='index.php';</script>";
}
if($_SESSION['user_type']=='1'){
	echo "<script>window.location.href='dashboard.php';</script>";
}
$id = $_GET['id'];
$sql = "delete from tb_courses where cid='$id'";
$result = mysqli_query($conn,$sql);
if($result){
    echo '<script>alert("Course Deleted Successfully")</script>';
    echo '<script>window.location.href="courses.php"</script>';
    }else{
        echo '<script>alert("Error occured")</script>';
        echo '<script>window.location.href="courses.php"</script>';

    }


?>