<?php 
include './dbh.php';
$q = intval($_GET['q']);


$sql2 = "UPDATE orders
SET confirmation = 'No Answer'
WHERE order_no = $q";
mysqli_query($conn,$sql2);

$sql="SELECT * FROM orders WHERE order_no = $q";
$result = mysqli_query($conn,$sql);
while($row = mysqli_fetch_array($result)) {
  echo $row["confirmation"];
}