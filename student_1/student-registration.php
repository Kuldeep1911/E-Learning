<?php
include("../config.php");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Check if email already exists
    $checkEmailQuery = "SELECT * FROM tb_students WHERE email = ?";
    $stmt = $conn->prepare($checkEmailQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<div class='alert alert-danger'>Error: Email already exists. Please use a different email.</div>";
        exit(); // Stop further execution
    }

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO tb_students (admission_date, student_name, father_name, mother_name, email, phone_number1, phone_number2, dob, address, city, state, profile_img, course, cid, password, branch, branchtime) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssssssssssss", $admission_date, $student_name, $father_name, $mother_name, $email, $phone_number1, $phone_number2, $dob, $address, $city, $state, $profile_img, $course, $cid, $password, $branch, $branchtime);

    // Set parameters and execute
    $admission_date = $_POST['admission_date'];
    $student_name = $_POST['student_name'];
    $father_name = $_POST['father_name'];
    $mother_name = $_POST['mother_name'];
    $phone_number1 = $_POST['phone_number1'];
    $phone_number2 = $_POST['phone_number2'];
    $dob = $_POST['dob'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $state = $_POST['state'];

    // Handle file upload
    $profile_img = "";
    if (isset($_FILES['profile_img']) && $_FILES['profile_img']['error'] == 0) {
        $profile_img = 'uploads/' . basename($_FILES['profile_img']['name']);
        move_uploaded_file($_FILES['profile_img']['tmp_name'], $profile_img);
    }

    $course = $_POST['course'];
    $cid = $_POST['cid'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hashing the password for security
    $branch = $_POST['branch'];
    $branchtime = $_POST['branchtime'];

    // Execute the prepared statement
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>New student registered successfully</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }


}

$branches = $conn->query("SELECT * FROM branches");
$courses = $conn->query("SELECT * FROM tb_courses");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5f5;
        }

        .card {
            border: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .form-control {
            border: none;
            border-bottom: 2px solid #ddd;
            border-radius: 0;
            transition: border-bottom-color 0.3s;
        }

        .form-control:focus {
            box-shadow: none;
            border-bottom-color: #007bff;
        }

        .form-control:hover {
            border-bottom-color: #17a2b8;
        }

        .form-control-file {
            border: none;
            border-bottom: 2px solid #ddd;
            transition: border-bottom-color 0.3s;
        }

        .form-control-file:focus {
            box-shadow: none;
            border-bottom-color: #007bff;
        }

        .form-control-file:hover {
            border-bottom-color: #17a2b8;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            transition: background-color 0.3s, transform 0.3s;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card-header">
                    <h2 class="mb-2  text-capitalize text-center">Student Registration form</h2>
                </div>
                <div class="card p-4">
                    <form action="" method="POST" enctype="multipart/form-data" class="mt-4">

                        <div class="form-group">
                            <label for="admission_date">Admission Date <small class="text-danger">*</small></label>
                            <input type="date" class="form-control" id="admission_date" name="admission_date">
                        </div>
                        <div class="form-group">
                            <label for="student_name">Student Name <small class="text-danger">*</small></label>
                            <input type="text" class="form-control" id="student_name" name="student_name">
                        </div>
                        <div class="form-group">
                            <label for="father_name">Father's Name <small class="text-danger">*</small></label>
                            <input type="text" class="form-control" id="father_name" name="father_name">
                        </div>
                        <div class="form-group">
                            <label for="mother_name">Mother's Name <small class="text-danger">*</small></label>
                            <input type="text" class="form-control" id="mother_name" name="mother_name">
                        </div>
                        <div class="form-group">
                            <label for="email">Email <small class="text-danger">*</small></label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-6">
                                <label for="phone_number1">Phone Number 1 <small class="text-danger">*</small></label>
                                <input type="text" class="form-control" id="phone_number1" name="phone_number1">
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="phone_number2">Phone Number 2</label>
                                <input type="text" class="form-control" id="phone_number2" name="phone_number2">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="dob">Date of Birth <small class="text-danger">*</small></label>
                            <input type="date" class="form-control" id="dob" name="dob" required>
                        </div>
                        <div class="form-group">
                            <label for="address">Address <small class="text-danger">*</small></label>
                            <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-6">
                                <label for="city">City <small class="text-danger">*</small></label>
                                <input type="text" class="form-control" id="city" name="city" required>
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="state">State <small class="text-danger">*</small></label>
                                <input type="text" class="form-control" id="state" name="state" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="profile_img">Profile Image <small class="text-danger">*</small></label>
                            <input type="file" class="form-control-file" id="profile_img" name="profile_img" required>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-6">
                                <label for="course">Course <small class="text-danger">*</small></label>
                                <input type="text" class="form-control" id="course" name="course" required>
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="cid">Course ID <small class="text-danger">*</small></label>
                                <select type="text" class="form-control" id="course" name="cid" required>
                                    <option value="">Select Course </option>
                                    <?php foreach ($courses as $course) : ?>
                                        <option value="<?= $course['id'] ?>"><?= $course['title'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password">Password <small class="text-danger">*</small></label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-6">
                                <label for="branch">Branch <small class="text-danger">*</small></label>
                                <select type="text" class="form-control" id="branch" name="branch" required>
                                    <option value="">Select Branch </option>
                                    <?php foreach ($branches as $branch) : ?>
                                        <option value="<?= $branch['id'] ?>"><?= $branch['branch_name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="branchtime">Branch Time <small class="text-danger">*</small></label>
                                <input type="time" class="form-control" id="branchtime" name="branchtime" step="1" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg mt-2">Register</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
