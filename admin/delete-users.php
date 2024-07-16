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
if(empty($_GET['id'])){
    echo "<script>window.location.href='users.php'</script>";
}else{
    $check_user ="select *from tb_users where id='$id'";
    $check_result = mysqli_query($conn,$check_user);
    if($check_result){
$sql = "delete from tb_users where id='$id'";
$result = mysqli_query($conn,$sql);
if($result){
    echo '<script>alert("User deleted Successfully")</script>';
    echo '<script>window.location.href="users.php"</script>';
    }else{
        echo '<script>alert("Error occured")</script>';
        echo '<script>window.location.href="users.php"</script>';

    }

}
else{
    echo '<script>alert("No User Found")</script>';
    echo '<script>window.location.href="users.php"</script>';
   
}
}


?>