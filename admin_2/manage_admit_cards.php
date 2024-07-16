<!-- manage_admit_cards.php -->

<?php
session_start();
include "../config.php";
if (empty($_SESSION['user_email'])) {
    echo "<script>window.location.href='index.php';</script>";
}

$message = ""; // Initialize the $message variable

// Handle delete request
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $sql = "DELETE FROM admit_cards WHERE id = $id";
    if (mysqli_query($conn, $sql)) {
        $message = "Admit card deleted successfully!";
    } else {
        $message = "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

// Fetch admit cards
$sql = "SELECT * FROM admit_cards";
$result = mysqli_query($conn, $sql);
$admitCards = mysqli_fetch_all($result, MYSQLI_ASSOC);
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
        <?php if (!empty($message)): ?>
            <div class="alert alert-info"><?= $message ?></div>
        <?php endif; ?>
<div class="row">
    <div class="col-lg-10">
    <h3 class="section-title mt-5">Admit Card List</h3>
    </div>
    <div class="col-lg-2 float-end ">
        <a href="admit_card.php"><button class="btn btn-secondary btn-md ">+ Add Admit Cards</button></a>
    </div>
</div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>COURSE</th>
                    <th>EXAM</th>
                    <th>DATE</th>
                    <th>RECPT NO.</th>
                    <th>EXAM CENTER ADDRESS</th>
                    <th>NAME</th>
                    <th>D.O.B</th>
                    <th>CONTACT</th>
                    <th>ADDRESS</th>
                    <th>ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($admitCards as $admitCard): ?>
                    <tr>
                        <td><?= $admitCard['id'] ?></td>
                        <td><?= $admitCard['course'] ?></td>
                        <td><?= $admitCard['exam'] ?></td>
                        <td><?= $admitCard['exam_date'] ?></td>
                        <td><?= $admitCard['recept_no'] ?></td>
                        <td><?= $admitCard['exam_center_address'] ?></td>
                        <td><?= $admitCard['name'] ?></td>
                        <td><?= $admitCard['dob'] ?></td>
                        <td><?= $admitCard['contact'] ?></td>
                        <td><?= $admitCard['address'] ?></td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="edit_admit_card.php?id=<?= $admitCard['id'] ?>" class="btn btn-warning btn-sm m-1 rounded">Edit</a>
                                <a href="manage_admit_cards.php?delete_id=<?= $admitCard['id'] ?>" class="btn btn-danger btn-sm m-1 rounded" onclick="return confirm('Are you sure?')">Delete</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
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
