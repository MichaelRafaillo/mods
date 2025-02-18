<?php
include './database/dbh.php';

$datetime = new DateTime('now');//nowtomorrow

    $tom = $datetime->format('l');
    //Avoid Sunday
    
        $sql = "SELECT * FROM orders WHERE delivery_date IN (CURDATE()) ORDER BY zone ASC;";
				$result = mysqli_query($conn,$sql);



?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="./css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
	<script src="./js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</head>

<body style="font-size: 25px;">

<div class="text-center mt-5">
	<h1><?php echo $tom.' '.$datetime->format('d/m/Y'); ?></h1>
	<h3>Total Orders: <?php $numrows = mysqli_num_rows($result);echo $numrows; ?></h3>
</div>
<div class="container-fluid col-10 mt-5">
	<table class="table table-striped">
  <thead>
    <tr>

      <th scope="col">Order No.</th>
      <th scope="col">Zone</th>
      <th scope="col">Address</th>
      <th scope="col">Comment</th>
    </tr>
  </thead>
  <tbody>
  	<?php 
  	$x=1;
  	 while($row = $result->fetch_assoc()) { ?>
  	<tr>

  		<td><?php echo $row["order_no"]; ?></td>
  		<td><?php echo $row["zone"]; ?></td>
  		<td style="font-size:15px;"><?php echo $row["address"]; ?></td>
  		<td><?php echo $row["comment"]; ?></td>
  	</tr>
  	<?php $x++; } ?>
  </tbody>
</table>
</div>



</body>
</html>