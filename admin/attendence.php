<?php 
session_start();
include "../config.php";

if(empty($_SESSION['user_email'])){
    echo "<script>window.location.href='login.php';</script>";
    exit;
}

$branchTime = $_SESSION['branch_time'] ?? '';

$searchQuery = '';
if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
}

// Fetch data from the students table
$sql = "SELECT * FROM tb_students WHERE (student_name LIKE ? OR sid LIKE ?) AND branchtime = ?";
$stmt = $conn->prepare($sql);
$searchParam = '%' . $searchQuery . '%';
$stmt->bind_param('sss', $searchParam, $searchParam, $branchTime);
$stmt->execute();
$result = $stmt->get_result();

// Fetch all rows as an associative array
$students = [];
if ($result->num_rows > 0) {
    $students = $result->fetch_all(MYSQLI_ASSOC);
}
$counter = 1;
?>

<div class="content-body">
    <div class="container mx-4">
        <h2 class="mt-4">All Students</h2>
        <!-- Search form -->
        <form method="GET" action="">
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Search by Name or ID" name="search" value="<?php echo htmlspecialchars($searchQuery); ?>">
                <button class="btn btn-outline-secondary" type="submit">Search</button>
            </div>
        </form>

        <!-- Bulk Attendance Button -->
        <div class="mb-3">
            <button class="btn btn-primary" id="bulkAttendanceBtn" data-bs-toggle="modal" data-bs-target="#bulkAttendanceModal" disabled>Mark Bulk Attendance</button>
        </div>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th><input type="checkbox" id="selectAll"></th>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Course</th>
                    <th>State</th>
                    <th>City</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($students)): ?>
                    <?php foreach ($students as $row): ?>
                        <tr>
                            <td><input type="checkbox" class="student-checkbox" data-id="<?php echo $row['sid']; ?>" data-name="<?php echo $row['student_name']; ?>"></td>
                            <td><?php echo $counter; ?></td>
                            <td><?php echo $row['student_name']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['phone_number1']; ?></td>
                            <td><?php echo $row['course']; ?></td>
                            <td><?php echo $row['state']; ?></td>
                            <td><?php echo $row['city']; ?></td>
                            <td>
                                <button 
                                    class="btn btn-success open-attendance-modal" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#staticBackdrop" 
                                    data-id="<?php echo $row['sid']; ?>"
                                    data-name="<?php echo $row['student_name']; ?>"
                                >
                                    Attendance
                                </button>
                            </td>
                        </tr>
                        <?php $counter++; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="9">No records found</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Single Attendance Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Update Attendance</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="attendanceForm" method="POST" action="mark_attendance.php">
          <input type="hidden" name="student_id" id="attendanceStudentId" />
          <div class="mb-3">
            <label for="attendanceStudentName" class="form-label">Student Name</label>
            <input type="text" class="form-control" name="student_name" id="attendanceStudentName" readonly />
          </div>
          <div class="mb-3">
            <label for="attendanceDate" class="form-label">Date</label>
            <input type="date" class="form-control" name="attendance_date" id="attendanceDate" required />
          </div>
          <div class="mb-3">
            <label for="attendanceStatus" class="form-label">Status</label>
            <select class="form-control" name="attendance_status" id="attendanceStatus" required>
              <option value="">Choose Attendance</option>
              <option value="present">Present</option>
              <option value="absent">Absent</option>
              <option value="leave">Leave</option>
            </select>
          </div>
          <div class="modal-footer mt-5">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Bulk Attendance Modal -->
<div class="modal fade" id="bulkAttendanceModal" tabindex="-1" aria-labelledby="bulkAttendanceLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="bulkAttendanceLabel">Update Bulk Attendance</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="bulkAttendanceForm" method="POST" action="mark_bulk_attendance.php">
          <input type="hidden" name="student_ids" id="bulkAttendanceStudentIds" />
          <div class="mb-3">
            <label for="bulkAttendanceDate" class="form-label">Date</label>
            <input type="date" class="form-control" name="attendance_date" id="bulkAttendanceDate" required />
          </div>
          <div class="mb-3">
            <label for="bulkAttendanceStatus" class="form-label">Status</label>
            <select class="form-control" name="attendance_status" id="bulkAttendanceStatus" required>
              <option value="">Choose Attendance</option>
              <option value="present">Present</option>
              <option value="absent">Absent</option>
              <option value="leave">Leave</option>
            </select>
          </div>
          <div class="modal-footer mt-5">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Preloader and main wrapper -->
<div id="preloader">
    <div class="lds-ripple">
        <div></div>
        <div></div>
    </div>
</div>
<div id="main-wrapper">
    <?php include("includes/header.php"); ?>
</div>

<!-- Scripts -->
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
    document.addEventListener('DOMContentLoaded', function () {
        var selectAllCheckbox = document.getElementById('selectAll');
        var studentCheckboxes = document.querySelectorAll('.student-checkbox');
        var bulkAttendanceBtn = document.getElementById('bulkAttendanceBtn');

        // Handle "select all" checkbox
        selectAllCheckbox.addEventListener('change', function () {
            studentCheckboxes.forEach(function (checkbox) {
                checkbox.checked = selectAllCheckbox.checked;
            });
            toggleBulkAttendanceButton();
        });

        // Handle individual checkboxes
        studentCheckboxes.forEach(function (checkbox) {
            checkbox.addEventListener('change', function () {
                toggleBulkAttendanceButton();
            });
        });

        // Enable or disable the bulk attendance button based on selection
        function toggleBulkAttendanceButton() {
            var selectedCount = document.querySelectorAll('.student-checkbox:checked').length;
            bulkAttendanceBtn.disabled = selectedCount === 0;
        }

        // Set student IDs in the bulk attendance form
        bulkAttendanceBtn.addEventListener('click', function () {
            var selectedIds = Array.from(document.querySelectorAll('.student-checkbox:checked')).map(function (checkbox) {
                return checkbox.getAttribute('data-id');
            });
            document.getElementById('bulkAttendanceStudentIds').value = selectedIds.join(',');
        });
    });
    
  document.addEventListener('DOMContentLoaded', function () {
    var attendanceButtons = document.querySelectorAll('.open-attendance-modal');
    attendanceButtons.forEach(function (button) {
      button.addEventListener('click', function () {
        var studentId = this.getAttribute('data-id');
        var studentName = this.getAttribute('data-name');
        
        // Update modal inputs
        document.getElementById('attendanceStudentId').value = studentId;
        document.getElementById('attendanceStudentName').value = studentName;
      });
    });
  });
</script>
</body>
</html>
