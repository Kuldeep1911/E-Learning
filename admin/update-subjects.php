<?php
session_start();
include("../config.php");
if (empty($_SESSION['user_email'])) {
    echo "<script>window.location.href='index.php';</script>";
}
if($_SESSION['user_type']=='1'){
	echo "<script>window.location.href='dashboard.php';</script>";
}
$id = $_GET['id'];
if(empty($id)){
    echo "<script>window.location.href='courses.php';</script>";

}
if(isset($_POST['submit'])){
    $course = $_POST['course_id'];
    $sub_name =$_POST['sub_name'];
    $semester = $_POST['semester'];
    $total_marks = $_POST['total_marks'];
    $email = $_SESSION['user_email'];

    $sql = "update tb_subjects set course_id='$course' ,semester='$semester',total_marks='$total_marks',sub_name='$sub_name',created_by='$email'";
    $results = mysqli_query($conn,$sql);
    if($results){
        echo '<script>alert("Subjects Updated Successfully")</script>';
        echo '<script>window.location.href="subjects.php"</script>';
    }else{
        echo '<script>alert("Error occured")</script>';
        echo '<script>window.location.href="subjects.php"</script>';

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

	<!-- PAGE TITLE HERE -->
	<title>updateCourse</title>
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
         <?php  include("includes/header.php");?>
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
						Update Course</a>
				</li>
			</ol>
		</div>
        <div class="row d-flex justify-content-center my-5">
        <div class="col-xl-9 col-lg-8">
        <div class="card profile-card card-bx m-b30">
            <div class="card-header">
                <h6 class="title">Course update</h6>
                <?php 
                $fetch_course = "select *from tb_subjects where subid='$id'";
                $fetch_result = mysqli_query($conn,$fetch_course);
                $fetch_row = mysqli_fetch_array($fetch_result);

                ?>
            </div>
            <form class="profile-form" method="post" action="#">
                <div class="card-body">
                    <div class="row">
                    <div class="col-sm-6 m-b30">
                                        <label class="form-label">Course</label>
                                        <select class="form-control" name="course_id" required>
                                            <?php
                                            $get_course = "select *from tb_courses";
                                            $course_result = mysqli_query($conn, $get_course);
                                            if (mysqli_num_rows($course_result) > 0) {
                                                while ($course_row = mysqli_fetch_array($course_result)) {
                                            ?>
                                                    <option value="<?php echo $course_row['cid']; ?>"><?php echo $course_row['course_name'] ?></option>
                                            <?php  }
                                            } ?>
                                        </select>
                                    </div>
                        <div class="col-sm-6 m-b30">
                            <label class="form-label">Semester No.</label>
                            <input type="text" class="form-control" name="semester" placeholder="Semester No." value="<?php echo $fetch_row['semester'];?>">
                        </div>
                        <div class="col-sm-6 m-b30">
                            <label class="form-label">Subject name</label>
                            <input type="text" class="form-control" name="sub_name" placeholder="Subject Name" value="<?php echo $fetch_row['sub_name'] ?>">
                        </div>
                        <div class="col-sm-6 m-b30">
                            <label class="form-label">Total marks</label>
                            <input type="text" class="form-control" name="total_marks" placeholder="Total Marks" value="<?php echo $fetch_row['total_marks']?>">
                        </div>
                    </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary" type="submit" name="submit">updateCourse</button>
                    
                </div>
            </form>
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
			<p>Copyright © Developed by <a href="#" target="_blank">Gravity Institute</a> <?php echo date('Y')?></p>
		</div>
	</div>
	<!--**********************************
            Footer end
        ***********************************-->

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
	



</body>

</html>