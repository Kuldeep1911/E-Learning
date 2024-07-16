<?php
session_start();
include("../config.php");

if (empty($_SESSION['user_email'])) {
    echo "<script>window.location.href='index.php';</script>";
    exit;
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

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body p-0">
                            <div class="table-responsive active-projects style-1">
                                <div class="tbl-caption">
                                    <h4 class="heading mb-0">Students</h4>
                                    <div>
                                        <div class="d-flex justify-content-end">
                                            <a href="add-student.php" class="mx-5 my-3"><button class="btn btn-primary">Add Student</button></a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Search Form -->
                                <div class="tbl-caption">
                                    <form action="" method="GET" class="d-flex">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <input type="text" name="student_name" class="form-control me-2" placeholder="Student Name">
                                            </div>
                                            <div class="col-md-3">
                                                <input type="text" name="roll_number" class="form-control me-2" placeholder="Roll Number">
                                            </div>
                                            <div class="col-md-3">
                                                <input type="text" name="email" class="form-control me-2" placeholder="Email">
                                            </div>
                                            <div class="col-md-3">
                                                <input type="text" name="contact" class="form-control me-2" placeholder="Contact">
                                            </div>
                                            <div class="d-flex justify-content-end">
                                                <button type="submit" class="btn btn-primary">Search</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <table id="empoloyees-tblwrapper" class="table">
                                    <thead>
                                        <tr>
                                            <th>S.no.</th>
                                            <th>Reg No.</th>
                                            <th>Student Name</th>
                                            <th>Course</th>
                                            <th>Registered On</th>
                                            <th>Father's Name</th>
                                            <th>Mother's Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $count = 1;

                                        $conditions = array();

                                        if (!empty($_GET['roll_number'])) {
                                            $roll_number = $_GET['roll_number'];
                                            $conditions[] = "sid='$roll_number'";
                                        }
                                        if (!empty($_GET['email'])) {
                                            $email = $_GET['email'];
                                            $conditions[] = "email='$email'";
                                        }
                                        if (!empty($_GET['contact'])) {
                                            $contact = $_GET['contact'];
                                            $conditions[] = "(phone_number1 LIKE '%$contact%' OR phone_number2 LIKE '%$contact%')";
                                        }
                                        if (!empty($_GET['student_name'])) {
                                            $student_name = $_GET['student_name'];
                                            $conditions[] = "student_name LIKE '%$student_name%'";
                                        }

                                        $search_query = "SELECT * FROM tb_students";

                                        if (count($conditions) > 0) {
                                            $search_query .= " WHERE " . implode(' AND ', $conditions);
                                        }

                                        $result = mysqli_query($conn, $search_query);

                                        if (mysqli_num_rows($result) > 0) {
                                            while ($row = mysqli_fetch_array($result)) {
                                        ?>
                                                <tr class="px-3">
                                                    <td><?php echo $count; ?></td>
                                                    <td><?php echo "GPS" . $row['sid']; ?></td>
                                                    <td><?php echo $row['student_name']; ?></td>
                                                    <td><?php echo $row['course']; ?></td>
                                                    <td><?php echo $row['admission_date']; ?></td>
                                                    <td><?php echo $row['father_name']; ?></td>
                                                    <td><?php echo $row['mother_name']; ?></td>
                                                    <td>
                                                        <a href="update-students.php?uid=<?php echo $row['sid']; ?>" class="px-3"><i class="fa-solid fa-pen-to-square"></i></a>
                                                        <a href="delete-student.php?id=<?php echo $row['sid']; ?>" class="px-3"><i class="fa-sharp fa-solid fa-trash"></i></a>
                                                    </td>
                                                </tr>
                                        <?php
                                                $count++;
                                            }
                                        } else {
                                            echo "<tr><td colspan='8' class='text-center'>No records found</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
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
