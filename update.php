<?php
include './database/dbh.php';
	


if (isset($_POST['edit'])) {
	$id = $_POST['id'];
	$query = "SELECT * FROM parts WHERE id = '$id'";
	$result = mysqli_query($conn,$query);
	$row = mysqli_fetch_array($result);

}
if (isset($_POST['delete'])) {
	$id = $_POST['id'];
	$sql = "DELETE FROM parts WHERE id = '$id'";
	mysqli_query($conn,$sql);
	header("Location: ./stock-control.php?delete=success");

}
if (isset($_POST['submit'])) {
	$id = $_POST['id'];
	$sku = $_POST['sku'];
	$code = $_POST['code'];
	$weight = $_POST['weight'];
	$orderno = $_POST['orderno'];
	$query2 = "UPDATE parts SET online_code='$sku', acc_code='$code', weight='$weight', taken='$orderno' WHERE id='$id'";
	mysqli_query($conn,$query2);
	header("Location: ./stock-control.php");
	echo 'im here';
}

?>
<!DOCTYPE html>
<html>
<head>
	<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Glamour ODS - Update Item</title>
	<link rel="stylesheet" href="./css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
	<script src="./js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="./css/style.css">
	<link rel="icon" type="image/x-icon" href="./img/favicon.ico">

	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
   <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

   
</head>
</head>
<body>

	<h1>ID: <strong><?php echo $row['id'] ?></strong></h1>
<form method="POST">
		<div class="form-row">
			<div class="form-group col-md-3">
		      <label for="inname" id="legend">Online SKU</label>
		      <input type="text" class="form-control" id="inname" value="<?php echo $row['online_code'] ?>" name="sku">
		    </div>
		    <div class="form-group col-md-3">
		      <label for="inname" id="legend">Accounting Code</label>
		      <input type="text" class="form-control" id="inname" value="<?php echo $row['acc_code'] ?>" name="code">
		    </div>
		    <div class="form-group col-md-3">
		      <label for="inname" id="legend">Weight</label>
		      <input type="Number" class="form-control" id="inname" value="<?php echo $row['weight'] ?>" name="weight" step="0.01">
		    </div>
		    <div class="form-group col-md-3">
		      <label for="inname" id="legend">Order Number G#</label>
		      <input type="Number" class="form-control" id="inname" value="<?php echo $row['taken'] ?>" name="orderno">
		    </div>
		    <input type="hidden" name="id" value="<?php echo $row['id'] ?>">
		</div>
		<button type="submit" class="btn btn-primary" name="submit">Update</button>
	</form>
</body>
</html>