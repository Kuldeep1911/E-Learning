<?php
session_start();
include "../config.php";

if (empty($_SESSION['user_email'])) {
    echo "<script>window.location.href='index.php';</script>";
}
// Initialize $subject_id
$subject_id = '';

if (isset($_POST['submit'])) {
    $question_number = $_POST['question_number'];
    $question_text = $_POST['question_text'];
    $correct_choice = $_POST['correct_choice'];
    $question_description = $_POST['description'];
    $subject_id = $_POST['subject_id'];

    $choice = array();
    $choice[1] = $_POST['choice1'];
    $choice[2] = $_POST['choice2'];
    $choice[3] = $_POST['choice3'];
    $choice[4] = $_POST['choice4'];

    $query = "UPDATE questions SET question_text = '$question_text' , description = '$question_description' WHERE question_number = '$question_number' AND subject_id = '$subject_id'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $query = "DELETE FROM options WHERE question_number = '$question_number'";
        mysqli_query($conn, $query);

        foreach ($choice as $option => $value) {
            if ($value != "") {
                $is_correct = ($correct_choice == $option) ? 1 : 0;
                $query = "INSERT INTO options (question_number, is_correct, coption) VALUES ('$question_number', '$is_correct', '$value')";
                $insert_row = mysqli_query($conn, $query);

                if (!$insert_row) {
                    die("Option could not be inserted.");
                }
            }
        }
        $message = "Question has been updated successfully.";
        echo "<script>window.location.href='list_questions.php';</script>";
    }
}

if (isset($_GET['question_number'])) {
    $question_number = $_GET['question_number'];
    $query = "SELECT * FROM questions WHERE question_number = '$question_number'";
    $question_result = mysqli_query($conn, $query);

    if (mysqli_num_rows($question_result) == 1) {
        $question = mysqli_fetch_assoc($question_result);

        // Fetch subject_id from the question result
        $subject_id = $question['subject_id'];

        $query = "SELECT * FROM options WHERE question_number = '$question_number'";
        $options_result = mysqli_query($conn, $query);
        $choices = array();
        while ($option = mysqli_fetch_assoc($options_result)) {
            $choices[] = $option; // Store each option in $choices array
        }
    } else {
        // Handle case where question_number is invalid or not found
        die("Question not found.");
    }
}
$subjects = $conn->query("SELECT * FROM subjects");
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
                <h1>Edit Question </h1>
                <div class="card mt-5">
                    <div class="card-header">Edit Question</div>
                    <div class="card-body">
                        <?php if (isset($message)) : ?>
                            <div class="alert alert-success" role="alert">
                                <?= $message ?>
                            </div>
                        <?php endif; ?>

                        <form action="edit_question.php" method="post">
                            <input type="hidden" name="question_number" value="<?= $question['question_number'] ?>">
                            <div class="mb-3">
                                <label for="question_text" class="form-label">Question Text:</label>
                                <input type="text" class="form-control" name="question_text" value="<?= $question['question_text'] ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="subject_id">Subject ID:</label>
                                <input type="text" class="form-control" id="subject_id" name="subject_id" value="<?= $subject_id ?>" required>
                            </div>
                            <div class="mb-3 col-lg-10">
                                    <label for="subject_id" class="form-label">Subject:</label>
                                    <select id="subject_id" name="subject_id" class="form-control" required>
                                        <?php foreach ($subjects as $subject) : ?>
                                            <?php $selected = ($subject['id'] == $question['subject_id']) ? 'selected' : ''; ?>
                                            <option value="<?= $subject['id'] ?>" <?= $selected ?>><?= $subject['subject_name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>


                            <?php foreach ($choices as $index => $choice) : ?>
                                <div class="mb-3">
                                    <label for="choice<?= $index + 1 ?>" class="form-label">Choice <?= $index + 1 ?>:</label>
                                    <input type="text" class="form-control" name="choice<?= $index + 1 ?>" value="<?= $choice['coption'] ?>" required>
                                </div>
                            <?php endforeach; ?>
                            <div class="row">
                                <div class="mb-3 col-lg-2">
                                    <label for="correct_choice" class="form-label">Correct Option Number:</label>
                                    <input type="number" class="form-control" name="correct_choice" min="1" max="<?= count($choices) ?>" value="<?php
                                                                                                                                                foreach ($choices as $key => $choice) {
                                                                                                                                                    if ($choice['is_correct']) echo $key + 1; // Display correct option index
                                                                                                                                                }
                                                                                                                                                ?>" required>
                                </div>
                                <div class="mb-3 col-lg-10">
                                    <label for="description" class="form-label">Description</label>
                                    <input type="text" class="form-control" name="description" value="<?= $question['description'] ?>">
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