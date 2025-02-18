<?php 
	include './database/dbh.php';
    $sql = "SELECT * FROM orders WHERE branch <> '' AND status ='Fulfilled'";
    $resul = $conn->query($sql);
    $totalbraches = mysqli_num_rows($resul);
    echo $totalbraches;
?>