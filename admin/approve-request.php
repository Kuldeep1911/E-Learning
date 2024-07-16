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
$sql = "select verified from tb_certifications  where cerid='$id'";
$result = mysqli_query($conn,$sql);
if(mysqli_num_rows($result)>0){
    $row = mysqli_fetch_array($result);
    $state = $row['verified'];
    if($state=='0'){
        $update_sql = "update tb_certifications set verified='1' where cerid='$id'";
        $update_result = mysqli_query($conn,$update_sql);
        if($update_result){
            echo '<script>alert("Certficate Status updated")</script>';
            echo '<script>window.location.href="certificate-request.php"</script>';
        }else{
            echo "<script>alert('Error updating the certificate request')</script>";
            echo '<script>window.location.href="certificate-request.php"</script>';
        }

    }else{
        $update_sql = "update tb_certifications set verified='0' where cerid='$id'";
        $update_result = mysqli_query($conn,$update_sql);
        if($update_result){
            echo '<script>alert("Certficate Status updated")</script>';
            echo '<script>window.location.href="certificate-request.php"</script>';
        }else{
            echo "<script>alert('Error updating the certificate request')</script>";
            echo '<script>window.location.href="certificate-request.php"</script>';
        }

    }
    
    }else{
        echo '<script>alert("No certificate request found")</script>';
        echo '<script>window.location.href="certificate-request.php"</script>';

    }


?>