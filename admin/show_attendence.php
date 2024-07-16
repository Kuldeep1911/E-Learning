<?php
session_start();
include "../config.php";

if (empty($_SESSION['user_email'])) {
    echo "<script>window.location.href='show_attendence.php';</script>";
    exit;
}

// Fetch student data
$sql_students = "SELECT * FROM tb_students";
$result_students = $conn->query($sql_students);

// Fetch attendance data
$sql_attendance = "
    SELECT 
        s.sid,
        s.student_name,
        COUNT(CASE WHEN a.attendance_status = 'present' THEN 1 END) AS days_present,
        COUNT(CASE WHEN a.attendance_status = 'absent' THEN 1 END) AS days_absent,
        COUNT(CASE WHEN a.attendance_status = 'leave' THEN 1 END) AS days_leave
    FROM 
        tb_students s
    LEFT JOIN 
        tb_attendance a ON s.sid = a.student_id
    GROUP BY 
        s.sid, s.student_name
    ORDER BY 
        s.student_name";
$result_attendance = $conn->query($sql_attendance);

?>

<div class="content-body">
    <div class="container mx-4">
        <h2 class="mt-4">Student Attendance Summary</h2>

        <!-- Search bar -->
        <input type="text" id="searchInput" class="form-control mb-4" placeholder="Search by name">

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th  class="bg-success text-bg-light text-center font-weight-bold">Days Present</th>
                    <th class="bg-danger text-bg-light text-center font-weight-bold">Days Absent</th>
                    <th class="bg-warning text-bg-light text-center font-weight-bold">Days Leave</th>
                </tr>
            </thead>
            <tbody id="studentTableBody">
                <?php
                if ($result_attendance->num_rows > 0) {
                    $counter = 1;
                    while ($row = $result_attendance->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $counter . "</td>";
                        echo "<td>" . $row['student_name'] . "</td>";
                        echo "<td class='text-center bg-success text-white '>" . $row['days_present'] . "</td>";
                        echo "<td class='text-center bg-danger text-white'>" . $row['days_absent'] . "</td>";
                        echo "<td class='text-center bg-warning text-white'>" . $row['days_leave'] . "</td>";
                        echo "</tr>";
                        $counter++;
                    }
                } else {
                    echo "<tr><td colspan='5'>No attendance records found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php
$conn->close();
?>

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
    <!-- Main wrapper end
    ***********************************-->

    <!--**********************************
    Scripts
    ***********************************-->
    <!-- Required vendors -->
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

    <script>
        // Function to handle search
        function filterTable() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("searchInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("studentTableBody");
            tr = table.getElementsByTagName("tr");

            // Loop through all table rows, and hide those who don't match the search query
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[1]; // Index 1 is where student name is
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }

        // Attach event listener to search input
        document.getElementById("searchInput").addEventListener("keyup", filterTable);
    </script>
</body>

</html>
