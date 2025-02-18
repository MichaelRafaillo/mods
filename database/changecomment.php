<?php 
include './dbh.php';
$q = intval($_GET['q']);
$comment = $_GET['comm'];
echo $comment;

$sql2 = "UPDATE orders
SET comment = '$comment'
WHERE order_no = $q";
mysqli_query($conn,$sql2);