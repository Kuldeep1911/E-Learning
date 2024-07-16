<?php
session_start();
include("../config.php");
if (empty($_SESSION['user_email'])) {
    echo "<script>window.location.href='index.php';</script>";
}
$uid = $_GET['req_id'];
$sql = "delete from tb_certifications where cerid='$uid'";
$result = mysqli_query($conn,$sql);
if($result){
    echo '<script>alert("Record Deleted Successfully")</script>';
    echo '<script>window.location.href="certificate-request.php"</script>';
}else{
    echo '<script>alert("Error")</script>';
    echo '<script>window.location.href="certificate-request.php"</script>';
}




?>