<?php 
	include './database/dbh.php';


    $sqldate = "SELECT * FROM orders WHERE delivery_date IN (CURDATE() + INTERVAL 1 DAY) AND NOT branch =''";
    $resultdate = $conn->query($sqldate);
    $totaltodayorders = mysqli_num_rows($resultdate);

    echo '<i class="mdi mdi-arrow-up-bold"></i>'.' '.$totaltodayorders;
    

    
?>