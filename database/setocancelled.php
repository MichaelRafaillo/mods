<?php 
include './dbh.php';
$q = intval($_GET['q']);


//add cancelled items to cancel table
$sql="SELECT * FROM parts WHERE taken = $q";
$result = mysqli_query($conn,$sql);
while($row = mysqli_fetch_array($result)) {
  $onlinecode = $row["online_code"];
  $sql3="INSERT INTO cancel VALUES ('$q','$onlinecode') ";
  mysqli_query($conn,$sql3);
}

//remove ordered items from parts table
$sql5="UPDATE `parts` SET `taken` = '' WHERE `parts`.`taken` = '$q'";
mysqli_query($conn,$sql5);

//set order cancel on orders table
$sql2 = "UPDATE orders
SET status = 'Cancelled'
WHERE order_no = $q";
mysqli_query($conn,$sql2);