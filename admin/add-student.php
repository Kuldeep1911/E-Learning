<?php
session_start();
include("../config.php");

// Redirect to login if session is not active
if (empty($_SESSION['user_email'])) {
    echo "<script>window.location.href='index.php';</script>";
    exit; // Stop further execution
}

// Check if user ID is provided via GET
if (isset($_GET['uid'])) {
    $uid = $_GET['uid'];
    $check_user_exists = "SELECT * FROM tb_students WHERE sid='$uid'";
    $check_result = mysqli_query($conn, $check_user_exists);

    // Redirect if user does not exist
    if (!$check_result || mysqli_num_rows($check_result) == 0) {
        echo '<script>window.location.href="students.php"</script>';
        exit; // Stop further execution
    }
}

// Process form submission
if (isset($_POST['submit'])) {
    // Sanitize inputs to prevent SQL injection
    $fullName = mysqli_real_escape_string($conn, $_POST['name']);
    $admissionDate = mysqli_real_escape_string($conn, $_POST['admission_date']);
    $phoneNo = mysqli_real_escape_string($conn, $_POST['phone']);
    $alternatePhoneNo = mysqli_real_escape_string($conn, $_POST['phone1']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $fatherName = mysqli_real_escape_string($conn, $_POST['father']);
    $motherName = mysqli_real_escape_string($conn, $_POST['mother']);
    $state = mysqli_real_escape_string($conn, $_POST['state']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $course = mysqli_real_escape_string($conn, $_POST['course']);

    // Fetch course ID
    $sql_fetch_course_id = "SELECT * FROM tb_courses WHERE title='$course'";
    $get_result = mysqli_query($conn, $sql_fetch_course_id);
    
    // Check if query executed successfully
    if (!$get_result) {
        echo "Error: " . mysqli_error($conn);
        exit; // Stop further execution
    }

    // Fetch course row
    $course_row = mysqli_fetch_assoc($get_result);
    $course_id = $course_row['cid'];

    // Handle file upload
    $photo = $_FILES['photo']['name'];
    $targetDirectory = '../img/students/';
    $uniqueFileName = uniqid() . '_' . $photo;
    $targetFilePath = $targetDirectory . $uniqueFileName;

    // SQL query to insert data
    $sql = "INSERT INTO tb_students (admission_date, student_name, father_name, mother_name, email, phone_number1, phone_number2, dob, address, city, state, profile_img, course, cid) 
            VALUES ('$admissionDate', '$fullName', '$fatherName', '$motherName', '$email', '$phoneNo', '$alternatePhoneNo', '$dob', '$address', '$city', '$state', '$uniqueFileName', '$course', '$course_id')";

    // Execute query
    $results = mysqli_query($conn, $sql);

    // Check if query executed successfully
    if ($results) {
        $user_id = mysqli_insert_id($conn);

        // Move uploaded file
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetFilePath)) {
            // Redirect to appropriate page
            echo '<script>window.location.href = "add-student.php?uid=' . $user_id . '";</script>';
            exit; // Stop further execution
        } else {
            echo "Error in uploading file.";
        }
    } else {
        // Error in executing the query
        echo "Error: " . mysqli_error($conn);
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
                            Manage Users</a>
                    </li>
                </ol>
            </div>
            <div class="row d-flex justify-content-center my-5">
                <div class="col-xl-9 col-lg-8">
                    <div class="card profile-card card-bx m-b30">
                        <div class="card-header">
                            <h6 class="title">Account setup</h6>
                        </div>
                        <?php if (empty($_GET['uid'])) { ?>
                            <form class="profile-form" method="post" action="#" enctype="multipart/form-data">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-6 m-b30">
                                            <label class="form-label">Student Full Name</label>
                                            <input type="text" class="form-control" name="name" required>
                                        </div>
                                        <div class="col-sm-6 m-b30">
                                            <label class="form-label">Admission Date</label>
                                            <input type="date" class="form-control" name="admission_date" required>
                                        </div>
                                        <div class="col-sm-6 m-b30">
                                            <label class="form-label">Phone No.</label>
                                            <input type="text" class="form-control" name="phone" required>
                                        </div>
                                        <div class="col-sm-6 m-b30">
                                            <label class="form-label">Alternate Phone no.</label>
                                            <input type="text" class="form-control" name="phone1" required>
                                        </div>
                                        <div class="col-sm-6 m-b30">
                                            <label class="form-label">Email</label>
                                            <input type="text" class="form-control" name="email" required>
                                        </div>
                                        <div class="col-sm-6 m-b30">
                                            <label class="form-label">D.O.B</label>
                                            <input type="date" class="form-control" name="dob" required>
                                        </div>
                                        <div class="col-sm-6 m-b30">
                                            <label class="form-label">Father Name</label>
                                            <input type="text" class="form-control" name="father" required>
                                        </div>
                                        <div class="col-sm-6 m-b30">
                                            <label class="form-label">Mother's Name</label>
                                            <input type="text" class="form-control" name="mother" required>
                                        </div>

                                        <div class="col-sm-6 m-b30">
                                            <label class="form-label">State</label>
                                            <input type="text" class="form-control" name="state" required>
                                        </div>
                                        <div class="col-sm-6 m-b30">
                                            <label class="form-label">City</label>
                                            <input type="text" class="form-control" name="city" required>
                                        </div>
                                        <div class="col-sm-6 m-b30">
                                            <label class="form-label">Address</label>
                                            <input type="text" class="form-control" name="address" required>
                                        </div>
                                        <div class="col-sm-6 m-b30">
                                            <label class="form-label">Course</label>
                                            <select class="form-control" name="course" required>
                                                <?php
                                                $get_course = "select * from tb_courses";
                                                $course_result = mysqli_query($conn, $get_course);
                                                if (mysqli_num_rows($course_result) > 0) {
                                                    while ($course_row = mysqli_fetch_array($course_result)) {
                                                ?>
                                                        <option value="<?php echo $course_row['id']; ?>"><?php echo $course_row['title'] ?></option>
                                                <?php  }
                                                } ?>
                                            </select>
                                        </div>

                                        <div class="col-sm-12 m-b30">
                                            <label class="form-label">Student Passport Size photo</label>
                                            <input type="file" class="form-control" id="imgInp" name="photo" onchange="preview()" required>
                                        </div>
                                        <img id="frame" src="" />


                                    </div>
                                </div>
                                <div class="card-footer d-flex justify-content-end">
                                    <button class="btn btn-primary" type="submit" name="submit">Add Student</button>

                                </div>
                            </form>
                        <?php } ?>
                                </div>
                                <div class="card-footer d-flex justify-content-end">
                                    <button class="btn btn-primary" type="submit" name="update-details">Update Details</button>

                                </div>
                            </form>
                        <?php  ?>
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


    <script>
        var total_image = 1;

        function add_more_images() {
            total_image++;
            var html = '<div class="col-lg-6" style="margin-top:20px;" id="add_image_box_' + total_image + '"><label for="categories" class=" form-control-label">Image</label><input type="file" name="product_images[]" class="form-control" required><button type="button" class="btn btn-lg btn-danger btn-block" onclick=remove_image("' + total_image + '")><span id="payment-button-amount">Remove</span></button></div>';
            jQuery('#image_box').append(html);
        }

        function remove_image(id) {
            jQuery('#add_image_box_' + id).remove();
        }
    </script>

    <script>
        $(document).ready(function() {
            $('body').on('click', '.deleteBanner', function() {
                document.getElementById("feed_id").value = $(this).attr('data-id');
                console.log($(this).attr('data-id'));
            });
        });
    </script>


</body>

</html>


?>