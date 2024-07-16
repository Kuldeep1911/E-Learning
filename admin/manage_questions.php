<?php
session_start();
include "../config.php";
$exam_id = isset($_GET['id']);

if (empty($_SESSION['user_email'])) {
    echo "<script>window.location.href='index.php';</script>";
}
// if (!isset($_SESSION['admin_id'])) {
//     header("Location: login.php");
//     exit();
// }

// Function to fetch all questions
function fetchQuestions($conn) {
    $query = "SELECT * FROM questions";
    $result = mysqli_query($conn, $query);
    return $result;
}

// Delete question functionality
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];

    // Delete from questions table
    $delete_query = "DELETE FROM questions WHERE id = $delete_id";
    $delete_result = mysqli_query($conn, $delete_query);

    // Delete from options table associated with the question
    $delete_options_query = "DELETE FROM options WHERE question_number = $delete_id";
    $delete_options_result = mysqli_query($conn, $delete_options_query);

    if ($delete_result && $delete_options_result) {
        $message = "Question deleted successfully.";
    } else {
        $error_message = "Failed to delete question.";
    }
}

// Fetch all questions
$questions = fetchQuestions($conn);
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


            <div class="container">
                <div class="row">
                    <div class="col-lg-10">
                        <h1 class="my-4">Manage Questions</h1>

                    </div>
                    <div class="col-lg-2 float-end">
                        <a href="add_question.php?exam_id=<?php echo $exam_id; ?>">
                            <button class="btn btn-secondary btn-sm mt-2" value="<?php echo $exam_id;  ?>">+ Add Questions</button>
                        </a>
                    </div>

                </div>

        <?php if (isset($message)): ?>
            <div class="alert alert-success" role="alert">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger" role="alert">
                <?= $error_message ?>
            </div>
        <?php endif; ?>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Question Text</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($questions)): ?>
                    <tr>
                        <td><?= $row['question_number'] ?></td>
                        <td><?= $row['question_text'] ?></td>
                        <td>
                            <a href="edit_question.php?id=<?= $row['question_number'] ?>" class="btn btn-sm btn-primary">Edit</a>
                            <a href="?delete=<?= $row['question_number'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this question?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
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
