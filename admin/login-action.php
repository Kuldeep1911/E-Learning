<?php
include("../config.php");
session_start();

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $sql = "select * from tb_users where email='$email' and password='$password'";
    $results = mysqli_query($conn, $sql);

    if (mysqli_num_rows($results) > 0) {
        $row = mysqli_fetch_array($results);
        $_SESSION['logged'] = '1';
        $_SESSION['user_type'] = $row['user_type'];
        $_SESSION['user_email'] = $row['email'];
        echo 'success';
    } else {
        echo 'Invalid email or password.';
    }
}
?>
