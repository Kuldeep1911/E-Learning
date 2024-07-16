<?php
session_start();
include("../config.php");
if (empty($_SESSION['user_email'])) {
    echo "<script>window.location.href='index.php';</script>";
}
if($_SESSION['user_type']=='1'){
	echo "<script>window.location.href='dashboard.php';</script>";
}

$session_email = $_SESSION['user_email'];
if (isset($_POST['submit'])) {
    $fullName = $_POST['name'];
    $phoneNo = $_POST['phone'];
    $email = $_POST['email'];
    $pass = $_POST['pass'];
    $cnf_pass = $_POST['cnf_pass'];
    $user_type = $_POST['user_type'];

if($pass !== $cnf_pass){
    echo "<div class='alert alert-danger'>Passwords Do not match</div>";

}else{
    $test_email = "select *from tb_users where email='$email'";
    $email_result = mysqli_query($conn,$test_email);
    if(!mysqli_num_rows($email_result)>0){


$sql_fetch_user_name = "select *from tb_users where email='$session_email'";
$get_result = mysqli_query($conn, $sql_fetch_user_name);
$admin_row = mysqli_fetch_array($get_result);
$admin_name = $admin_row['name'];
// Create the SQL query
$sql = "INSERT INTO tb_users (name,email,phone,user_type,created_by,password) 
 VALUES ('$fullName','$email', '$phoneNo', '$user_type', '$admin_name', '$pass')";
$results = mysqli_query($conn, $sql);

if ($results) {
    // Query executed successfully
    echo "<script>alert('New user created successfully');</script>";
    echo "<script>window.location.href='users.php';</script>";
} else {
    // Error in executing the query
    echo "<script>alert('Failed to create new user')</script>";
    echo "Error: " . mysqli_error($conn);
}
} else{
    echo "<div class='alert alert-danger'>Email Already Exists,Try with new email</div>";
}
}
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="">
    <meta name="author" content="">
    <meta name="robots" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="W3crm:Customer Relationship Management Admin Bootstrap 5 Template">
    <meta property="og:title" content="W3crm:Customer Relationship Management Admin Bootstrap 5 Template">
    <meta property="og:description" content="W3crm:Customer Relationship Management Admin Bootstrap 5 Template">
    <meta property="og:image" content="social-image.png">
    <meta name="format-detection" content="telephone=no">

    <!-- PAGE TITLE HERE -->
    <title>Add Users</title>
    <!-- FAVICONS ICON -->
    <link rel="shortcut icon" type="image/png" href="assets/images/favicon.png">


    <link href="vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
    <link href="vendor/swiper/css/swiper-bundle.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet">
    <link href="vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="vendor/jvmap/jquery-jvectormap.css" rel="stylesheet">
    <link href="vendor/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet">

    <!-- tagify-css -->
    <link href="vendor/tagify/dist/tagify.css" rel="stylesheet">

    <!-- Style css -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">

</head>

<body data-typography="poppins" data-theme-version="light" data-layout="vertical" data-nav-headerbg="black" data-headerbg="color_1">


    <!--*******************
        Preloader start
    ********************-->
    <div id="preloader">
        <div class="lds-ripple">
            <div></div>
            <div></div>
        </div>
    </div>
    <!--*******************
        Preloader end
    ********************-->


    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">
        <!--**********************************
            Nav header start
        ***********************************-->
        <?php include("includes/header.php"); ?>
        <!--   Content body start
        ***********************************-->
        <div class="content-body ">
            <!-- row -->
            <div class="page-titles">
                <ol class="breadcrumb">
                    <li>
                        <h5 class="bc-title">Dashboard</h5>
                    </li>
                    <li class="breadcrumb-item"><a href="javascript:void(0)">
                            <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://WWW.w3.org/2000/svg">
                                <path d="M2.125 6.375L8.5 1.41667L14.875 6.375V14.1667C14.875 14.5424 14.7257 14.9027 14.4601 15.1684C14.1944 15.4341 13.8341 15.5833 13.4583 15.5833H3.54167C3.16594 15.5833 2.80561 15.4341 2.53993 15.1684C2.27426 14.9027 2.125 14.5424 2.125 14.1667V6.375Z" stroke="#2C2C2C" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M6.375 15.5833V8.5H10.625V15.5833" stroke="#2C2C2C" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            Manage Users</a>
                    </li>
                </ol>
            </div>
            <div class="row d-flex justify-content-center my-5">
                <div class="col-xl-9 col-lg-8">
                    <div class="card profile-card card-bx m-b30">
                        <div class="card-header">
                            <h6 class="title">Super Admin/AdminAccount setup </h6>
                        </div>
                        <form class="profile-form" method="post" action="#" enctype="multipart/form-data">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-6 m-b30">
                                        <label class="form-label">Full Name</label>
                                        <input type="text" class="form-control" name="name" required>
                                    </div>
                                    <div class="col-sm-6 m-b30">
                                        <label class="form-label">Phone No.</label>
                                        <input type="text" class="form-control" name="phone" required>
                                    </div>
                                    <div class="col-sm-6 m-b30">
                                        <label class="form-label">Email</label>
                                        <input type="text" class="form-control" name="email" required>
                                    </div>
                                    <div class="col-sm-6 m-b30">
                                        <label class="form-label">Password</label>
                                        <input type="text" class="form-control" name="pass" required>
                                    </div>
                                    <div class="col-sm-6 m-b30">
                                        <label class="form-label">Confirm Password</label>
                                        <input type="text" class="form-control" name="cnf_pass" required>
                                    </div>

                                    <div class="col-sm-6 m-b30">
                                        <label class="form-label">User Type</label>
                                        <select class="form-control" name="user_type" required>
                                            <option value="0">Super Admin</option>
                                            <option value="1">Admin</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer d-flex justify-content-end">
                                <button class="btn btn-primary" type="submit" name="submit">Add User</button>

                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>




        <!--**********************************
            Content body end
        ***********************************-->

        <!--**********************************
            Footer start
        ***********************************-->
        <div class="footer">
            <div class="copyright">
                <p>Copyright © Developed by <a href="#" target="_blank">Gravity Institute</a> <?php echo date('Y') ?></p>
            </div>
        </div>
        <!--**********************************
            Footer end
        ***********************************-->

        <!--**********************************
           Support ticket button start
        ***********************************-->

        <!--**********************************
           Support ticket button end
        ***********************************-->


    </div>
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
    <script src="vendor/global/global.min.js"></script>
    <script src="vendor/chart.js/Chart.bundle.min.js"></script>
    <script src="vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script>

    <!-- Dashboard 1 -->
    <script src="vendor/draggable/draggable.js"></script>


    <!-- tagify -->
    <script src="vendor/tagify/dist/tagify.js"></script>

    <script src="vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/js/dataTables.buttons.min.js"></script>
    <script src="vendor/datatables/js/buttons.html5.min.js"></script>
    <script src="vendor/datatables/js/jszip.min.js"></script>
    <script src="js/plugins-init/datatables.init.js"></script>

    <!-- Apex Chart -->

    <script src="vendor/bootstrap-datetimepicker/js/moment.js"></script>
    <script src="vendor/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>


    <!-- Vectormap -->
    <script src="vendor/jqvmap/js/jquery.vmap.min.js"></script>
    <script src="vendor/jqvmap/js/jquery.vmap.world.js"></script>
    <script src="vendor/jqvmap/js/jquery.vmap.usa.js"></script>
    <script src="js/custom.js"></script>
    <script src="js/deznav-init.js"></script>
    <script>
        function preview() {
            frame.src = URL.createObjectURL(event.target.files[0]);
        }
    </script>


</body>

</html>