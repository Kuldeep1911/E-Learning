<?php
include("../config.php");
session_start();
if(isset($_SESSION['user_email'])){
    echo "<script>window.location.href='dashboard.php';</script>";
}

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
        $_SESSION['username'] = $row['name'];
        $_SESSION['branch'] = $row['branch'];
        $_SESSION['exam_id'] =$row['exam_id'];

        echo '<script>window.location.href="dashboard.php"</script>';
    } else {
        echo '<div class="alert alert-danger " id="error-message" role="alert">Please try again</div>';
    }
}

?>
<!DOCTYPE html>
<html>
<head>
  <title>Login Form</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    
  </style>
</head>
<body style="height:100vh" class="d-flex justify-content-center align-items-center flex-column">
<h1 class="text-center fw-bold">(Part of EduManage Pro)</h1>

  <div  style="max-width:540px;" class="container p-5  login-form shadow p-3 rounded">
    <form id="loginForm" method="post" action="#">
    <h2 class="text-center">Login Now</h2>
      <div class="mb-3">
        <input type="text" class="form-control" name="email" placeholder="Email">
      </div>
      <div class="mb-3">
        <input type="password" class="form-control" name="password" placeholder="Password">
      </div>
      <div class="d-flex justify-content-end">
         <button type="submit" name="submit" class="btn btn-primary">Submit</button>
      </div>
    </form>
 
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
 
</body>
</html>
