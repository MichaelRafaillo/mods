<?php 
include './dbh.php';
$q = intval($_GET['q']);
$branch = $_GET['branch'];


$sql2 = "UPDATE orders
SET branch = '$branch'
WHERE order_no = $q";
mysqli_query($conn,$sql2);