<?php 
	include './database/dbh.php';


    $sqldate = "SELECT * FROM orders WHERE delivery_date < NOW() - INTERVAL 3 DAY AND NOT branch='' AND status='Fulfilled'";
    $resultdate = $conn->query($sqldate);
    $totaltodayorders = mysqli_num_rows($resultdate);

    echo '<i class="mdi mdi-arrow-up-bold"></i>'.' '.$totaltodayorders;
    

    
?>