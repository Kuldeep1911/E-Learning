<?php
$env =0;
if($env=="0"){
    $conn = mysqli_connect("localhost","root",'',"db_gravity") or die("Connection Failed".mysqli_connect_error());
}
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}