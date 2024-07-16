<?php
session_start();
include "../config.php";

$id = $_GET['id'] ?? null;

if (!$id) {
    die("Invalid request.");
}

$sql = "SELECT * FROM admit_cards WHERE id = $id";
$result = mysqli_query($conn, $sql);
$admitCard = mysqli_fetch_assoc($result);

if (!$admitCard) {
    die("Admit card not found.");
}

$message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Escape user inputs for security
    $id = intval($_GET['id']); // Make sure to retrieve and validate the ID
    $course = mysqli_real_escape_string($conn, $_POST['course']);
    $exam = mysqli_real_escape_string($conn, $_POST['exam']);
    $date = mysqli_real_escape_string($conn, $_POST['exam_date']);
    $recept_no = mysqli_real_escape_string($conn, $_POST['recept_no']);
    $exam_center_address = mysqli_real_escape_string($conn, $_POST['exam_center_address']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $reporting_time = mysqli_real_escape_string($conn, $_POST['reporting_time']);
    $exam_time = mysqli_real_escape_string($conn, $_POST['exam_time']);
    $paper_collecting_time = mysqli_real_escape_string($conn, $_POST['paper_collecting_time']); // Fixed variable name to follow consistent naming

    // Update existing admit card
    $sql = "UPDATE admit_cards SET 
            course = '$course', 
            exam = '$exam', 
            exam_date = '$date', 
            recept_no = '$recept_no', 
            exam_center_address = '$exam_center_address', 
            name = '$name', 
            dob = '$dob', 
            contact = '$contact', 
            address = '$address', 
            reporting_time = '$reporting_time', 
            exam_time = '$exam_time', 
            paper_collecting_time = '$paper_collecting_time' 
            WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . mysqli_error($conn);
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
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 20px;
        }
        .container {
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            border-radius: 10px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0069d9;
            border-color: #0062cc;
        }
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }
    </style>

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

            <div class="container">
        <h3 class="mb-4">Edit Admit Card</h3>
        <?php if ($message): ?>
            <div class="alert alert-info"><?= $message ?></div>
        <?php endif; ?>
        <form action="edit_admit_card.php?id=<?= $id ?>" method="POST">
            <div class="row">
                <div class="form-group col-lg-6">
                    <label for="course">Course</label>
                    <input type="text" class="form-control" id="course" name="course" value="<?= $admitCard['course'] ?>" required>
                </div>
                <div class="form-group col-lg-6">
                    <label for="exam">Exam</label>
                    <input type="text" class="form-control" id="exam" name="exam" value="<?= $admitCard['exam'] ?>" required>
                </div>
            </div>
             
            <div class="row">
                <div class="form-group col-lg-6">
                    <label for="date">Date</label>
                    <input type="text" class="form-control" id="date" name="exam_date" value="<?= $admitCard['exam_date'] ?>" required>
                </div>
                <div class="form-group col-lg-6">
                    <label for="recept_no">Receipt No.</label>
                    <input type="text" class="form-control" id="recept_no" name="recept_no" value="<?= $admitCard['recept_no'] ?>" required>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-lg-6">
                    <label for="date">Reporting Time</label>
                    <input type="text" class="form-control"  name="reporting_time" value="<?= $admitCard['reporting_time']; ?>" required>
                </div>
                <div class="form-group col-lg-3">
                    <label for="recept_no">Exam Date</label>
                    <input type="text" class="form-control" id="recept_no" name="exam_time" value="<?= $admitCard['exam_time']; ?>" required>
                </div>
                <div class="form-group col-lg-3">
                    <label for="recept_no">Paper Submitting Time</label>
                    <input type="text" class="form-control" id="recept_no" name="paper_collecting_time" value="<?= $admitCard['paper_collecting_time'] ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label for="exam_center_address">Exam Center Address</label>
                <textarea class="form-control" id="exam_center_address" name="exam_center_address" required><?= $admitCard['exam_center_address'] ?></textarea>
            </div>
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?= $admitCard['name'] ?>" required>
            </div>
            <div class="form-group">
                <label for="dob">Date of Birth</label>
                <input type="date" class="form-control" id="dob" name="dob" value="<?= $admitCard['dob'] ?>" required>
            </div>
            <div class="form-group">
                <label for="contact">Contact</label>
                <input type="text" class="form-control" id="contact" name="contact" value="<?= $admitCard['contact'] ?>" required>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <textarea class="form-control" id="address" name="address" required><?= $admitCard['address'] ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="manage_admit_cards.php" class="btn btn-secondary">Cancel</a>
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
