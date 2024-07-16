
<?php
session_start();
include "../config.php";

if (empty($_SESSION['user_email'])) {
    echo "<script>window.location.href='index.php';</script>";
}

// Check if the user is not logged in, redirect to login page\

// if (!isset($_SESSION['user_id'])) {
//     header("Location: login.php");
// }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input
    $course = mysqli_real_escape_string($conn, $_POST['course']);
    $exam = mysqli_real_escape_string($conn, $_POST['exam']);
    $exam_date = mysqli_real_escape_string($conn, $_POST['date']);
    $recept_no = mysqli_real_escape_string($conn, $_POST['recept_no']);
    $exam_center_address = mysqli_real_escape_string($conn, $_POST['exam_center_address']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $reporting_time = mysqli_real_escape_string($conn, $_POST['reporting_time']);
    $exam_time = mysqli_real_escape_string($conn, $_POST['exam_time']);
    $Paper_collecting_time = mysqli_real_escape_string($conn, $_POST['Paper_collecting_time']);

    // Process uploaded profile photo if provided
    $profile_photo = '';
    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['size'] > 0) {
        $target_dir = "uploads/";
        $photo_name = basename($_FILES["profile_photo"]["name"]);
        $target_file = $target_dir . $photo_name;
        move_uploaded_file($_FILES["profile_photo"]["tmp_name"], $target_file);
        $profile_photo = $photo_name;
    }

    // Get branch session ID from session if set
    $branch_session_id = isset($_SESSION['branch_session_id']) ? $_SESSION['branch_session_id'] : '';

    // Validate exam_id, assuming it's passed as POST data
    $exam_id = isset($_POST['exam_id']) ? intval($_POST['exam_id']) : '';

    // Generate random sequence number
    $sequence_no = rand(1000, 9999);

    // Create the SQL query
    $sql = "INSERT INTO admit_cards 
            (course, exam, exam_date, sequence_no, recept_no, exam_center_address, name, dob, contact, address, profile_photo, branch_session_id, exam_id,reporting_time,exam_time,Paper_collecting_time) 
            VALUES 
            ('$course', '$exam', '$exam_date', $sequence_no, '$recept_no', '$exam_center_address', '$name', '$dob', '$contact', '$address', '$profile_photo', '$branch_session_id', $exam_id , '$reporting_time','$exam_time','$Paper_collecting_time')";

    // Execute the query
    if (mysqli_query($conn, $sql)) {
        echo "Admit card created successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}




$exams = $conn->query("SELECT * FROM exams");
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

    <title>Student Management || Gravity</title>
    <link rel="shortcut icon" type="image/png" href="assets/images/favicon.png">

    <link href="vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
    <link href="vendor/swiper/css/swiper-bundle.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet">
    <link href="vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="vendor/jvmap/jquery-jvectormap.css" rel="stylesheet">
    <link href="vendor/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
    <link href="vendor/tagify/dist/tagify.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">
</head>

<body data-typography="poppins" data-theme-version="light" data-layout="vertical" data-nav-headerbg="black" data-headerbg="color_1">
    <div id="preloader">
        <div class="lds-ripple">
            <div></div>
            <div></div>
        </div>
    </div>

    <div id="main-wrapper">
        <?php include("includes/header.php"); ?>

        <div class="content-body">
            <div class="page-titles">
                <ol class="breadcrumb">
                    <li>
                        <h5 class="bc-title">Dashboard</h5>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="javascript:void(0)">
                            <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2.125 6.375L8.5 1.41667L14.875 6.375V14.1667C14.875 14.5424 14.7257 14.9027 14.4601 15.1684C14.1944 15.4341 13.8341 15.5833 13.4583 15.5833H3.54167C3.16594 15.5833 2.80561 15.4341 2.53993 15.1684C2.27426 14.9027 2.125 14.5424 2.125 14.1667V6.375Z" stroke="#2C2C2C" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M6.375 15.5833V8.5H10.625V15.5833" stroke="#2C2C2C" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            Manage student
                        </a>
                    </li>
                </ol>
            </div>
            <div class="container mt-4">
                <h1 class="font-lg">Add Admit card</h1>
        <form action="#" method="POST" enctype="multipart/form-data">
            <div class="row mb-3">
                <div class="col-md-12">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>COURSE</th>
                                <td colspan="3">
                                    <select name="course" class="form-control" required>
                                        <option value="">Select Course</option>
                                        <option>6M</option>
                                        <option>12M</option>
                                        <option>18M</option>
                                    </select>
                                </td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th>EXAM(S)</th>
                                <td colspan="3">
                                    <select name="exam" class="form-control" required>
                                        <option value="">Select Exams</option>
                                        <option>DCA</option>
                                        <option>ADCA</option>
                                        <option>MDCA</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>DATE</th>
                                <td colspan="3">
                                    <!-- Populate dynamically with JavaScript -->
                                    <input  type="date" name="date" class="form-control" required></input>
                                    
                                </td>
                            </tr>
                            <tr>
                                <th>Exam Name:</th>
                                <td colspan="3">
                                    <!-- Populate dynamically with JavaScript -->
                                    <select  type="text" name="exam_id" class="form-control" required>

                                    <option value="">Select Exam</option>
                                    <?php foreach ($exams as $exam): ?>
                                        <option value="<?=$exam['id']?>"><?=$exam['title']?></option>
                                    <?php endforeach;?>
                                    </select>
                                    
                                    
                                </td>
                            </tr>
                            <tr>
                                <th>SEQUENCE NO.</th>
                                <td colspan="3">
                                    <!-- Displayed after form submission -->
                                </td>
                            </tr>
                            <tr>
                                <th>ID</th>
                                <td colspan="3">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <label for="" class="text-uppercase">Reporting time</label><br>
                                            <input type="text" class="form-control" name="reporting_time">
                                        </div>
                                        <div class="col-lg-4">
                                            <label for="" class="text-uppercase">Exam Time</label><br>
                                            <input type="text"  name="exam_time" class="form-control" value="<?php echo $exam['start_time'] ;  ?>" readonly>
                                        </div>
                                        <div class="col-lg-4">
                                            <label for="" class="text-uppercase">Paper Collecting Time</label><br>
                                            <input type="text" name="Paper_collecting_time" class="form-control" value="<?php echo $exam['end_time'];  ?>" readonly>
                                        </div>
                                    </div>
                                    
                                </td>
                            </tr>
                            <tr>
                                <th>RECPT NO.</th>
                                <td colspan="3">
                                    <input type="text" name="recept_no" class="form-control" required>
                                </td>
                            </tr>
                            <tr>
                                <th>EXAM CENTER ADDRESS:</th>
                                <td colspan="3">
                                    <textarea name="exam_center_address" class="form-control" required></textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-12 m-4 section-title bg-warning text-weight-bold text-white text-capitalize text-center p-2">CANDIDATE DETAILS</div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>NAME:</th>
                                <td>
                                    <input type="text" name="name" class="form-control" required>
                                </td>
                            </tr>
                            <tr>
                                <th>D.O.B</th>
                                <td>
                                    <input type="date" name="dob" class="form-control" required>
                                </td>
                            </tr>
                            <tr>
                                <th>CONTACT</th>
                                <td>
                                    <input type="text" name="contact" class="form-control" required>
                                </td>
                            </tr>
                            <tr>
                                <th>ADDRESS</th>
                                <td>
                                    <textarea name="address" class="form-control" required></textarea>
                                </td>
                            </tr>
                            <tr>
                                <th>UPLOAD PHOTO:</th>
                                <td colspan="3">
                                    <input type="file" name="profile_photo" class="form-control-file" accept="image/*">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6 text-center">
                    <div class="passport-photo mb-3">PASSPORT-SIZE PHOTO</div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 text-center">
                    <strong>CANDIDATE SIGNATURE</strong>
                </div>
                <div class="col-md-6 text-center">
                    <strong>CANDIDATE SIGNATURE</strong>
                </div>
            </div>
            
            <div class="row mt-3">
                <div class="col-md-6 text-center">
                    <strong>CONTROLLER OF EXAMINATION</strong>
                </div>
                <div class="col-md-6 text-center">
                    <strong>INVIGILATOR SIGNATURE</strong>
                </div>
            </div>
            
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="term-conditions">
                        <p><strong>TERMS & CONDITIONS</strong></p>
                        <p>1. It Is Mandatory For The Student To Come On Time <span>विद्यार्थी को समय पर आना आवश्यक है।</span></p>
                        <p>2. Student Will Bring Exam Board In The Exam <span>विद्यार्थी परीक्षा में EXAM BOARD लेकर आए।</span></p>
                        <p>3. Must Bring Your Admit Card In The Examination <span>विद्यार्थी परीक्षा में अपना Admit Card लेकर आए।</span></p>
                        <p>4. Exam Is Compulsory In Every Session Your Marks Are Determined By The Exam <span>प्रत्येक सत्र में परीक्षा अनिवार्य है। आपके अंक परीक्षा द्वारा निर्धारित किए जाते हैं।</span></p>
                        <p>5. It Is Necessary To Submit The Examination Fee For The Examination <span>परीक्षा के लिए परीक्षा शुल्क जमा करना आवश्यक है।</span></p>
                    </div>
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-md-12 text-center">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>


        </div>
    </div>

    <div class="footer">
        <div class="copyright">
            <p>Copyright © Developed by <a href="#" target="_blank"></a> <?php echo date('Y') ?></p>
        </div>
    </div>

    <script src="vendor/global/global.min.js"></script>
    <script src="vendor/chart.js/Chart.bundle.min.js"></script>
    <script src="vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
    <script src="vendor/draggable/draggable.js"></script>
    <script src="vendor/tagify/dist/tagify.js"></script>
    <script src="vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/js/dataTables.buttons.min.js"></script>
    <script src="vendor/datatables/js/buttons.html5.min.js"></script>
    <script src="vendor/datatables/js/jszip.min.js"></script>
    <script src="js/plugins-init/datatables.init.js"></script>
    <script src="vendor/bootstrap-datetimepicker/js/moment.js"></script>
    <script src="vendor/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
    <script src="vendor/jqvmap/js/jquery.vmap.min.js"></script>
    <script src="vendor/jqvmap/js/jquery.vmap.world.js"></script>
    <script src="vendor/jqvmap/js/jquery.vmap.usa.js"></script>
    <script src="js/custom.js"></script>
    <script src="js/deznav-init.js"></script>
</body>

</html>
