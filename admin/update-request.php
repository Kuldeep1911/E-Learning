<?php
session_start();
include("../config.php");
if (empty($_SESSION['user_email'])) {
    echo "<script>window.location.href='index.php';</script>";
}
if (isset($_GET['req_id'])) {
    $cerid = $_GET['req_id'];
    $check_user_exists = "select *from tb_certifications where cerid='$cerid'";
    $check_result = mysqli_query($conn, $check_user_exists);
    if (!mysqli_num_rows($check_result) > 0) {
        echo '<script>window.location.href="certificate-result.php"</script>';
    } else {
        $row = mysqli_fetch_array($check_result);
    }
}
if (!empty($_GET['req_id'])) {
    $cerid = $_GET['req_id'];
} else {
    echo '<script>window.location.href="certificate-request.php"</script>';
}


if(isset($_POST['submit'])){
    $grade = $_POST['grade'];
    $valid_from = $_POST['from'];
    $valid_to = $_POST['to'];

    $sql = "update tb_certifications set grade='$grade',valid_from='$valid_from',valid_to='$valid_to' where cerid='$cerid'";
    $result = mysqli_query($conn,$sql);
    if($result){
        echo '<script>alert("Request Updated ")</script>';
        echo '<script>window.location.href="certificate-request.php"</script>';
       
    }else{
        echo '<script>alert("Error")</script>';
        echo '<script>window.location.href="certificate-request.php"</script>';
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
    <title>Update Request</title>
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
                            Update Request</a>
                    </li>
                </ol>
            </div>
            <div class="row d-flex justify-content-center my-5">
                <div class="col-xl-12 col-lg-12">
                    <div class="card profile-card card-bx m-b30">
                        <div class="card-header">
                            <h6 class="title"><?php echo $row['student_name']?> Certificate Request for Course <?php echo $row['course_name']?></h6>
                        </div>
                        <form class="profile-form" method="post" action="#">
                            <div class="card-body">
                                <div class="row" id="form-fields">
                                    </h2>
                                    <?php
                                    $sub_res = "select *from tb_certifications where cerid='$cerid'";;
                                    $exec_sub = mysqli_query($conn, $sub_res);
                                    if (mysqli_num_rows($exec_sub)) {
                                        while ($res_row = mysqli_fetch_array($exec_sub)) {
                                    ?>

                                            <div class="col-sm-6 col-md-6 col-lg-4 m-b30">
                                                <label class="form-label">Grade</label>
                                                <select class="form-control" name="grade" required>
    <option value="A" <?php if ($res_row['grade'] == 'A') echo 'selected'; ?>>A</option>
    <option value="A+" <?php if ($res_row['grade'] == 'A+') echo 'selected'; ?>>A+</option>
    <option value="B" <?php if ($res_row['grade'] == 'B') echo 'selected'; ?>>B</option>
    <option value="B+" <?php if ($res_row['grade'] == 'B+') echo 'selected'; ?>>B+</option>
    <option value="C" <?php if ($res_row['grade'] == 'C') echo 'selected'; ?>>C</option>
    <option value="C+" <?php if ($res_row['grade'] == 'C+') echo 'selected'; ?>>C+</option>
    <option value="D" <?php if ($res_row['grade'] == 'D') echo 'selected'; ?>>D</option>
    <option value="D+" <?php if ($res_row['grade'] == 'D+') echo 'selected'; ?>>D+</option>
</select>

                                            </div>
                                            <div class="col-sm-6 col-md-6 col-lg-4 m-b30">
                                                <label class="form-label">Valid From</label>
                                                <input type="date" class="form-control" name="from" value="<?php echo $res_row['valid_from'] ?>" required>
                                            </div>
                                            <div class="col-sm-6 col-md-6 col-lg-4 m-b30">
                                                <label class="form-label">Valid To</label>
                                                <input type="date" class="form-control" name="to" placeholder="Enter the Course Name" value="<?php echo $res_row['valid_to'] ?>" required>
                                            </div>
                                    <?php }
                                    } ?>
                                </div>
                            </div>
                    
                    <div class="card-footer d-flex justify-content-end my-3">
                        <button class="btn btn-primary" type="submit" name="submit">Update Result</button>
                    </div>
                    </form>
                    </div>
                </div>
            </div>
            <?php 
            if (isset($_POST['submit'])) {
                // Get the form values
                $subjects = $_POST['subject'];
                $maxima = $_POST['maximum'];
                $minima = $_POST['minimum'];
                $externals = $_POST['external'];
                $uid = $_GET['roll_no'];
                $semester = $_GET['sem'];
                $course = $_GET['course'];
            
                // Loop through the form values and update the records
                for ($i = 0; $i < count($subjects); $i++) {
                    $subject = $_POST['subject'][$i];
                    $maximum = $_POST['maximum'][$i];
                    $minimum = $_POST['minimum'][$i];
                    $external = $_POST['external'][$i];
            
                    // Prepare the update statement
                     $query = "UPDATE tb_results SET subject = '$subject', maximum = '$maximum', minimum = '$minimum', external = '$external' WHERE semester = '$semester' AND sid = '$uid' and course_name='$course' and subject='$subject'";
                   $stmt = mysqli_query($conn,$query);
            
            
                    // Execute the statement
                    if ($stmt) {
                        echo "<div class='alert alert-success'>Record Updated Succesfully for ".$subject."</div>";
                        // echo "<script>window.location.href='results.php'</script>";
                    } else {
                        echo "Error";
                    }
                }
            
            }?>

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
            <p>Copyright © Developed by <a href="#" target="_blank"> </a> <?php echo date('Y') ?></p>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    

</html>