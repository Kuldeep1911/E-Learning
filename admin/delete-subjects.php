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
$sql = "delete from tb_subjects where subid='$id'";
$result = mysqli_query($conn,$sql);
if($result){
    echo '<script>alert("Subject deleted Successfully")</script>';
    echo '<script>window.location.href="subjects.php"</script>';
    }else{
        echo '<script>alert("Error occured")</script>';
        echo '<script>window.location.href="subjects.php"</script>';

    }


?>