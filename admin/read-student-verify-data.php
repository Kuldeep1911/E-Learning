<?php
session_start();
include("../config.php");

// Check if user is logged in
if (empty($_SESSION['user_email'])) {
    echo "<script>window.location.href='index.php';</script>";
    exit();
}


$id = $_GET['uid'];

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
    <title>Student Management || Gravity</title>
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
        <div class="content-body">
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
                            Manage student</a>
                    </li>
                </ol>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">

                        <div class="card-body p-0">
                            <div class="table-responsive active-projects style-1">
                                <div class="tbl-caption">
                                    <h4 class="heading mb-0">Student Verification Data</h4>
                                    <div>
                                    </div>
                                </div>

                                <table id="students-verification-table" class="table">
                                    <thead>
                                        <tr>
                                            <th>S.no.</th>
                                            <th>Student ID</th>
                                            <th>Profile Image</th>
                                            <th>Student Name</th>
                                            <th>Father's Name</th>
                                            <th>Mother's Name</th>
                                            <th>Aadhar Number</th>
                                            <th>Aadhar Front</th>
                                            <th>Aadhar Back</th>
                                            <th>Payment Status</th>
                                            <th>Qualification Details</th>
                                            <th>Passing Year</th>
                                            <th>Board</th>
                                            <th>Marks Obtained</th>
                                            <th>Subject</th>
                                            <th>Verification Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $count = 1;

                                        // Fetch data from tb_students
                                        $students_query = "SELECT * FROM tb_students where sid='$id'";
                                        $students_result = mysqli_query($conn, $students_query);

                                        while ($student = mysqli_fetch_array($students_result)) {
                                            $sid = $student['sid'];

                                            // Fetch data from tb_aadhar
                                            $aadhar_query = "SELECT * FROM tb_aadhar WHERE uid = '$sid'";
                                            $aadhar_result = mysqli_query($conn, $aadhar_query);
                                            $aadhar = mysqli_fetch_array($aadhar_result);

                                            // Fetch data from tb_payment
                                            $payment_query = "SELECT * FROM tb_payments WHERE sid = '$sid'";
                                            $payment_result = mysqli_query($conn, $payment_query);
                                            $payment = mysqli_fetch_array($payment_result);

                                            // Fetch data from tb_qualification
                                            $qualification_query = "SELECT * FROM tb_qualification WHERE uid = '$sid'";
                                            $qualification_result = mysqli_query($conn, $qualification_query);
                                            $qualification = mysqli_fetch_array($qualification_result);

                                            // Fetch data from tb_verification
                                            $verification_query = "SELECT * FROM tb_verification WHERE uid = '$sid'";
                                            $verification_result = mysqli_query($conn, $verification_query);
                                            $verification = mysqli_fetch_array($verification_result);
                                        ?>
                                            <tr>
                                                <td><?php echo $count; ?></td>
                                                <td><?php echo "GPS" . $student['sid']; ?></td>
                                                <td><img src="<?php echo $student['profile_image']; ?>" alt="Profile Image" width="50" height="50"></td>
                                                <td><?php echo $student['student_name']; ?></td>
                                                <td><?php echo $student['father_name']; ?></td>
                                                <td><?php echo $student['mother_name']; ?></td>
                                                <td><?php echo isset($aadhar['aadhar_number']) ? $aadhar['aadhar_number'] : 'N/A'; ?></td>
                                                <td><img src="../img/<?php echo isset($aadhar['aadhar_front']) ? $aadhar['aadhar_front'] : 'assets/images/default-aadhar-front.jpg'; ?>" alt="Aadhar Front" width="50" height="50"></td>
                                                <td><img src="../img<?php echo isset($aadhar['aadhar_back']) ? $aadhar['aadhar_back'] : 'assets/images/default-aadhar-back.jpg'; ?>" alt="Aadhar Back" width="50" height="50"></td>
                                                <td><?php echo  $payment['payment_status'] ?></td>
                                                <td><?php echo $qualification['detail']  ?></td>
                                                <td><?php echo $qualification['passing_year']  ?></td>
                                                <td><?php echo $qualification['board']  ?></td>
                                                <td><?php echo $qualification['marks_obtained']  ?></td>
                                                <td><?php echo $qualification['subject']  ?></td>
                                                <td><?php echo $verification['verification_status']  ?></td>

                                            </tr>
                                        <?php
                                            $count++;
                                        }
                                        ?>
                                    </tbody>
                                </table>
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
                            <p>Copyright © Developed by <a href="#" target="_blank"></a> <?php echo date('Y') ?></p>
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
                <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
</body>

</html>