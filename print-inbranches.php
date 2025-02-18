<?php
include './database/dbh.php';

$datetime = new DateTime('tomorrow');


$sql = "SELECT * FROM orders WHERE branch <> '' AND status ='Fulfilled'";
$result = mysqli_query($conn,$sql);

$sqldate = "SELECT * FROM orders WHERE delivery_date < NOW() - INTERVAL 1 WEEK AND NOT branch='' AND status='Fulfilled'";
$resultdate = $conn->query($sqldate);
$totaltodayorders = mysqli_num_rows($resultdate);
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="./css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
	<script src="./js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<style type="text/css">
		.table td{
			vertical-align: middle !important;
		}
	</style>
</head>

<body style="font-size: 16px;">

<div class="text-center mt-5">
	<h1>Orders in Branches</h1>
	<h3>Total Orders: <?php $numrows = mysqli_num_rows($result);echo $numrows; ?></h3>
	<h3>More Than a Week: <?php echo $totaltodayorders; ?></h3>
</div>
<div class="container-fluid mt-5">
	<table class="table table-striped">
  <thead>
    <tr>

      <th scope="col">Order No.</th>
      <th scope="col">Customer Name</th>
      <th scope="col">Branch</th>
      <th scope="col">Delivery Date</th>
      <th scope="col">Items</th>
    </tr>
  </thead>
  <tbody>
  	<?php 
  	$x=1;
  	 while($row = $result->fetch_assoc()) { 
  	 	$id = $row["order_no"];
  	 	$sql2 = "SELECT * FROM parts WHERE taken = '$id'";
  	 	$result2 = mysqli_query($conn,$sql2);
  	 	?>
  	<tr>

  		<td><h2><?php echo $row["order_no"]; ?></h2></td>
  		<td><?php echo $row["name"]; ?></td>
  		<td><?php echo $row["branch"]; ?></td>
  		<td><?php echo $row["delivery_date"]; ?></td>
  		<td>
  			<?php while($row2 = $result2->fetch_assoc()) { 
  						echo $row2["online_code"].'   ';
  						echo '<b>'.$row2["acc_code"].'</b>   ';
  						echo $row2["weight"].'<br>';
}
  				?>


  		</td>
  	</tr>
  	<?php $x++; } ?>
  </tbody>
</table>
</div>



</body>
</html>