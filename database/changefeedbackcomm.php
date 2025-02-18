<?php
include './dbh.php';

$id = $_GET['q'];
$comment = $conn -> real_escape_string($_GET['comm']);


$sql = "UPDATE feedback SET feedback_comment = '$comment' WHERE order_no = $id";
mysqli_query($conn,$sql);

?>