<?php
session_start();
if (empty($_SESSION['user_email'])) {
    echo "<script>window.location.href='index.php';</script>";
}
include "../config.php";

// SQL query to fetch questions and options
$query = "
SELECT 
    q.question_number, 
    q.question_text, 
    o.coption, 
    o.is_correct
FROM 
    questions q 
LEFT JOIN 
    options o 
ON 
    q.question_number = o.question_number 
ORDER BY 
    q.question_number, o.id;
";

// Execute the query
$questions = mysqli_query($conn, $query);

// Check for errors
if (!$questions) {
    die("Query Failed: " . mysqli_error($conn));
}

// Organize the data
$questionsArray = [];
while ($row = mysqli_fetch_assoc($questions)) {
    $questionsArray[$row['question_number']]['text'] = $row['question_text'];
    $questionsArray[$row['question_number']]['options'][] = [
        'text' => $row['coption'],
        'is_correct' => $row['is_correct']
    ];
}
$counter = 1;
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
        .correct-option {
            background-color: green;
            color: #000;
            /* Light green background for correct options */
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

            <div class="container mt-2">
                <div class="row">
                    <div class="col-lg-10">
                        <h2 class="mt-5 mb-3">Questions</h2>
                    </div>
                    <div class="float-end col-lg-2">
                        <a href="add_question.php">
                            <button class="btn btn-primary btn-md mt-5 mb-3">+ Add Questions</button>
                        </a>
                    </div>
                </div>
                <div class="table-container mx-0">
                    <table class="table table-hover table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>Question Number</th>
                                <th>Question Text</th>
                                <th colspan="4">Options</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($questionsArray as $questionNumber => $data): ?>
                                <tr>
                                    <td><?= $counter; ?></td>
                                    <td><?= $data['text'] ?></td>
                                    <?php foreach ($data['options'] as $option): ?>
                                        <td class="<?= $option['is_correct'] ? 'correct-option' : ''; ?>">
                                            <?= $option['text'] ?>
                                        </td>
                                    <?php endforeach; ?>
                                    <?php // Fill empty columns for consistent row width
                                        for ($i = count($data['options']); $i < 4; $i++): ?>
                                        <td></td>
                                    <?php endfor; ?>
                                    <td>
                                        <div class="btn-group">
                                            <a href="edit_question.php?question_number=<?= $questionNumber ?>" class="btn btn-primary btn-sm m-2 rounded">Edit</a>
                                            <a href="delete_question.php?question_number=<?= $questionNumber ?>" class="btn btn-danger btn-sm m-2 rounded" onclick="return confirm('Are you sure?')">Delete</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php 
                                $counter++;
                            endforeach; ?>
                        </tbody>
                    </table>
                </div>
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