<?php 
include './dbh.php';
$q = intval($_GET['q']);
$date = $_GET['date'];
echo $comment;


$sql2 = "UPDATE orders
SET delivery_date = STR_TO_DATE('$date','%Y-%m-%d')
WHERE order_no = $q";
mysqli_query($conn,$sql2);