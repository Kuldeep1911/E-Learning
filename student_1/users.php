<?php 
session_start();
include "../config.php";
if(empty($_SESSION['user'])){
    echo "<script>window.location.href='index.php';</script>";
}

// Fetch data from the stores table
$sql = "SELECT * FROM users";
$result = $conn->query($sql);

// Fetch all rows as an associative array
$users = [];
if ($result->num_rows > 0) {
    $users = $result->fetch_all(MYSQLI_ASSOC);
}
$counter = 1;
?>


        <div class="content-body">

            <div class="container ">
    <h2 class="mt-4">Users</h2>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Email</th>
                <th>Name</th>
                <th>Phone</th>
                <th>State</th>
                <th>City</th>
                <th>Zip</th>
                <th>Address</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($users)) : ?>
                <?php foreach ($users as $user) : ?>
                    <tr>
                        <td><?php echo $counter; ?></td>
                        <td><?php echo $user['email']; ?></td>
                        <td><?php echo $user['first_name'] . $user['last_name'] ; ?></td>
                        <td><?php echo $user['phone']; ?></td>
                        <td><?php echo $user['state']; ?></td>
                        <td><?php echo $user['city']; ?></td>
                        <td><?php echo $user['zip']; ?></td>
                        <td><?php echo $user['address']; ?></td>
                    </tr>
                    
                <?php endforeach;
                $counter ++;
                 ?>
                
            <?php else : ?>
                <tr><td colspan="10">No records found</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
        </div>



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
         <?php include("includes/header.php");?>
     <!-- Main wrapper end
    ***********************************-->

    <!--**********************************
        Scripts
    ***********************************-->
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
	

	

	
</body>
</html>