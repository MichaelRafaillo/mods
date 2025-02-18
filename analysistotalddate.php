<?php
$todaydate = date('Y/m/d');
$sqldate = "SELECT * FROM orders WHERE delivery_date = '$todaydate'";
$resultdate = $conn->query($sqldate);
$totaltodayorders = mysqli_num_rows($resultdate);
echo $totaltodayorders;
?>