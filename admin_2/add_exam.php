<?php
session_start();
include '../config.php';

if (empty($_SESSION['user_email'])) {
    echo "<script>window.location.href='index.php';</script>";
}

// Function to sanitize input data
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Check if branch ID is set in session
if (isset($_SESSION['branch'])) {
    $branch_id = $_SESSION['branch'];
} else {
    echo "Branch ID is missing. Please login again.";
    exit(); // Stop further execution
}

if (isset($_POST["submit"])) {
    // Sanitize and retrieve form data
    $title = sanitizeInput($_POST['title']);
    $course_id = intval($_POST['course']); // Convert to integer
    $semester = sanitizeInput($_POST['semester']);
    $exam_date = sanitizeInput($_POST['exam_date']);
    $start_time = sanitizeInput($_POST['start_time']);
    $end_time = sanitizeInput($_POST['end_time']);
    $subjects = $_POST['subjects']; // Array of subjects and their details

    // Validate required fields
    if (!empty($title) && !empty($course_id) && !empty($semester) && !empty($branch_id) && !empty($exam_date) && !empty($start_time) && !empty($end_time) && !empty($subjects)) {
        
        // Insert exam data
        $insert_exam_query = "INSERT INTO exams (title, course_id, semester, branch_id, exam_date, start_time, end_time) VALUES ('$title', $course_id, '$semester', $branch_id, '$exam_date', '$start_time', '$end_time')";
        $result_exam = $conn->query($insert_exam_query);

        if ($result_exam) {
            $exam_id = $conn->insert_id; // Get the ID of the inserted exam

            // Insert subjects data
            $insert_subject_query = "INSERT INTO subjects (exam_id, subject_name, min_marks, max_marks, semester) VALUES ";
            $value_strings = [];

            foreach ($subjects as $subject) {
                $subject_name = sanitizeInput($subject['name']);
                $min_marks = intval($subject['min_marks']); // Convert to integer
                $max_marks = intval($subject['max_marks']); // Convert to integer
                $subject_semester = sanitizeInput($subject['semester']);

                $value_strings[] = "($exam_id, '$subject_name', $min_marks, $max_marks, '$subject_semester')";
            }

            if (!empty($value_strings)) {
                $insert_subject_query .= implode(", ", $value_strings);
                $result_subject = $conn->query($insert_subject_query);

                if (!$result_subject) {
                    echo "Error inserting subjects: " . $conn->error;
                    exit();
                }
            }

            // Redirect to manage exams after successful insert
            header('Location: manage_exams.php');
            exit();
        } else {
            echo "Error inserting exam: " . $conn->error;
        }

    } else {
        echo "All fields are required.";
    }
}

// Query to retrieve courses
$courses = $conn->query("SELECT * FROM tb_courses");
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
        <?php include "includes/header.php"; ?>

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
    <div class="card mt-5">
        <h5 class="card-header">Add Exam</h5>
        <div class="card-body">
            <h5 class="card-title">Add New Exam</h5>
            
            <form method="POST" action="#">
                <div class="mb-3">
                    <label for="title" class="form-label">Exam Title:</label>
                    <input type="text" id="title" name="title" class="form-control" required>
                </div>
                
                <div class="row">
                    <div class="mb-3 col-lg-6">
                        <label for="course">Course</label>
                        <select id="course_id" name="course" class="form-control" required>
                            <option value="">Select Course</option>
                            <?php foreach ($courses as $course): ?>
                                <option value="<?= $course['id'] ?>"><?= $course['title'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3 col-lg-6">
                        <label for="semester">Semester</label>
                        <input type="text" id="semester" name="semester" class="form-control" required>
                    </div>
                </div>
                
                

                <div class="mb-3">
                    <label for="exam_date" class="form-label">Exam Date:</label>
                    <input type="date" id="exam_date" name="exam_date" class="form-control" required>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="start_time" class="form-label">Start Time:</label>
                        <input type="time" id="start_time" name="start_time" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="end_time" class="form-label">End Time:</label>
                        <input type="time" id="end_time" name="end_time" class="form-control" required>
                    </div>
                </div>

                <div id="subjectsContainer">
                    <!-- Subject fields will be added here dynamically -->
                </div>
                
                <button type="button" class="btn btn-success mt-3" id="addSubject">Add Subject</button>
                
                <button type="submit" class="btn btn-primary mt-4 float-end" name="submit">Add Exam</button>
            </form>
            <a href="manage_exams.php" class="btn btn-outline-secondary mt-4">Back to Home</a>
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
    <script>
        document.getElementById('addSubject').addEventListener('click', function() {
            const subjectsContainer = document.getElementById('subjectsContainer');

            const subjectDiv = document.createElement('div');
            subjectDiv.classList.add('mb-4', 'subject-row', 'p-3', 'border', 'border-secondary', 'rounded');

            subjectDiv.innerHTML = `
                <div class="row">
                    <div class="col-md-4">
                        <label for="subject_name" class="form-label">Subject:</label>
                        <input type="text" name="subjects[][name]" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label for="min_marks" class="form-label">Min Marks:</label>
                        <input type="number" name="subjects[][min_marks]" class="form-control" required>
                    </div>
                    <div class="col-md-2">
                        <label for="max_marks" class="form-label">Max Marks:</label>
                        <input type="number" name="subjects[][max_marks]" class="form-control" required>
                    </div>
                    <div class="col-md-2 d-flex align-items-end btn-lg ">
                        <button type="button" class="btn btn-danger btn-sm removeSubject">Remove</button>
                    </div>
                </div>
            `;

            subjectsContainer.appendChild(subjectDiv);

            subjectDiv.querySelector('.removeSubject').addEventListener('click', function() {
                subjectsContainer.removeChild(subjectDiv);
            });
        });
    </script>

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