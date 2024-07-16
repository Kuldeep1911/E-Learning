<?php
session_start();
include("../config.php");

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $sql = "SELECT * FROM tb_students WHERE email='$email' AND password='$password'";
    $results = mysqli_query($conn, $sql);

    if (mysqli_num_rows($results) > 0) {
        $row = mysqli_fetch_array($results);
        $_SESSION['logged'] = '1';

        $_SESSION['user_email'] = $row['email'];

        $_SESSION['student_id'] = $row['sid'];
        $_SESSION['cid'] = $row['cid'];

        // print_r($_SESSION['cid']);
        // exit();

        echo '<script>window.location.href="dashboard/index.php"</script>';
    } else {
        echo '<div class="alert alert-danger" id="error-message" role="alert">Please try again</div>';
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Login Form</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            height: 100vh;
        }

        .login-form {
            max-width: 540px;
        }

        .btn-primary {
            width: 100%;
        }

        .form-container {
            height: 100vh;
        }

        .btn-transparent {
            width: 100%;
            background-color: transparent;
            color: #007bff;
            border: 1px solid #007bff;
            transition: background-color 0.3s, color 0.3s;
        }

        .btn-transparent:hover {
            background-color: #007bff;
            color: #fff;
        }
    </style>
</head>

<body class="d-flex justify-content-center align-items-center flex-column form-container">
    <h1 class="text-center fw-bold">STUDENT LOGIN</h1>
    <div class="container p-5 login-form shadow p-3 rounded">
        <form id="loginForm" method="post" action="#">
            <h2 class="text-center">Login Now</h2>
            <div class="form-group">
                <input type="text" class="form-control" name="email" placeholder="Email" required>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Password" required>
            </div>
            <div class="row">
                <div class="col-6 d-flex justify-content-start">
                    <a href="student-registration.php" class="btn btn-transparent">Register</a>
                </div>
                <div class="col-6 d-flex justify-content-end">
                    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>