<?php
session_start();
include("../config.php");
if (empty($_SESSION['user_email'])) {
    echo "<script>window.location.href='index.php';</script>";
}

if (empty($_GET['roll_no']) && $_GET['course_id']) {
    echo '<script>window.location.href="short-result.php"</script>';
} else {
    $uid = $_GET['roll_no'];
    $course = $_GET['course_id'];
    $sql = "select *from tb_students where sid='$uid'";
    $result = mysqli_query($conn, $sql);
    if (!$result > 0) {
        echo '<script>alert("No Student found")</script>';
        echo '<script>window.location.href="short-result.php"</script>';
    } else {
        $sid = $_GET['roll_no'];
        $row = mysqli_fetch_array($result);
    }
    
}

if (isset($_POST['submit'])) {
    // Get the values from the GET method

    $sid = $_GET['roll_no'];
    $course_name = $_GET['course_id'];
    $c_name = $_GET['course_name'];

    $lst_sql ="insert into tb_last_single_result(sid,course_name,course_id) Values('$sid','$c_name','$course_name')";
    $exec_lst_sql = mysqli_query($conn,$lst_sql);

    // Get the form values
    $subjects = $_POST['subject'];
    $maxima = $_POST['maximum'];
    $minima = $_POST['minimum'];
    $externals = $_POST['external'];

    // Prepare the insert statement
    $stmt = $conn->prepare("INSERT INTO tb_single_result (sid, course_name, subject, maximum, minimum, external) VALUES (?, ?, ?, ?, ?, ?)");

    // Loop through the form values and add them as parameters for batch insert
    for ($i = 0; $i < count($subjects); $i++) {
        $subject = $subjects[$i];
        $maximum = $maxima[$i];
        $minimum = $minima[$i];
        $external = $externals[$i];
        $stmt->bind_param("isssss", $sid, $course_name,$subject, $maximum, $minimum, $external);
        $stmt->execute();
    }

    // Check if any rows were inserted
    if ($stmt->affected_rows > 0) {
        echo "<div class='alert alert-success'>Records inserted successfully.</div>";
        echo '<script>window.location.href="short-result.php"</script>';
    } else {
        echo "<div class='alert alert-danger'>Error: No records were inserted.</div>";
    }

    // Close the statement
    $stmt->close();
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
    <title>Add Result</title>
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
                <div class="col-xl-12 col-lg-12">
                    <div class="card profile-card card-bx m-b30">
                        <div class="card-header">
                            <h6 class="title">Statement of Marks</h6>
                        </div>
                        <form class="profile-form" method="post" action="#">
                            <div class="card-body">
                                <div class="row">
                                    <img src="images/re_hd.jpg" class="img-fluid" alt="re_fd">
                                    <div class="col-md-6 justify-content-start">
                                        <div class="d-flex flex-column">
                                            <p>Student Name:<?php echo $row['student_name'] ?></p>
                                            <p>Course Name:<?php echo $row['course'];  ?></p>
                                            <p>Dob :<?php echo $row['dob']; ?></p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex flex-column">
                                            <p>Student Name:<?php echo $row['father_name'] ?></p>
                                            <p>Course Name:<?php echo $row['mother_name'];  ?></p>
                                            <p>Dob :<?php echo $row['dob']; ?></p>
                                        </div>

                                    </div>
                                </div>
                                <div class="row" id="form-fields">
                                    <div class="d-flex justify-content-end">
                                    <button class="add-button btn btn-dark" type="button" id="add-button">+</button>

                                    </div>
                                


                                    <div class="col-sm-6 col-md-6 col-lg-3 m-b30">
                                        <label class="form-label">Subject</label>
                                        <input type="text" class="form-control" name="subject[]" placeholder="Enter the Course Name">
                                    </div>
                                    <div class="col-sm-6 col-md-6 col-lg-3 m-b30">
                                        <label class="form-label">Maximum</label>
                                        <input type="number" class="form-control" name="maximum[]">
                                    </div>
                                    <div class="col-sm-6 col-md-6 col-lg-3 m-b30">
                                        <label class="form-label">Minimum</label>
                                        <input type="number" class="form-control" name="minimum[]" placeholder="Enter the Course Name">
                                    </div>
                                    <div class="col-sm-6 col-md-6 col-lg-3 m-b30">
                                        <label class="form-label">External</label>
                                        <input type="number" class="form-control" name="external[]">
                                        
                                    </div>
                                </div>
                            </div>
                    
                
                    <div class="card-footer d-flex justify-content-end">
                        
                        <button class="btn btn-primary" type="submit" name="submit">Add Result</button>

                    </div>
                    </form>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
            var maxFields = 10; // Maximum number of fields allowed
            var addButton = document.getElementById('add-button');
            var formFields = document.getElementById('form-fields');
            var fieldIndex = 1;

            // Function to create and append the new form fields
            function createNewFields() {
                var newFields = document.createElement('div');
                newFields.className = 'row dynamic-fields';
                newFields.innerHTML = '<div class="col-sm-6 col-md-6 col-lg-3 m-b30">' +
                                        '<label class="form-label">Subject</label>' +
                                        '<input type="text" class="form-control" name="subject[]' + fieldIndex + '" placeholder="Enter the Course Name">' +
                                    '</div>' +
                                    '<div class="col-sm-6 col-md-6 col-lg-3 m-b30">' +
                                        '<label class="form-label">Maximum</label>' +
                                        '<input type="number" class="form-control" name="maximum[]' + fieldIndex + '">' +
                                    '</div>' +
                                    '<div class="col-sm-6 col-md-6 col-lg-3 m-b30">' +
                                        '<label class="form-label">Minimum</label>' +
                                        '<input type="number" class="form-control" name="minimum[]' + fieldIndex + '" placeholder="Enter the Course Name">' +
                                    '</div>' +
                                    '<div class="col-sm-6 col-md-6 col-lg-3 m-b30">' +
                                        '<label class="form-label">External</label>' +
                                        '<input type="number" class="form-control" name="external[]' + fieldIndex + '">' +
                                    '</div>' +
                                    '<div class="col-sm-12 m-b30 d-flex justify-content-end">' +
                                        '<button class="remove-button btn btn-secondary" type="button">Remove</button>' +
                                    '</div>';

                formFields.appendChild(newFields);

                // Add event listener for the remove button
                var removeButton = newFields.querySelector('.remove-button');
                removeButton.addEventListener('click', function() {
                    formFields.removeChild(newFields);
                });

                fieldIndex++;
            }

            // Event handler for the plus button click
            addButton.addEventListener('click', function(e) {
                e.preventDefault();

                // Check if the maximum number of fields has been reached
                if (fieldIndex < maxFields) {
                    createNewFields();
                }
            });
        </script>
    </form



</body>

</html>


?>