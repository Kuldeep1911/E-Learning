<?php
session_start();
include("../config.php");
if (empty($_SESSION['user_email'])) {
    echo "<script>window.location.href='index.php';</script>";
}
if (isset($_GET['roll_no'])) {
    $uid = $_GET['roll_no'];
    $check_user_exists = "select *from tb_students where sid='$uid'";
    $check_result = mysqli_query($conn, $check_user_exists);
    if (!mysqli_num_rows($check_result) > 0) {
        echo '<script>window.location.href="students.php"</script>';
    } else {
        $row = mysqli_fetch_array($check_result);
    }
}
if (!empty($_GET['roll_no'] && !empty($_GET['sem'])) && !empty($_GET['course'])) {
    $uid = $_GET['roll_no'];
    $semester = $_GET['sem'];
    $course = $_GET['course'];
} else {
    echo '<script>window.location.href="results.php"</script>';
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
    <title>View Result</title>
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
                            Result</a>
                    </li>
                </ol>
            </div>
            <div class="row d-flex justify-content-center my-5">
                <div class="col-xl-12 col-lg-12">
                    <div class="card profile-card card-bx m-b30">
                        <div class="card-header">
                            <h6 class="title">Statement of Marks</h6>
                            <div class="d-flex justify-content-end">
                                <button onclick="convertToImage()" class="btn btn-primary">Download</button>
                            </div>
                        </div>
                        <div class="card-body" style="border:2px solid black; background-color: white;max-width:600px;height:auto;" id="card-body">
                            <img src="images/re_hd.jpg" class="img-fluid" id="card-image" alt="gravity">
                            <h6 class="text-center fw-bold">Statement of <?php if ($semester == 1) {
                                                                                echo $semester . "st";
                                                                            } elseif ($semester == 2) {
                                                                                echo $semester . "nd";
                                                                            } elseif ($semester == 3) {
                                                                                echo $semester . "rd";
                                                                            } elseif ($semester >= 4) {
                                                                                echo $semester . "th";
                                                                            }  ?> Semester Marks </h6>
                                   
                                   <div class="row mt-3">
                               
                               <div class="col-5 justify-content-start">
                                   <div class="d-flex flex-column">
                                       <p class="py-0 my-2">Student Name:<?php echo $row['student_name'] ?></p>
                                       <p class="py-0 my-2">Course Name:<?php echo $row['course'];  ?></p>
                                       <p class="py-0 my-2">Admission Date :<?php echo $row['admission_date']; ?></p>
                                   </div>
                               </div>
                               <div class="col-5">
                                   <div class="d-flex flex-column">
                                       <p class="py-0 my-2">Father Name:<?php echo $row['father_name'] ?></p>
                                       <p class="py-0 my-2">Mother Name:<?php echo $row['mother_name'];  ?></p>
                                       <p class="py-0 my-2">Dob :<?php echo $row['dob']; ?></p>
                                   </div>
                               </div>
                                   <div class="col-2">
                                       <img src="../img/students/<?php echo $row['profile_img']; ?>" class="img-fluid" height="100" width="100" alt="">
                                   </div>

                               </div>

                            <div class="row" id="form-fields">
                                <table class="table">
                                    <thead>
                                        <tr style="text-align:center;">
                                            <th scope="col">S.No.</th>
                                            <th scope="col" colspan="3">Subject</th>
                                            <th scope="col">Maximum</th>
                                            <th scope="col">Minimum</th>
                                            <th scope="col">External</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $count = 1;
                                        $total = 0;
                                        $pass_status = 0;
                                        $maximum = 0;
                                        $sub_res = "select *from tb_results where semester='$semester' and sid='$uid'";;
                                        $exec_sub = mysqli_query($conn, $sub_res);
                                        if (mysqli_num_rows($exec_sub)) {
                                            while ($res_row = mysqli_fetch_array($exec_sub)) {
                                        ?>

                                                <tr style="text-align:center;">
                                                    <th scope="row"><?php echo $count; ?></th>
                                                    <td colspan="3"><?php echo $res_row['subject']; ?></td>
                                                    <td><?php echo $res_row['maximum']; ?></td>
                                                    <td><?php echo $res_row['minimum']; ?></td>
                                                    <td><?php echo $res_row['external']; 
                                                    $total += $res_row['external'];
                                                    if ($res_row['minimum'] > $res_row['external']) {
                                                        $pass_status = '1';
                                                        echo '<script>console.log("fail");</script>';
                                                    }
                                                    $maximum += $res_row['maximum'];?></td>
                                                </tr>

                                        <?php $count++;
                                            }
                                        } ?>

                                    </tbody>
                                    <tfoot>


                                        <tr style="text-align:center;">
                                            <th colspan="3">TOTAL MARKS OBTAINED</th>
                                            <th rowspan="2">TOTAL</th>
                                            <th rowspan="2">RESULT</th>
                                            <th rowspan="2">DIVISION</th>
                                            <th rowspan="2">PERCENTAGE</th>
                                        </tr>
                                        <tr style="text-align:center;">
                                            <th>1st Semester</th>
                                            <th>2nd Semester</th>
                                            <th>3rd Semester</th>
                                        </tr>
                                        <tr style="text-align:center;">
                                            <th><span><?php 
                                           $first_semster = "select SUM(external) as total  from tb_results where semester='1' and sid='$uid'  and course_name='$course' ";
                                            $semester_result = mysqli_query($conn,$first_semster);
                                            if(mysqli_num_rows($semester_result)>0){
                                                $row_result = mysqli_fetch_array($semester_result);
                                                $first_semster_total = $row_result['total'];
                                                echo $row_result['total'];
                                            }else{
                                                echo "0";
                                            }
                                            ?></span>
                                            
                                                <input type="hidden" name="sem1" id="sem1" value="292">
                                            </th>
                                            <th><span><?php 
                                         $first_semster = "select SUM(external) as total  from tb_results where semester='2' and sid='$uid'  and course_name='$course' ";
                                            $semester_result = mysqli_query($conn,$first_semster);
                                            if(mysqli_num_rows($semester_result)>0){
                                                $row_result = mysqli_fetch_array($semester_result);
                                                $second_result = $row_result['total'];;
                                                echo $row_result['total'];
                                            }else{
                                                echo "0";
                                            }
                                            ?></span>
                                                <input type="hidden" name="sem2" id="sem2" value="302">
                                            </th>

                                            <th><span><?php 
                                         $first_semster = "select SUM(external) as total  from tb_results where semester='3' and sid='$uid'  and course_name='$course' ";
                                            $semester_result = mysqli_query($conn,$first_semster);
                                            if(mysqli_num_rows($semester_result)>0){
                                                $row_result = mysqli_fetch_array($semester_result);
                                                $third_result = $row_result['total'];
                                                echo $row_result['total'];
                                            }else{
                                                echo "0";
                                            }
                                            ?></span>
                                                <input type="hidden" name="sem3" id="sem3" value="0">
                                            </th>

                                            <th><span> <?php echo $first_semster_total+$second_result+$third_result; ?> </span></th>
                                            <th> <?php if ($pass_status == '0') {
                                                                echo 'Pass';
                                                            } else {
                                                                echo 'Fail';
                                                            } ?>  </th>
                                            <th><?php
                                            $total_marks =$first_semster_total+$second_result+$third_result;
                                            $sql_get_maximum = "SELECT SUM(maximum) AS total_max
                                            FROM tb_results
                                            WHERE semester IN ('1', '2', '3')  and sid='$uid'";
                                            $get_result = mysqli_query($conn,$sql_get_maximum);
                                            $row_maximum = mysqli_fetch_array($get_result);
                                            $percentage =$total_marks/$row_maximum['total_max']*100;
                                            $rounded_percentage = round($percentage);
                                            if ($percentage >= 90) {
                                                echo '1st';
                                            } elseif ($percentage >= 80) {
                                                echo '2nd';
                                            } elseif ($percentage >= 70) {
                                                echo '3rd';
                                            } else {
                                                echo 'Participation';
                                            }
                                            
                                            ?></th>
                                            <th><span>
                                            <?php
                                            echo $rounded_percentage;
                                            ?></span></th>

                                        </tr>

                                    </tfoot>
                                </table>
                                <img src="images/re_fd.jpg" alt="result" class="img-flu">

                            </div>

                        </div>
                    </div>
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
    <script>
        function convertToImage() {
            var element = document.getElementById('card-body');

            html2canvas(element, {
                scale: window.devicePixelRatio
            }).then(function(canvas) {

                var dataURL = canvas.toDataURL('image/png');
                var filename = 'converted_image.png';

                downloadImage(dataURL, filename);
            });
        }

        function downloadImage(dataURL, filename) {
            var link = document.createElement('a');
            link.href = dataURL;
            link.download = filename;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    </script>









</body>

</html>

