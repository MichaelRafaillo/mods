<?php 
	include './database/dbh.php';

    $datetime = new DateTime('tomorrow');
    $tom = $datetime->format('l');
    //Avoid Sunday
    if ($tom == 'Friday') {
        $sqldate = "SELECT * FROM orders WHERE delivery_date IN (CURDATE() + INTERVAL 2 DAY) AND `confirmation` = 'Confirmed'";
        $resultdate = $conn->query($sqldate);
        $totaltodayorders = mysqli_num_rows($resultdate);
    }else{
        $sqldate = "SELECT * FROM orders WHERE delivery_date IN (CURDATE() + INTERVAL 1 DAY) AND `confirmation` = 'Confirmed'";
        $resultdate = $conn->query($sqldate);
        $totaltodayorders = mysqli_num_rows($resultdate);
    }



    echo '<i class="mdi mdi-arrow-up-bold"></i>'.' '.$totaltodayorders;
    

    
?>