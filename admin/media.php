<?php
session_start();
include("../config.php");
if (empty($_SESSION['user_email'])) {
    echo "<script>window.location.href='index.php';</script>";
}
if($_SESSION['user_type']=='1'){
	echo "<script>window.location.href='dashboard.php';</script>";
}
if (isset($_POST['submit'])) {

    $image = rand(111111111, 999999999) . '_' . $_FILES['product_images']['name'];
    move_uploaded_file($_FILES['product_images']['tmp_name'], '../img/media/' . $image);
    $sql = "Insert into tb_media_img(img_name) Values('$image')";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        echo "<script>alert('File uploaded succesfully')</script>";
        echo '<script>window.location.href="media.php";</script>';
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
    <title>Media Management || Gravity</title>
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
                            Manage Media</a>
                    </li>
                </ol>
            </div>

            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-12 wid-100">
                        <div class="row">
                            <form method="post" action="#" enctype="multipart/form-data">
                                <div class="form-group">
                                    <div class="row" id="image_box">
                                        <div class="col-lg-12">

                                            <label for="" class=" pt-3">Media Images</label>
                                            <input type="file" name="product_images" class="form-control" required>
                                        </div>



                                    </div>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn mt-5 btn-lg btn-primary" name="submit">Add Media Images</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="row mt-5 mb-5">
                    <?php
            
                     $banner ="select *from tb_media_img";
                     $banner_result = mysqli_query($conn,$banner);
                     $banner_count = mysqli_num_rows($banner_result);
                     if($banner_count>0){
                             while($banner_row =mysqli_fetch_array($banner_result)){?>
                    <div class="col-lg-12 col-xl-6 col-xxl-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="row m-b-30">
                                    <div class="col-md-5 col-xxl-12">
                                        <div class="new-arrival-product mb-4 mb-xxl-4 mb-md-0">
                                            <div class="new-arrivals-img-contnent">
                                                <img class="img-fluid" src="../img/media/<?php echo $banner_row['img_name']?>" alt="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-7 col-xxl-12">
                                        <div class="new-arrival-content position-relative">
                                            <h4><a><?php echo $banner_row['img_name']?>"</a></h4>
                                            <div class="comment-review star-rating d-flex justify-content-end">
                                                <button class="btn btn-primary deleteBanner" data-id="<?php echo  $banner_row['mediaid']?>" data-bs-toggle="modal" data-bs-target="#deleteModal">Delete Now</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php }}?>


                </div>
            </div>
        </div>
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Delete Confirmation</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				
				<form action="#" method="post">
				<input id="feed_id" name="feed_id" value="" hidden/>
				<label>Are you sure you want to delete?</label>
				<br>
				<button type="submit" name="deleteBanner" class="btn btn-primary">Yes,I'm</button>
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
				</form>
			
			</div>
			</div>
		</div>
		</div>
		<?php

			if(isset($_POST['deleteBanner'])){
			$banner_id = $_POST['feed_id'];
			$banner_name = "select *from tb_media_img where mediaid='$banner_id'";
			$banner_name_result = mysqli_query($conn,$banner_name);
			$banner_name_row = mysqli_fetch_array($banner_name_result);
			$delete_banner =unlink('../img/media/'.$banner_name_row["img_name"].'');
			if($delete_banner){
				$delete_sql ="delete from tb_media_img where mediaid='$banner_id'";
				$delete_banner_result = mysqli_query($conn,$delete_sql);

				if($delete_banner_result){
					echo '<script>alert("Image Deleted Succesfully");</script>';
					echo '<script>window.location.href="media.php"</script>';

				}
			}else{
				echo "<script>alert('Failed to delete images from folder,please check that file exists in the folder');</script>";
				echo '<script>window.location.href="media.php"</script>';
			}
			$conn->close();

		}
			?>
    </div>




    <!--**********************************
            Content body end
        ***********************************-->

    <!--**********************************
            Footer start
        ***********************************-->
    <div class="footer">
        <div class="copyright">
            <p>Copyright © Developed by <a href="#" target="_blank">Gravity Institute</a> <?php echo date('Y') ?></p>
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
			$(document).ready(function () {
		$('body').on('click', '.deleteBanner',function(){
			document.getElementById("feed_id").value = $(this).attr('data-id');
				console.log($(this).attr('data-id'));
			});
		});
	</script>




</body>

</html>