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
    <title></title>
    
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
        
        <?php include("includes/header.php");?>


        <!--**********************************
        Main wrapper end
    ***********************************-->
	<div class="content-body">
            <!-- row -->	
			<div class="page-titles">
				<ol class="breadcrumb">
					<li><h5 class="bc-title">NGO</h5></li>
					<li class="breadcrumb-item"><a href="javascript:void(0)">
						<svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://WWW.w3.org/2000/svg">
							<path d="M2.125 6.375L8.5 1.41667L14.875 6.375V14.1667C14.875 14.5424 14.7257 14.9027 14.4601 15.1684C14.1944 15.4341 13.8341 15.5833 13.4583 15.5833H3.54167C3.16594 15.5833 2.80561 15.4341 2.53993 15.1684C2.27426 14.9027 2.125 14.5424 2.125 14.1667V6.375Z" stroke="#2C2C2C" stroke-linecap="round" stroke-linejoin="round"/>
							<path d="M6.375 15.5833V8.5H10.625V15.5833" stroke="#2C2C2C" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
					Dashboard </a>
					</li>
				</ol>
</div>
    <div class="container-fluid">
				<div class="row">
					<div class="col-xl-4 col-sm-6">
						<div class="card box-hover mb-5">
							<div class="card-body">
								<div class="d-flex align-items-center">
									<div class="icon-box icon-box-lg bg-success-light rounded-circle">
										<svg width="46" height="46" viewBox="0 0 46 46" fill="none" xmlns="http://WWW.w3.org/2000/svg">
											<path fill-rule="evenodd" clip-rule="evenodd" d="M22.9715 29.3168C15.7197 29.3168 9.52686 30.4132 9.52686 34.8043C9.52686 39.1953 15.6804 40.331 22.9715 40.331C30.2233 40.331 36.4144 39.2328 36.4144 34.8435C36.4144 30.4543 30.2626 29.3168 22.9715 29.3168Z" stroke="#3AC977" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
											<path fill-rule="evenodd" clip-rule="evenodd" d="M22.9714 23.0537C27.7304 23.0537 31.5875 19.1948 31.5875 14.4359C31.5875 9.67694 27.7304 5.81979 22.9714 5.81979C18.2125 5.81979 14.3536 9.67694 14.3536 14.4359C14.3375 19.1787 18.1696 23.0377 22.9107 23.0537H22.9714Z" stroke="#3AC977" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
										</svg>
									</div>
									<div class="total-projects ms-3">
										<?php $total_users = "select *from tb_users";
										      $total_results =mysqli_query($conn,$total_users); 
										  ?>
										<h3 class="text-success count"><?php echo mysqli_num_rows($total_results);?></h3> 
										<span>Total Users</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-4 col-sm-6">
						<div class="card box-hover mb-5">
							<div class="card-body">
								<div class="d-flex align-items-center">
									<div class="icon-box icon-box-lg bg-primary-light rounded-circle">
										<svg width="46" height="46" viewBox="0 0 46 46" fill="none" xmlns="http://WWW.w3.org/2000/svg">
											<path fill-rule="evenodd" clip-rule="evenodd" d="M32.8961 26.5849C34.1612 26.5849 35.223 27.629 35.0296 28.8783C33.8947 36.2283 27.6026 41.6855 20.0138 41.6855C11.6178 41.6855 4.8125 34.8803 4.8125 26.4862C4.8125 19.5704 10.0664 13.1283 15.9816 11.6717C17.2526 11.3579 18.5553 12.252 18.5553 13.5605C18.5553 22.4263 18.8533 24.7197 20.5368 25.9671C22.2204 27.2145 24.2 26.5849 32.8961 26.5849Z" stroke="var(--primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
											<path fill-rule="evenodd" clip-rule="evenodd" d="M41.1733 19.2019C41.2739 13.5059 34.2772 4.32428 25.7509 4.48217C25.0877 4.49402 24.5568 5.04665 24.5272 5.70783C24.3121 10.3914 24.6022 16.4605 24.764 19.2118C24.8134 20.0684 25.4864 20.7414 26.341 20.7907C29.1693 20.9526 35.4594 21.1736 40.0759 20.4749C40.7035 20.3802 41.1634 19.8355 41.1733 19.2019Z" stroke="var(--primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
										</svg>

									</div>
									<?php $total_projects = "select *from tb_users where user_type='1'";
										      $total_proj =mysqli_query($conn,$total_projects); 
										  ?>
									<div class="total-projects ms-3">
										<h3 class="text-primary count"><?php echo mysqli_num_rows($total_proj);?></h3> 
										<span>Total Admin</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-4 col-sm-6">
						<div class="card box-hover mb-5">
							<div class="card-body">
								<div class="d-flex align-items-center">
									<div class="icon-box icon-box-lg bg-danger-light rounded-circle">
										<svg width="46" height="46" viewBox="0 0 46 46" fill="none" xmlns="http://WWW.w3.org/2000/svg">
											<path d="M34.0396 20.974C36.6552 20.6065 38.6689 18.364 38.6746 15.6471C38.6746 12.9696 36.7227 10.7496 34.1633 10.3296" stroke="#FF5E5E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
											<path d="M37.4912 27.262C40.0243 27.6407 41.7925 28.5276 41.7925 30.3557C41.7925 31.6139 40.96 32.4314 39.6137 32.9451" stroke="#FF5E5E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
											<path fill-rule="evenodd" clip-rule="evenodd" d="M22.7879 28.0373C16.7616 28.0373 11.6147 28.9504 11.6147 32.5973C11.6147 36.2423 16.7297 37.1817 22.7879 37.1817C28.8141 37.1817 33.9591 36.2779 33.9591 32.6292C33.9591 28.9804 28.846 28.0373 22.7879 28.0373Z" stroke="#FF5E5E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
											<path fill-rule="evenodd" clip-rule="evenodd" d="M22.7876 22.8325C26.742 22.8325 29.9483 19.6281 29.9483 15.6719C29.9483 11.7175 26.742 8.51123 22.7876 8.51123C18.8333 8.51123 15.627 11.7175 15.627 15.6719C15.612 19.6131 18.7939 22.8194 22.7351 22.8325H22.7876Z" stroke="#FF5E5E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
											<path d="M11.5344 20.974C8.91691 20.6065 6.90504 18.364 6.89941 15.6471C6.89941 12.9696 8.85129 10.7496 11.4107 10.3296" stroke="#FF5E5E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
											<path d="M8.0825 27.262C5.54937 27.6407 3.78125 28.5276 3.78125 30.3557C3.78125 31.6139 4.61375 32.4314 5.96 32.9451" stroke="#FF5E5E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
										</svg>
									</div>
									<?php $not_verified = "select *from tb_students";
										      $total_not =mysqli_query($conn,$not_verified); 
										  ?>
									<div class="total-projects ms-3">
										<h3 class="text-danger count"><?php echo  mysqli_num_rows($total_not);?></h3> 
										<span>Total Students</span>
									</div>
								</div>
							</div>
						</div>
					</div>
	

        <!--**********************************
        Scripts
    ***********************************-->
</div>
        <!-- Required vendors -->
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

</body>

</html>