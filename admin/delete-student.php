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
$get_pic = "select *from tb_students where sid='$id'";
$get_pic_result = mysqli_query($conn,$get_pic);
if(mysqli_num_rows($get_pic_result)>0){
$row_pic  = mysqli_fetch_array($get_pic_result);
if(unlink("../img/students/".$row_pic['profile_img'])) {
$sql = "delete from tb_students where sid='$id'";
$result = mysqli_query($conn,$sql);
$delete_payments = "delete from tb_payments where sid='$id'";
$delete_result = mysqli_query($conn,$delete_payments);
$delete_tb_result= "delete from tb_results where sid='$id'";
$delete_exec_result = mysqli_query($conn,$delete_tb_result);
/*delte fro last <row></row>
*/
$delete_last_row ="delete from tb_last_result where sid='$id'";
$delete_exec = mysqli_query($conn,$delete_last_row);
/*delte fro last <row></row>
*/

$delete_join = "DELETE from tb_qualification
WHERE uid='$id'";
$delete_join_result = mysqli_query($conn,$delete_join);
if($delete_join_result){
    echo '<script>alert("Student record Deleted Successfully")</script>';
    echo '<script>window.location.href="students.php"</script>';
    }else{
        echo '<script>alert("Error occured")</script>';
        echo '<script>window.location.href="students.php"</script>';
     }
}
}else{
    echo "<script>alert('No user found')</script>";
    echo "<script>window.location.href='students.php'</script>";
}


?>