<?php
session_start();
include '../config.php';

if (empty($_SESSION['user_email'])) {
    echo "<script>window.location.href='index.php';</script>";
}
// Delete operation: Delete exam
if (isset($_GET["delete"])) {
    $id = $_GET["delete"];

    // Delete exam data
    $query = $conn->prepare("DELETE FROM exams WHERE id=?");
    $query->bind_param("i", $id);
    $result = $query->execute();

    if ($result) {
        $_SESSION['message'] = "Exam deleted successfully.";
        $_SESSION['msg_type'] = "success";
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    } else {
        $_SESSION['message'] = "Error deleting exam: " . $conn->error;
        $_SESSION['msg_type'] = "danger";
    }
   
}

// Update operation: Update exam details
if (isset($_POST['update'])) {
    $exam_id = $_POST['exam_id'];
    $title = $_POST['title'];
    // Add other fields for update here

    // Perform update query
    $query = $conn->prepare("UPDATE exams SET title=? WHERE id=?");
    $query->bind_param("si", $title, $exam_id);
    $result = $query->execute();

    if ($result) {
        $_SESSION['message'] = "Exam updated successfully.";
        $_SESSION['msg_type'] = "success";
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    } else {
        $_SESSION['message'] = "Error updating exam: " . $conn->error;
        $_SESSION['msg_type'] = "danger";
    }
    
}

// Read operation: Fetch all exams
$exams = [];
$result = $conn->query("SELECT * FROM exams");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $exams[] = $row;
    }
} else {
    $_SESSION['message'] = "No exams found.";
    $_SESSION['msg_type'] = "info";
}

// Function to fetch course title based on course_id
function getCourseTitle($course_id) {
    global $conn;
    $result = $conn->query("SELECT title FROM tb_courses WHERE id = $course_id");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['title'];
    } else {
        return "N/A";
    }
}

// Function to fetch branch name based on branch_id
function getBranchName($branch_id) {
    global $conn;
    $result = $conn->query("SELECT branch_name FROM branches WHERE id = $branch_id");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['branch_name'];
    } else {
        return "N/A";
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
                    <h2 class="mt-5 mb-3  ">Manage Exams</h2>
                    </div>
                    <div class="float-end col-lg-2">
                        <a href="add_exam.php"><button class="btn btn-secondary mt-5 mb-3">+ Add Exam</button></a>
                    </div>

                </div>
       

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?php echo $_SESSION['msg_type']; ?> alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['message']; ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <!-- Exams Table -->
        <div class="card">
            <div class="card-header">
                Exams List
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Sr. No:</th>
                            <th>Title</th>
                            <th>Course</th>
                            <th>Semester</th>
                            <th>Branch</th>
                            <th>Date</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php 
                        $counter =1;
                        foreach ($exams as $exam): ?>
                            <tr>
                                <td><a href="add_question.php?id=<?php echo $exam['id']; ?>"><?php echo $counter;  ?></a></td>
                                <td><?php echo $exam['title']; ?></td>
                                <td><?php echo getCourseTitle($exam['course_id']); ?></td>
                                <td><?php echo $exam['semester']; ?></td>
                                <td><?php echo getBranchName($exam['branch_id']); ?></td>
                                <td><?php echo $exam['exam_date']; ?></td>
                                <td><?php echo $exam['start_time']; ?></td>
                                <td><?php echo $exam['end_time']; ?></td>
                                <td>
                                    <!-- Edit Button with Modal Trigger -->
                                    <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editExamModal<?php echo $exam['id']; ?>">Edit</button>
                                    <a href="?delete=<?php echo $exam['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this exam?')">Delete</a>
                                </td>
                            </tr>

                            <!-- Edit Exam Modal -->
                     <!-- Edit Exam Modal -->
                    <div class="modal fade" id="editExamModal<?php echo $exam['id']; ?>" tabindex="-1" aria-labelledby="editExamModalLabel<?php echo $exam['id']; ?>" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width: 600px;">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editExamModalLabel<?php echo $exam['id']; ?>">Edit Exam</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <!-- Form for editing exam details -->
                                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                                        <input type="hidden" name="exam_id" value="<?php echo $exam['id']; ?>">
                                        <div class="form-group">
                                            <label for="title">Title:</label>
                                            <input type="text" class="form-control" id="title" name="title" value="<?php echo $exam['title']; ?>">
                                        </div>
                                        <!-- Add other fields for editing here -->
                                        <div class="form-group">
                                            <label for="course_id">Course:</label>
                                            <select class="form-control" id="course_id" name="course_id">
                                                <?php
                                                $courses = $conn->query("SELECT id, title FROM tb_courses");
                                                while ($course = $courses->fetch_assoc()) {
                                                    $selected = ($course['id'] == $exam['course_id']) ? 'selected' : '';
                                                    echo '<option value="' . $course['id'] . '" ' . $selected . '>' . $course['title'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="semester">Semester:</label>
                                            <input type="text" class="form-control" id="semester" name="semester" value="<?php echo $exam['semester']; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="branch_id">Branch:</label>
                                            <select class="form-control" id="branch_id" name="branch_id">
                                                <?php
                                                $branches = $conn->query("SELECT id, branch_name FROM branches");
                                                while ($branch = $branches->fetch_assoc()) {
                                                    $selected = ($branch['id'] == $exam['branch_id']) ? 'selected' : '';
                                                    echo '<option value="' . $branch['id'] . '" ' . $selected . '>' . $branch['branch_name'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="exam_date">Exam Date:</label>
                                            <input type="date" class="form-control" id="exam_date" name="exam_date" value="<?php echo $exam['exam_date']; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="start_time">Start Time:</label>
                                            <input type="time" class="form-control" id="start_time" name="start_time" value="<?php echo $exam['start_time']; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="end_time">End Time:</label>
                                            <input type="time" class="form-control" id="end_time" name="end_time" value="<?php echo $exam['end_time']; ?>">
                                        </div>
                                        <button type="submit" class="btn btn-primary" name="update">Save Changes</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                        <?php
                        $counter ++;
                    endforeach; ?>
                    </tbody>
                </table>
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
