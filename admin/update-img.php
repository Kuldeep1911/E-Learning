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
    $photo = $_FILES['photo']['name']; 
    $targetDirectory = '../img/students/'; // Specify the target directory to store the uploaded file
    $uniqueFileName = uniqid() . '_' . $photo; // Generate a unique file name
    $targetFilePath = $targetDirectory . $uniqueFileName;

    $get_img = "select profile_img from tb_students where sid='$id'";
    $img_result = mysqli_query($conn,$get_img);
    if(mysqli_num_rows($img_result)>0){
        $img_row = mysqli_fetch_array($img_result);
        $img_profile =$img_row['profile_img'];
       if(unlink("../img/students/".$img_profile)){
    
        $sql = "update tb_students set profile_img='$uniqueFileName'  where sid='$id'";
    $results = mysqli_query($conn,$sql);
    if($results){
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetFilePath)) {
            echo '<script>alert("Image updated successfully")</script>';
            echo '<script>window.location.href = "students.php";</script>';}
         else {
             echo "Error in uploading file.";
         }
    }else{
        echo '<script>alert("Error occured")</script>';
        echo '<script>window.location.href="students.php"</script>';

    }
       }
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
	<title>Update Img</title>
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
						Update Student Img</a>
				</li>
			</ol>
		</div>
        <div class="row d-flex justify-content-center my-5">
        <div class="col-xl-9 col-lg-8">
        <div class="card profile-card card-bx m-b30">
            <div class="card-header">
                <h6 class="title">Course update</h6>
            </div>
            <form class="profile-form" method="post" action="#" enctype="multipart/form-data">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6 m-b30">
                            <label class="form-label">Course name</label>
                            <input type="file" class="form-control" name="photo" >
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary" type="submit" name="submit">Update Image</button>
                    
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
			<p>Copyright © Developed by <a href="#" target="_blank">NGO Manager</a> <?php echo date('D')?></p>
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
	

	<script>
		var total_image = 1;

		function add_more_images() {
			total_image++;
			var html = '<div class="col-lg-6" style="margin-top:20px;" id="add_image_box_' + total_image + '"><label for="categories" class=" form-control-label">Image</label><input type="file" name="product_images[]" class="form-control" required><button type="button" class="btn btn-lg btn-danger btn-block" onclick=remove_image("' + total_image + '")><span id="payment-button-amount">Remove</span></button></div>';
			jQuery('#image_box').append(html);
		}

		function remove_image(id) {
			jQuery('#add_image_box_' + id).remove();
		}
	</script>

	<script>
			$(document).ready(function () {
		$('body').on('click', '.deleteBanner',function(){
			document.getElementById("feed_id").value = $(this).attr('data-id');
				console.log($(this).attr('data-id'));
			});
		});
	</script>


</body>

</html>


?>