<?php 
	include './database/dbh.php';
    $sql = "SELECT * FROM orders WHERE branch <> ''";
    $resul = $conn->query($sql);
    $totalbraches = mysqli_num_rows($resul);
    echo $totalbraches;
?>