<?php
session_start();
include './database/user.php';
include './database/dbh.php';

if (isset($_POST['sku'])) {
	$sku = $_POST['sku'];
	$code = $_POST['code'];
	$weight = '';
	$orderno = $_POST['orderno'];

	$insert = "INSERT INTO parts (`online_code`, `acc_code`, `weight`, `price`, `taken`, `level`) VALUES ('$sku','$code','$weight','','$orderno','')";
	$conn->query($insert);

	header("Location: ./addpart.php?alert=success");
}
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Glamour ODS - Add Item</title>
	<link rel="stylesheet" href="./css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
	<script src="./js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="./css/style.css">
	<link rel="icon" type="image/x-icon" href="./img/favicon.ico">

	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>


</head>

<body>
	<?php include './includes/navbar.php'; ?>
	<div class="d-flex justify-content-center">
		<div class="form-container col-md-5">
			<h1 style="margin-top:30px%;">Add Item to Order</h1>
			<hr class="style1"><br>
			<?php
			if (isset($_GET['alert'])) {
				echo '<div class="alert alert-success" role="alert" id="ppp">';
				if ($_GET['alert'] == 'success') {
					echo 'Item Added Successfuly!';
				}
				echo '</div>';
			} ?>
			<form action="./addpart.php" method="POST">
				<div class="form-row">
					<div class="form-group col-6">
						<label for="inname" id="legend">Online SKU</label>
						<input type="text" class="form-control" id="inname" placeholder="NG0001" name="sku">
					</div>
					<div class="form-group col-6">
						<label for="inname" id="legend">Accounting Code</label>
						<input type="text" class="form-control" id="inname" placeholder="MLP127353" name="code">
					</div>
					<div class="form-group col-12">
						<label for="inname" id="legend">Order Number G#</label>
						<input type="Number" class="form-control" id="inname" placeholder="2674" name="orderno">
					</div>
				</div>
				<br>
				<button type="submit" class="btn btn-primary col-md-12">Submit</button>
			</form>
		</div>
	</div>
</body>

</html>