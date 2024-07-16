<?php
session_start();
include("../config.php");
if (empty($_SESSION['user_email'])) {
    echo "<script>window.location.href='index.php';</script>";
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

    <!-- PAGE TITLE HERE -->
    <title>Add Request|| Gravity</title>
    <!-- FAVICONS ICON -->
    <link rel="shortcut icon" type="image/png" href="assets/images/favicon.png">


    <link href="vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
    <link href="vendor/swiper/css/swiper-bundle.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet">
    <link href="vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="vendor/jvmap/jquery-jvectormap.css" rel="stylesheet">
    <link href="vendor/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet">

    <!-- tagify-css -->
    <link href="vendor/tagify/dist/tagify.css" rel="stylesheet">

    <!-- Style css -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">

</head>

<body data-typography="poppins" data-theme-version="light" data-layout="vertical" data-nav-headerbg="black" data-headerbg="color_1">


    <!--*******************
        Preloader start
    ********************-->
    <div id="preloader">
        <div class="lds-ripple">
            <div></div>
            <div></div>
        </div>
    </div>
    <!--*******************
        Preloader end
    ********************-->


    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">
        <!--**********************************
            Nav header start
        ***********************************-->
        <?php include("includes/header.php"); ?>
        <!--   Content body start
        ***********************************-->
        <div class="content-body ">
            <!-- row -->
            <div class="page-titles">
                <ol class="breadcrumb">
                    <li>
                        <h5 class="bc-title">Dashboard</h5>
                    </li>
                    <li class="breadcrumb-item"><a href="javascript:void(0)">
                            <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://WWW.w3.org/2000/svg">
                                <path d="M2.125 6.375L8.5 1.41667L14.875 6.375V14.1667C14.875 14.5424 14.7257 14.9027 14.4601 15.1684C14.1944 15.4341 13.8341 15.5833 13.4583 15.5833H3.54167C3.16594 15.5833 2.80561 15.4341 2.53993 15.1684C2.27426 14.9027 2.125 14.5424 2.125 14.1667V6.375Z" stroke="#2C2C2C" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M6.375 15.5833V8.5H10.625V15.5833" stroke="#2C2C2C" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            Manage Results</a>
                    </li>
                </ol>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">

                        <div class="card-body p-0">
                            <div class="table-responsive active-projects style-1">
                                <div class="tbl-caption">
                                   
                                    <h4 class="heading mb-0">Certificate Request</h4>
                                    <div>
                                        <div class="d-flex justify-content-end">
                                            <a href="#" class="mx-5 my-3" data-bs-toggle="modal" data-bs-target="#exampleModal"><button class="btn btn-primary">Add Request</button></a>
                                        </div>
                                    </div>
                                </div>
                                <table id="empoloyees-tblwrapper" class="table">
                                    <thead>
                                        <tr>
                                            <th>S.no.</th>
                                            <th>Reg No.</th>
                                            <th>Student Name</th>
                                            <th>Course Name</th>
                                            <th>Father</th>
                                            <th>Created On</th>
                                            <th>Approve Status</th>
                                            <?php if($_SESSION['user_type']=='0'){
                                                ?>
                                                <th>Approve Request</th>
                                          <?php  } ?>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = "SELECT *
                                        FROM tb_certifications
                                        ";
                                        $count=1;
                                        $result = mysqli_query($conn, $sql);
                                        if (mysqli_num_rows($result) > 0) {
                                            while ($row = mysqli_fetch_array($result)) {
                                        ?>
                                                <tr class="px-3">
                                                    <td><?php echo $count; ?></td>
                                                    <td><?php echo $row['sid']; ?></td>
                                                    <td><?php echo $row['student_name']; ?></td>
                                                    <td><?php echo $row['course_name']; ?></td>
                                                    <td><?php echo $row['father']; ?></td>
                                                    <td><?php echo $row['created_on']; ?></td>
                                                    <td><?php  
                                                    if($row['verified']=='0'){
                                                        echo '<div class="alert alert-danger">Not Approved</div>';
                                                    }else{
                                                        echo '<div class="alert alert-success">Approved</div>';
                                                    }
                                                    
                                                    
                                                    ?></td>
                                                       <?php if($_SESSION['user_type']=='0'){
                                                ?>
                                                <td><a href="approve-request.php?id=<?php echo $row['cerid'];?>"><button class="btn btn-primary">Approve Request</button></a></td>
                                          <?php  } ?>
                                                    <td>
                                                    <?php if($_SESSION['user_type']=='0'){?>
                                                        <a href="view-certificate.php?req_id=<?php echo $row['cerid'];?>" class="px-3"><i class="fa-solid fa-eye"></i></a>
                                                   <?php } ?>    
                                                    <a href="update-request.php?req_id=<?php echo $row['cerid'];?>" class="px-3"><i class="fa-solid fa-pen-to-square"></i></a>
                                                        <a href="delete-request.php?req_id=<?php echo $row['cerid'];?>" class="px-3"><i class="fa-sharp fa-solid fa-trash"></i></a>
                                                    </td>
                                                </tr>
                                        <?php
                                                $count++;
                                            }
                                        } ?>
                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>




    <!--**********************************
            Content body end
        ***********************************-->

    <!--**********************************
            Footer start
        ***********************************-->
    <div class="footer">
        <div class="copyright">
            <p>Copyright © Developed by <a href="#" target="_blank"></a> <?php echo date('Y') ?></p>
        </div>
    </div>
    <!--**********************************
            Footer end
        ***********************************-->
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add result</h5>
                    <p>Note :*field has to added manually</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <form method="post" action="#">
                            <div class="row">
                                <div class="col-sm-6 m-b30">
                                    <label class="form-label">Enter student roll No*</label>
                                    <input type="text" class="form-control" name="roll_no" placeholder="student roll no." required>
                                </div>
                                <div class="col-sm-6 m-b30">
                                    <label class="form-label">Student Name</label>
                                    <input type="text" class="form-control" name="student"  required>
                                </div>
                                
                                <div class="col-sm-6 m-b30">
                                    <label class="form-label">Father's Name</label>
                                    <input type="text" class="form-control" name="father"   required>
                                </div>
                                <div class="col-sm-6 m-b30">
                                <label class="form-label">Course Name</label>
                                <input type="text" class="form-control" name="course_name"  required> 
                                </div>
                                <div class="col-sm-6 m-b30">
                                <label class="form-label">Course Id</label>
                                <input type="text" class="form-control" name="course_id" required>
                                </div>
                                <div class="col-sm-6 m-b30">
                                <label class="form-label">Grade *</label>
                                <select class="form-control" name="grade" required>
                                    <option value="A">A</option>
                                    <option value="A+">A+</option>
                                    <option value="B">B</option>
                                    <option value="B+">B+</option>
                                    <option value="C">C</option>
                                    <option value="C+">C+</option>
                                    <option value="D">D</option>
                                    <option value="D+">D+</option>
                                </select>
                                </div>
                                <div class="col-sm-6 m-b30">
                                <label class="form-label">Valid From</label>
                                <input type="date" class="form-control" name="from" required>
                                </div>
                                <div class="col-sm-6 m-b30">
                                <label class="form-label">Valid To*</label>
                                <input type="date" class="form-control" name="to" required>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button class="btn btn-primary" name="create-request">Submit</button>

                                </div>
                            </div>
                         </form>
                    </div>
                    
                </div>
            

            
        </div>
    </div>
    </div>
 <?php   if (isset($_POST['create-request'])) {
    $rollNo = $_POST['roll_no'];
    $studentName = $_POST['student'];
    $fatherName = $_POST['father'];
    $courseName = $_POST['course_name'];
    $courseId = $_POST['course_id'];
    $grade = $_POST['grade'];
    $validFrom = $_POST['from'];
    $validTo = $_POST['to'];

        // Create the SQL query
    $sql = "INSERT INTO tb_certifications (sid, student_name, father, course_id, course_name, valid_from, valid_to, verified, grade) 
                    VALUES ('$rollNo', 
                    '$studentName', 
                    '$fatherName', 
                    '$courseId', '$courseName', 
                    '$validFrom', '$validTo', '0', 
                    '$grade')";

    // Execute the query
    if ($conn->query($sql) === TRUE) {
    $msg ="<div class='alert alert-success'>Records Inserted Successfully</div>";
    echo "<script>window.location.href='certificate-request.php'</script>";
    // Perform any additional actions after successful insertion
    } else {
    echo "Error: " . $conn->error;
    }


    // Close the connection
    $conn->close();
}
    ?>


    <!--**********************************
           Support ticket button start
        ***********************************-->

    <!--**********************************
           Support ticket button end
        ***********************************-->


    </div>
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="vendor/global/global.min.js"></script>
    <script src="vendor/chart.js/Chart.bundle.min.js"></script>
    <script src="vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script>

    <!-- Dashboard 1 -->
    <script src="vendor/draggable/draggable.js"></script>


    <!-- tagify -->
    <script src="vendor/tagify/dist/tagify.js"></script>

    <script src="vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/js/dataTables.buttons.min.js"></script>
    <script src="vendor/datatables/js/buttons.html5.min.js"></script>
    <script src="vendor/datatables/js/jszip.min.js"></script>
    <script src="js/plugins-init/datatables.init.js"></script>

    <!-- Apex Chart -->

    <script src="vendor/bootstrap-datetimepicker/js/moment.js"></script>
    <script src="vendor/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>


    <!-- Vectormap -->
    <script src="vendor/jqvmap/js/jquery.vmap.min.js"></script>
    <script src="vendor/jqvmap/js/jquery.vmap.world.js"></script>
    <script src="vendor/jqvmap/js/jquery.vmap.usa.js"></script>
    <script src="js/custom.js"></script>
    <script src="js/deznav-init.js"></script>

    <script>
        $(document).ready(function() {
            // Listen for the change event of the roll number input field
            $('input[name="roll_no"]').on('input', function() {
                var rollNo = $(this).val();
                
                // Make an AJAX request to fetch the student details
                $.ajax({
                    url: 'fetch_student.php',
                    method: 'POST',
                    data: { roll_no: rollNo },
                    dataType: 'json',
                    success: function(response) {
                        if (response !== null) {
                            $('input[name="course"]').val(response.total_sem);
                            $('input[name="father"]').val(response.father);
                            $('input[name="student"]').val(response.student);
                            $('input[name="course_id"]').val(response.course_id);
                            $('input[name="course_name"]').val(response.total_sem);
                            
                        } else {
                            $('input[name="total_sem"]').val('');
                            
                            $('input[name="father"]').val('');
                            $('input[name="student"]').val('');
                            $('input[name="course_id"]').val('');
                            $('input[name="course_name"]').val('');
                           
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                        alert('Error occurred while fetching student details.');
                    }
                });
            });
        });
    </script>




</body>

</html>