<?php 
include './dbh.php';
$id = $_GET['id'];

//add cancelled items to cancel table
$sql3="SELECT * FROM parts WHERE id = $id";
$result = mysqli_query($conn,$sql3);
while($row = mysqli_fetch_array($result)) {
  $orderno = $row["taken"];
  $onlinecode = $row["online_code"];
}


//remove ordered items from parts table
$sql = "UPDATE `parts` SET `taken`='' WHERE id = $id";
mysqli_query($conn,$sql);

$sql2="INSERT INTO cancel VALUES ('$orderno','$onlinecode') ";
mysqli_query($conn,$sql2);
