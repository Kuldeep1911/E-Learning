<?php
include "../config.php";

if (isset($_GET['question_number'])) {
    $question_number = $_GET['question_number'];
    $query = "DELETE FROM questions WHERE question_number = '$question_number'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $query = "DELETE FROM options WHERE question_number = '$question_number'";
        mysqli_query($conn, $query);
        header("Location: list_questions.php");
    } else {
        die("Failed to delete question.");
    }
}
?>
