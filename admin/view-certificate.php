<?php
session_start();
include("../config.php");
if (empty($_SESSION['user_email'])) {
    echo "<script>window.location.href='index.php';</script>";
}
if($_SESSION['user_type']=='1'){
	echo "<script>window.location.href='dashboard.php';</script>";
}
if (isset($_GET['req_id'])) {
    $uid = $_GET['req_id'];
    $check_user_exists = "select *from tb_certifications where cerid='$uid'";
    $check_result = mysqli_query($conn, $check_user_exists);
    if (!mysqli_num_rows($check_result) > 0) {
        echo '<script>window.location.href="students.php"</script>';
    } else {
        $row = mysqli_fetch_array($check_result);
    }
}
$font = realpath('developer.ttf');
$image = imagecreatefromjpeg("format.jpeg");
$color = imagecolorallocate($image, 51, 51, 102);
$date = date('d F, Y');

$name = strtoupper($row['student_name']);
$s_o = strtoupper($row['father']);
$enrollment_no = strtoupper("GPS" . $row['sid']);
$sid = $row['sid'];
// Fetch profile image of student
$fetch_img = "select profile_img from tb_students where sid='$sid'";
$get_img = mysqli_query($conn, $fetch_img);
$img_row = mysqli_fetch_array($get_img);
$profile_img_path = "../img/students/" . $img_row['profile_img'];

// Create an image resource from the profile image
$profile_img_info = getimagesize($profile_img_path);
$profile_img_width = $profile_img_info[0];
$profile_img_height = $profile_img_info[1];

switch ($profile_img_info['mime']) {
    case 'image/jpeg':
        $profile_img = imagecreatefromjpeg($profile_img_path);
        break;
    case 'image/png':
        $profile_img = imagecreatefrompng($profile_img_path);
        break;
    case 'image/gif':
        $profile_img = imagecreatefromgif($profile_img_path);
        break;
        // Add more cases for other image formats if needed
    default:
        // Handle unsupported image formats
        $profile_img = null;
        break;
}

if ($profile_img) {
    // Resize the profile image to fit within specific dimensions
    $profile_img_resized = imagecreatetruecolor(650, 650);
    imagecopyresampled($profile_img_resized, $profile_img, 0, 0, 0, 0, 200, 200, $profile_img_width, $profile_img_height);

    // Merge the profile image with the existing image
    imagecopymerge($image, $profile_img_resized, 1260, 270, 0, 0, 200, 200, 100);
}

$at = strtoupper("");
$validFrom = strtoupper($row['valid_from']);
$validTo = strtoupper($row['valid_to']);
$course = strtoupper($row['course_name']);


// Convert the dates to the desired format
$validFromFormatted = date("M d, Y", strtotime($validFrom));
$validToFormatted = date("M d, Y", strtotime($validTo));

// Combine the formatted dates
$period = $validFromFormatted . " to " . $validToFormatted;

$grade = strtoupper($row['grade']);
$date_of_issue = date('d-m-Y');
$at = strtoupper("");
    $validFrom = strtoupper($row['valid_from']);
    $validTo = strtoupper($row['valid_to']);
    $course = strtoupper($row['course_name']);

    // Convert the dates to the desired format
    $validFromFormatted = date("M d, Y", strtotime($validFrom));
    $validToFormatted = date("M d, Y", strtotime($validTo));

    // Combine the formatted dates
    $period = $validFromFormatted . " to " . $validToFormatted;

    $grade = strtoupper($row['grade']);
    $date_of_issue = date('d-m-Y',strtotime($row['created_on']));
    
    imagettftext($image, 19, 0, 740, 510, $color, $font, $name);
    imagettftext($image, 19, 0, 470, 570, $color, $font, $s_o);
    imagettftext($image, 19, 0, 430, 640, $color, $font, $enrollment_no);
    imagettftext($image, 19, 0, 1224, 640, $color, $font, $course);
    imagettftext($image, 19, 0, 300, 700, $color, $font, $at);
    imagettftext($image, 19, 0, 424, 770, $color, $font, $period);
    imagettftext($image, 19, 0, 1240, 770, $color, $font, $grade);
    imagettftext($image, 19, 0, 1240, 850, $color, $font, $date_of_issue);
    header('Content-type: image/jpeg');

// Output the image
imagejpeg($image);
imagedestroy($image);
