<?php 
include './dbh.php';
$q = intval($_GET['q']);


$sql2 = "UPDATE orders
SET status = 'Printed'
WHERE order_no = $q";
mysqli_query($conn,$sql2);