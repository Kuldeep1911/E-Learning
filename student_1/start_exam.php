<?php
session_start();

if (!isset($_SESSION['exam_id'])) {
    header("Location: login.php");
    exit();
}

header("Location: exam_portal.php");
exit();
?>
