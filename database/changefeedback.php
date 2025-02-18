<?php
include './dbh.php';

$id = $_GET['q'];
$feedback = $_GET['feedback'];


if ($feedback == "noanswer") {
	$feedback = "no answer";
}

$sql = "UPDATE feedback SET feedback_status = '$feedback' WHERE order_no = $id";
mysqli_query($conn,$sql);

?>