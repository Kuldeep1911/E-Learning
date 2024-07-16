<?php
session_start();
if (!isset($_SESSION['exam_id'])) {
    echo json_encode(['success' => false, 'message' => 'Exam not started']);
    exit;
}

if (isset($_POST['current_question_index'])) {
    $_SESSION['current_question_index'] = (int)$_POST['current_question_index'];
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
}
?>
