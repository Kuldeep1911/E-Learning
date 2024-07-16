<?php
session_start();
include("../config.php");
if (empty($_SESSION['user_email'])) {
    echo "<script>window.location.href='index.php';</script>";
}


if (isset($_GET['uid'])) {
    $uid = $_GET['uid'];
    $check_user_exists = "select *from tb_students where sid='$uid'";
    $check_result = mysqli_query($conn, $check_user_exists);
    if (!mysqli_num_rows($check_result) > 0) {
        echo '<script>window.location.href="students.php"</script>';
    }
}else{
    echo '<script>window.location.href="students.php"</script>';
}


if (isset($_POST['submit'])) {
    
    // Get the form values
    $fullName = $_POST['name'];
    $admissionDate = $_POST['admission_date'];
    $phoneNo = $_POST['phone'];
    $alternatePhoneNo = $_POST['phone1'];
    $email = $_POST['email'];
    $dob = $_POST['dob'];
    $fatherName = $_POST['father'];
    $motherName = $_POST['mother'];
    $state = $_POST['state'];
    $city = $_POST['city'];
    $address = $_POST['address'];
    $course = $_POST['course'];
    $details = $_POST['details'];
    $passingYear = $_POST['passing_year'];
    $board = $_POST['board'];
    $marksObtained = $_POST['marks'];
    $subject = $_POST['subject'];
    $regFees = $_POST['reg_fees'];
    $monthlyFees = $_POST['monthly_fees'];
    $discountedFees = $_POST['discounted_fees'];
    $regTotalPaid = $_POST['reg_total_paid'];
    
    // Update query for tb_students table
    $updateStudentQuery = "UPDATE tb_students SET
                            student_name = '$fullName',
                            admission_date = '$admissionDate',
                            phone_number1 = '$phoneNo',
                            phone_number2 = '$alternatePhoneNo',
                            email = '$email',
                            dob = '$dob',
                            father_name = '$fatherName',
                            mother_name = '$motherName',
                            state = '$state',
                            city = '$city',
                            address = '$address',
                            course = '$course'
                          WHERE sid='$uid'";
    
    // Execute the update query for tb_students table
    mysqli_query($conn, $updateStudentQuery);
    
    // Update query for tb_qualification table
    $updateQualificationQuery = "UPDATE tb_qualification SET
                                  detail = '$details',
                                  passing_year = '$passingYear',
                                  board = '$board',
                                  marks_obtained = '$marksObtained',
                                  subject = '$subject'
                                WHERE uid='$uid'";
    
    // Execute the update query for tb_qualification table
    mysqli_query($conn, $updateQualificationQuery);
    
    // Update query for tb_payments table
    $updatePaymentsQuery = "UPDATE tb_payments SET
                             registration = '$regFees',
                             monthly = '$monthlyFees',
                             discount = '$discountedFees',
                             payment_on_registration = '$regTotalPaid'
                           WHERE sid='$uid'";
    
    // Execute the update query for tb_payments table
    if(mysqli_query($conn, $updatePaymentsQuery)){
        echo '<script>alert("User Profile updated successfully")</script>';
        echo '<script>window.location.href="students.php"</script>';
    }

    
    // Redirect or display success message
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
    <title>Add Student</title>
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
                            Update Students</a>
                    </li>
                </ol>
            </div>
            <div class="row d-flex justify-content-center my-5">
                <div class="col-xl-9 col-lg-8">
                    <div class="card profile-card card-bx m-b30">
                        <div class="card-header">
                            <h6 class="title">Update Students</h6>
                            <div class="d-flex justify-content-end">
                                <a href="update-img.php?id=<?php echo $uid ?>"><button class="btn btn-primary">Update Image</button></a>
                            </div>
                        </div>
                        <?php if (!empty($_GET['uid'])) {

                            $get_student = "select *from tb_students where sid='$uid'";
                            $get_result = mysqli_query($conn, $get_student);
                            if (mysqli_num_rows($get_result) > 0) {
                            $row = mysqli_fetch_array($get_result);
                            $get_qualification = "select *from tb_qualification JOIN tb_payments on tb_qualification.uid = tb_payments.sid where uid='$uid' ";
                            $get_qualification_result = mysqli_query($conn,$get_qualification);
                            $row_q = mysqli_fetch_array($get_qualification_result);
                        ?>
                        <div class="d-flex justify-content-center">
                                <img src="../img/students/<?php echo $row['profile_img'];?>" alt="profile_img" height="200" width="200" class="img-">
                            </div>    
                            <form class="profile-form" method="post" action="#" enctype="multipart/form-data">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-6 m-b30">
                                                <label class="form-label">Student Full Name</label>
                                                <input type="text" class="form-control" name="name" value="<?php echo $row['student_name'] ?>" required>
                                            </div>
                                            <div class="col-sm-6 m-b30">
                                                <label class="form-label">Admission Date</label>
                                                <input type="date" class="form-control" name="admission_date" value="<?php echo $row['admission_date'] ?>" required>
                                            </div>
                                            <div class="col-sm-6 m-b30">
                                                <label class="form-label">Phone No.</label>
                                                <input type="text" class="form-control" name="phone" value="<?php echo $row['phone_number1'] ?>" required>
                                            </div>
                                            <div class="col-sm-6 m-b30">
                                                <label class="form-label">Alternate Phone no.</label>
                                                <input type="text" class="form-control" name="phone1" value="<?php echo $row['phone_number2'] ?>" required>
                                            </div>
                                            <div class="col-sm-6 m-b30">
                                                <label class="form-label">Email</label>
                                                <input type="text" class="form-control" name="email" value="<?php echo $row['email'] ?>" required>
                                            </div>
                                            <div class="col-sm-6 m-b30">
                                                <label class="form-label">D.O.B</label>
                                                <input type="date" class="form-control" name="dob" value="<?php echo $row['dob'] ?>" required>
                                            </div>
                                            <div class="col-sm-6 m-b30">
                                                <label class="form-label">Father Name</label>
                                                <input type="text" class="form-control" name="father" value="<?php echo $row['father_name']; ?>" required>
                                            </div>
                                            <div class="col-sm-6 m-b30">
                                                <label class="form-label">Mother's Name</label>
                                                <input type="text" class="form-control" name="mother" value="<?php echo $row['mother_name']; ?>" required>
                                            </div>

                                            <div class="col-sm-6 m-b30">
                                                <label class="form-label">State</label>
                                                <input type="text" class="form-control" name="state" value="<?php echo $row['mother_name']; ?>" required>
                                            </div>
                                            <div class="col-sm-6 m-b30">
                                                <label class="form-label">City</label>
                                                <input type="text" class="form-control" name="city" value="<?php echo $row['mother_name']; ?>" required>
                                            </div>
                                            <div class="col-sm-6 m-b30">
                                                <label class="form-label">Address</label>
                                                <input type="text" class="form-control" name="address" value="<?php echo $row['address']; ?>" required>
                                            </div>
                                            <div class="col-sm-6 m-b30">
                                                <label class="form-label">Course</label>
                                                <select class="form-control" name="course" value="<?php echo $row['course']; ?>" required>
                                                    <?php
                                                    $get_course = "select *from tb_courses";
                                                    $course_result = mysqli_query($conn, $get_course);
                                                    if (mysqli_num_rows($course_result) > 0) {
                                                        while ($course_row = mysqli_fetch_array($course_result)) {
                                                    ?>
                                                            <option value="<?php echo $course_row['course_name']; ?>"><?php echo $course_row['course_name'] ?></option>
                                                    <?php  }
                                                    } ?>
                                                </select>
                                            </div>



                                                <?php if(mysqli_num_rows($get_qualification_result)>0){?>

                                            <div class="col-sm-6 m-b30">
                                                <label class="form-label">Passing Details</label>
                                                <input type="text" class="form-control" name="details" placeholder="Example 10th,12th,graduation" value="<?php echo $row_q['detail']; ?>" required>
                                            </div>
                                            <div class="col-sm-6 m-b30">
                                                <label class="form-label">Passing Year</label>
                                                <input type="text" class="form-control" name="passing_year" value="<?php echo $row_q['passing_year']; ?>" required>
                                            </div>
                                            <div class="col-sm-6 m-b30">
                                                <label class="form-label">Passing Board</label>
                                                <input type="text" class="form-control" name="board" value="<?php echo $row_q['board']; ?>" placeholder="ex -CBSE,ICSE,HBSE,e.t.c" required>
                                            </div>
                                            <div class="col-sm-6 m-b30">
                                                <label class="form-label">Marks Obtained</label>
                                                <input type="text" class="form-control" name="marks" value="<?php echo $row_q['marks_obtained']; ?>" placeholder="ex -85%" required>
                                            </div>
                                            <div class="col-sm-6 m-b30">
                                                <label class="form-label">Subject</label>
                                                <input type="text" class="form-control" name="subject" value="<?php echo $row_q['subject']; ?>" placeholder="ex.all subject" required>
                                            </div>
                                            <div class="col-sm-6 m-b30">
                                                <label class="form-label">Registration Fees </label>
                                                <input type="text" class="form-control" name="reg_fees" value="<?php echo $row_q['registration']; ?>" placeholder="registration fees" required>
                                            </div>
                                            <div class="col-sm-6 m-b30">
                                                <label class="form-label">Monthly Fees</label>
                                                <input type="text" class="form-control" name="monthly_fees" value="<?php echo $row_q['monthly']; ?>" placeholder="Monthly fees" required>
                                            </div>

                                            <div class="col-sm-6 m-b30">
                                                <label class="form-label">Discount Fees Amount</label>
                                                <input type="text" class="form-control" name="discounted_fees" value="<?php echo $row_q['discount']; ?>" placeholder="Discounted fees amount" required>
                                            </div>
                                            <div class="col-sm-6 m-b30">
                                                <label class="form-label">Registation Amount Paid</label>
                                                <input type="text" class="form-control" name="reg_total_paid" value="<?php echo $row_q['payment_on_registration']; ?>" placeholder="Amount paid at the time of registration" required>
                                            </div>

                                            <?php }?>


                                        </div>
                                    </div>
                                    <div class="card-footer d-flex justify-content-end">
                                        <button class="btn btn-primary" type="submit" name="submit">Update Details</button>

                                    </div>
                                </form>
                        <?php }
                        } ?>
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
        <script>
            function preview() {
                frame.src = URL.createObjectURL(event.target.files[0]);
            }
        </script>




</body>

</html>


?>