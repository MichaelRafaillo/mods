<?php 
	include './database/dbh.php';
    $todaydate = date('Y/m/d');
    $sqldate = "SELECT * FROM orders WHERE delivery_date = '$todaydate'";
    $resultdate = $conn->query($sqldate);
    $totaltodayorders = mysqli_num_rows($resultdate);
    
    $sqldate2 = "SELECT * FROM orders WHERE confirmation = 'Confirmed' AND status = 'Printed'";
    $resultdate2 = $conn->query($sqldate2);
    $totalfulfromtod = mysqli_num_rows($resultdate2);

    if ($totaltodayorders != 0) {
        echo '<i class="mdi mdi-arrow-up-bold"></i>'.number_format(($totalfulfromtod/$totaltodayorders)*100,2).'%';
    }else{
         echo '<i class="mdi mdi-arrow-up-bold"></i>'.'0'.'%';
    }
    
?>