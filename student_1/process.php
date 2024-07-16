<?php include "../config.php"; ?>
<?php session_start() ; ?>
<?php
// for first question score will not be there
if(!isset($_SESSION['score'])){
    $_SESSION['score']=0;
}
if($_POST){
    // we need total question in process file too
    $query="SELECT * FROM questions ";
    $total_questions=mysqli_num_rows(mysqli_query($conn,$query));

    // we need to capture the question number from where form was submitted
    $number= $_POST['number'];

    // here we are storing the selected option by user
    $selected_choice=$_POST['choice'];

    // what will be the next question number
    $next=$number+1;

    // Determine the correct choice for current question
    $query="SELECT * FROM options WHERE question_number=$number AND is_correct =1";
    $result= mysqli_query($conn,$query);
    $row=mysqli_fetch_assoc($result);

    $correct_choice= $row['id'];

    // increase the score if selected choice is correct
    if($selected_choice==$correct_choice){
        $_SESSION['score']++;
    }

    // redirect to the next question or final score page
    if($number==$total_questions){
        header("LOCATION: result.php");
    }else{
     header("LOCATION: questions.php?n=". $next);   
    }
}
?>

