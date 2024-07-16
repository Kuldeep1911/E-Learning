<?php
include "../config.php";

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $sql = "SELECT * FROM admit_cards WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    
    if(mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo json_encode($row); // Return data as JSON
    } else {
        echo json_encode(["error" => "No record found"]);
    }
} else {
    echo json_encode(["error" => "ID parameter is missing"]);
}
?>
