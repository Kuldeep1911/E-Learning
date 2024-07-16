<?php
include "../config.php";
session_start();

// Set timezone to IST
date_default_timezone_set('Asia/Kolkata');

$sequence_no = $_POST['sequence_no'] ?? null;
$dob = $_POST['dob'] ?? null;

if (!$sequence_no || !$dob) {
    die("Please provide all login details.");
}

// Fetch admit card details
$sql = "SELECT * FROM admit_cards WHERE sequence_no = ? AND dob = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $sequence_no, $dob);
$stmt->execute();
$result = $stmt->get_result();
$admitCard = $result->fetch_assoc();

if (!$admitCard) {
    die("Invalid credentials.");
}

// Fetch exam details
$exam_id = $admitCard['exam_id'];
$sql = "SELECT * FROM exams WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $exam_id);
$stmt->execute();
$result = $stmt->get_result();
$exam = $result->fetch_assoc();

if (!$exam) {
    die("Exam not found.");
}

// Check Exam Timing
$current_datetime = new DateTime();
$exam_date = new DateTime($exam['exam_date']);
$start_datetime = new DateTime($exam_date->format('Y-m-d') . ' ' . $exam['start_time']);
$end_datetime = new DateTime($exam_date->format('Y-m-d') . ' ' . $exam['end_time']);

// Debugging output (can be removed in production)
echo "Current: " . $current_datetime->format('Y-m-d H:i:s') . "<br>";
echo "Start: " . $start_datetime->format('Y-m-d H:i:s') . "<br>";
echo "End: " . $end_datetime->format('Y-m-d H:i:s') . "<br>";

if ($current_datetime < $start_datetime || $current_datetime > $end_datetime) {
    die("The exam can only be taken between " . $start_datetime->format('Y-m-d H:i:s') . " and " . $end_datetime->format('Y-m-d H:i:s'));
}

// Redirect to disclaimer
$_SESSION['exam_id'] = $exam_id;
$_SESSION['admit_card_id'] = $admitCard['id'];

header("Location: disclaimer.php");
exit();
?>
