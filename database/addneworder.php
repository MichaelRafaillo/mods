<?php
include './dbh.php';

$orderdate = $conn->real_escape_string($_POST['orderdate']);
$zone = $conn->real_escape_string($_POST['zone']);
$cname = $conn->real_escape_string($_POST['cname']);
$phone = $conn->real_escape_string($_POST['phone']);
$address = $conn->real_escape_string($_POST['address']);
$noofitems = $conn->real_escape_string($_POST['noofitems']);



for ($i = 1; $i <= $noofitems; $i++) {
	// code...
}

$sql = 'SELECT order_no FROM `orders` ORDER BY order_no DESC LIMIT 1';
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
	$orderno = $row['order_no'] + 1;
}

if (isset($cname) && isset($phone) && !empty($cname) && !empty($phone)) {

	$insert = "INSERT INTO orders VALUES ('$orderdate','$orderno','New','$cname','$phone','$zone','0000-00-00','$noofitems','','','','$address')";
	$conn->query($insert);

	//insert the feedback row
	$insertfeedback = "INSERT INTO feedback VALUES ('$orderno','','')";
	$conn->query($insertfeedback);

	for ($x = 1; $x <= $noofitems; $x++) {
		$itemname = 'item' . $x;
		$initem = $_POST[$itemname];
		$additem = "UPDATE `parts` SET `taken`='$orderno' WHERE taken = '' AND online_code = '$initem' LIMIT 1;";
		$conn->query($additem);
	}

	header("Location: ./../index.php?alert=success");
} else {
	header("Location: ./../index.php?alert=failed");
}
