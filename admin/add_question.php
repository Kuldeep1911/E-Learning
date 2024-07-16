<?php
session_start();
include "../config.php";

if (empty($_SESSION['user_email'])) {
    echo "<script>window.location.href='index.php';</script>";
}

// Ensure connection is established
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch exam_id from URL and assign it to a variable
$exam_id = isset($_GET['id']) ? $_GET['id'] : null;

if ($exam_id) {
    // Prepare the SQL query to check if the exam_id exists
    $stmt = $conn->prepare("SELECT COUNT(*) FROM exams WHERE id = ?");
    $stmt->bind_param("i", $exam_id);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    // Check if the exam_id exists
    if ($count > 0) {
        // exam_id exists, fetch subjects for this exam
        $subjects_query = "SELECT * FROM subjects WHERE exam_id='$exam_id'";
        $subjects_result = mysqli_query($conn, $subjects_query);
        $subjects = mysqli_fetch_all($subjects_result, MYSQLI_ASSOC);
    } else {
        // exam_id does not exist, redirect to exams.php or handle as per your logic
        echo "<script>window.location.href='exams.php';</script>";
        exit();
    }
} else {
    // If 'exam_id' is not provided, redirect to exams.php or handle as per your logic
    echo "<script>window.location.href='exams.php';</script>";
    exit();
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['submit'])) {
        $question_text = $_POST['question_text'];
        $correct_choice = $_POST['correct_choice'];
        $question_description = isset($_POST['description']) ? $_POST['description'] : '';
        $exam_id = isset($_GET['id']) ? $_GET['id'] : '';  // Get from POST
        $subject_id = isset($_POST['subject_id']) ? $_POST['subject_id'] : '';

        // Choice array
        $choices = array();
        $choices[] = mysqli_real_escape_string($conn, $_POST['choice1']);
        $choices[] = mysqli_real_escape_string($conn, $_POST['choice2']);
        $choices[] = mysqli_real_escape_string($conn, $_POST['choice3']);
        $choices[] = mysqli_real_escape_string($conn, $_POST['choice4']);

        // Escape inputs to prevent SQL injection
        $question_text = mysqli_real_escape_string($conn, $question_text);
        $correct_choice = mysqli_real_escape_string($conn, $correct_choice);
        $question_description = mysqli_real_escape_string($conn, $question_description);
        $exam_id = mysqli_real_escape_string($conn, $exam_id);
        $subject_id = mysqli_real_escape_string($conn, $subject_id);

        // First query for the question table
        $query = "INSERT INTO questions (question_text, description, exam_id, subject_id) 
                  VALUES ('$question_text', '$question_description', '$exam_id', '$subject_id')";
                  


        $result = mysqli_query($conn, $query);
        if (!$result) {
            die("1st query for question could not be executed: " . mysqli_error($conn));
        }

        if ($result) {
            // Get the question_number of the inserted question
            $question_number = mysqli_insert_id($conn);

            // Insert options into options table
            foreach ($choices as $key => $choice) {
                $is_correct = ($correct_choice == ($key + 1)) ? 1 : 0;

                $query = "INSERT INTO options (question_number, is_correct, coption) 
                          VALUES ('$question_number', '$is_correct', '$choice')";

                $insert_row = mysqli_query($conn, $query);

                // Validate insert choice
                if (!$insert_row) {
                    die("Query for choice could not be executed: " . mysqli_error($conn));
                }
            }

            $message = "Question and options have been added successfully.";
        }
    }
}

// Fetch the next question number
$query = "SELECT * FROM questions";
$questions_result = mysqli_query($conn, $query);
$total = mysqli_num_rows($questions_result);
$next_question_number = $total + 1;
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
            <div class="container mt-5">
                <div class="card mt-5">
                    <div class="card-header">Add Question</div>
                    <div class="card-body">
                        <h5 class="card-title">Add New Question</h5>

                        <?php if (isset($message)): ?>
                            <div class="alert alert-success" role="alert">
                                <?=$message?>
                            </div>
                        <?php endif;?>

                        <form action="#" method="post">
                            <div class="row">
                                <div class="mb-3 col-lg-2">
                                    <label for="question_number" class="form-label">Question Number:</label>
                                    <input type="number" class="form-control" name="question_number" value="<?php echo $next_question_number; ?>" readonly>
                                </div>
                                <div class="mb-3 col-lg-10">
                                    <label for="subject_id" class="form-label">Subject:</label>
                                    <select id="subject_id" name="subject_id" class="form-control" required>
                                        <option value="">Select Subject</option>
                                        <?php foreach ($subjects as $subject): ?>
                                            <option value="<?=$subject['id']?>"><?=$subject['subject_name']?></option>
                                        <?php endforeach;?>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="question_text" class="form-label">Question Text:</label>
                                <input type="text" class="form-control" name="question_text" required>
                            </div>

                            <div class="row">
                                <div class="mb-3 col-lg-6">
                                    <label for="choice1" class="form-label">Choice 1:</label>
                                    <input type="text" class="form-control" name="choice1" required>
                                </div>
                                <div class="mb-3 col-lg-6">
                                    <label for="choice2" class="form-label">Choice 2:</label>
                                    <input type="text" class="form-control" name="choice2" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="mb-3 col-lg-6">
                                    <label for="choice3" class="form-label">Choice 3:</label>
                                    <input type="text" class="form-control" name="choice3" required>
                                </div>
                                <div class="mb-3 col-lg-6">
                                    <label for="choice4" class="form-label">Choice 4:</label>
                                    <input type="text" class="form-control" name="choice4" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="mb-3 col-lg-2">
                                    <label for="correct_choice" class="form-label">Correct Option Number:</label>
                                    <input type="number" class="form-control" name="correct_choice" min="1" max="4" required>
                                </div>
                                <div class="mb-3 col-lg-10">
                                    <label for="description" class="form-label">Description:</label>
                                    <input type="text" class="form-control" name="description">
                                </div>
                            </div>

                            <button type="submit" name="submit" class="btn btn-success">Submit</button>
                        </form>
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
