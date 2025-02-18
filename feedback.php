<?php
include './database/dbh.php';
session_start();
include './database/user.php';

if (isset($_GET['fromdate'])) {
	$from = date('Y-m-d', strtotime($_GET['fromdate']));
	$to = date('Y-m-d', strtotime($_GET['todate']));
} else {
	$thismonth = date('m');
	$from = '2023-' . $thismonth . '-01';
	$to = '2023-' . $thismonth . '-30';
}
if (isset($_GET['status'])) {
	$status = $_GET['status'];
	if ($status == 'All') {
		$sql = "SELECT orders.order_date , orders.order_no , orders.phone ,orders.name , orders.items , feedback.feedback_status , feedback.feedback_comment FROM orders INNER JOIN feedback ON orders.order_no = feedback.order_no WHERE orders.order_date BETWEEN '$from' AND '$to';";
		$result = mysqli_query($conn, $sql);
		$sql2 = "SELECT orders.order_date , orders.order_no , orders.phone ,orders.name , orders.items , feedback.feedback_status , feedback.feedback_comment FROM orders INNER JOIN feedback ON orders.order_no = feedback.order_no WHERE orders.order_date BETWEEN '$from' AND '$to';";
		$result2 = mysqli_query($conn, $sql2);
	} else {
		$sql = "SELECT orders.order_date , orders.order_no ,orders.phone , orders.name , orders.items , feedback.feedback_status , feedback.feedback_comment FROM orders INNER JOIN feedback ON orders.order_no = feedback.order_no WHERE feedback.feedback_status = '$status' AND orders.order_date BETWEEN '$from' AND '$to';";
		$result = mysqli_query($conn, $sql);
		$sql2 = "SELECT orders.order_date , orders.order_no, orders.phone , orders.name , orders.items , feedback.feedback_status , feedback.feedback_comment FROM orders INNER JOIN feedback ON orders.order_no = feedback.order_no WHERE feedback.feedback_status = '$status' AND orders.order_date BETWEEN '$from' AND '$to';";
		$result2 = mysqli_query($conn, $sql2);
	}
} else {
	$sql = "SELECT orders.order_date , orders.order_no ,orders.phone , orders.name , orders.items , feedback.feedback_status , feedback.feedback_comment FROM orders INNER JOIN feedback ON orders.order_no = feedback.order_no WHERE orders.order_date BETWEEN '$from' AND '$to';";
	$result = mysqli_query($conn, $sql);
	$sql2 = "SELECT orders.order_date , orders.order_no ,orders.phone , orders.name , orders.items , feedback.feedback_status , feedback.feedback_comment FROM orders INNER JOIN feedback ON orders.order_no = feedback.order_no WHERE orders.order_date BETWEEN '$from' AND '$to';";
	$result2 = mysqli_query($conn, $sql2);
}
?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Feedback</title>
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
	<div id="onlinecheck">
		<p>Offline</p>
	</div>
	<script type="text/javascript" src="./js/script.js"></script>
	<?php include './includes/navbar.php'; ?>

	<form method="GET">
		<div class="container">
			<div class="row">
				<div class="col-12 text-center mb-4">
					<h3>Feedback Report</h3>
				</div><br>
				<label for="inname" id="legend">From</label>
				<div class="col-md-2 col-sm-12">
					<input type="date" name="fromdate" class="form-control" id="inorderdate" placeholder="Order Date" value="<?php echo $from; ?>" onchange="this.form.submit()">
				</div>
				<label for="inname" id="legend">To</label>
				<div class="col-md-2 col-sm-12">
					<input type="date" name="todate" class="form-control" value="<?php echo $to; ?>" format="dd-mm-yyyy" onchange="this.form.submit()">
				</div>
				<label for="inname" id="legend">Status</label>
				<div class="col-md-2 col-sm-12">
					<select id="inputState" class="form-control" name="status" onchange="this.form.submit()">
						<option <?php if (isset($_GET['status'])) {
									if ($_GET['status'] == 'All') {
										echo 'selected';
									}
								} ?>>All</option>
						<option <?php if (isset($_GET['status'])) {
									if ($_GET['status'] == 'positive') {
										echo 'selected';
									}
								} ?>>positive</option>
						<option <?php if (isset($_GET['status'])) {
									if ($_GET['status'] == 'negative') {
										echo 'selected';
									}
								} ?>>negative</option>
						<option <?php if (isset($_GET['status'])) {
									if ($_GET['status'] == 'no answer') {
										echo 'selected';
									}
								} ?>>no answer</option>
					</select>
				</div>
				<div class="col-md-2 col-sm-12">
					<button type='submit' value='Export Excel Sheet' name='Export' class="btn btn-success col-12" form="exported">Export</button>
				</div>

			</div>
		</div>
	</form>

	<div>
		<table class="table table-sm table-dark">
			<thead>
				<tr>
					<th scope="col">Date</th>
					<th scope="col">#</th>
					<th scope="col">Name</th>
					<th scope="col">Items</th>
					<th scope="col">Feedback Status</th>
					<th scope="col">Feedback Comment</th>
				</tr>
			</thead>
			<tbody>
				<?php while ($row = $result->fetch_assoc()) { ?>
					<tr>
						<th scope="row"><?php echo $row['order_date']; ?></th>
						<td style="cursor: pointer;" onclick="location.href='./edit-order.php?id=<?php echo $row['order_no']; ?>'"><?php echo $row['order_no']; ?></td>
						<td>
							<a href="https://api.whatsapp.com/send?phone=2<?php echo $row['phone']; ?>&text=Dear%20<?php echo $row['name']; ?>,%0A%0AWe%20value%20your%20opinion%20and%20would%20love%20to%20hear%20about%20your%20recent%20experience%20with%20us.%20Your%20feedback%20helps%20us%20improve%20our%20services.%20Please%20take%20a%20moment%20to%20share%20your%20thoughts.%0A%0AThank%20you%20for%20being%20a%20valued%20customer.%0A%0ABest%20regards,%0AMarly%20Silver%0A%0Aعزيزي%20<?php echo $row['name']; ?>،%0A%0Aنحن%20نقدر%20رأيك%20ونود%20أن%20نسمع%20عن%20تجربتك%20الأخيرة%20معنا.%20تساعدنا%20ملاحظاتك%20على%20تحسين%20خدماتنا.%20يرجى%20تخصيص%20بعض%20الوقت%20لمشاركة%20أفكارك.%0A%0Aشكرا%20لكونك%20عميلا%20مميزاً .%0A%0Aمع%20أطيب%20التحيات،%20مجوهرات%20جلامور" target="_blank">
								<?php echo $row['name']; ?>
							</a>

							</a>
						</td>
						<td><?php echo $row['items']; ?></td>
						<td><?php echo $row['feedback_status']; ?></td>
						<td style="font-size:12px"><?php echo $row['feedback_comment']; ?></td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>

	<form method='post' action='download-feedback.php' id="exported">

		<?php
		$user_arr = array();
		while ($row2 = mysqli_fetch_array($result2)) {
			$orderdate = $row2['order_date'];
			$orderno = $row2['order_no'];
			$name = $row2['name'];
			$items = $row2['items'];
			$feedbackstatus = $row2['feedback_status'];
			$feedbackcomm = $row2['feedback_comment'];
			$user_arr[] = array($orderdate, $orderno, $name, $items, $feedbackstatus, $feedbackcomm);
		}
		$serialize_user_arr = serialize($user_arr);
		?>
		<textarea name='export_data' style='display: none;'><?php echo $serialize_user_arr; ?></textarea>
	</form>

	<script>
		function copyToClipboard(tdElement) {
			// Create a textarea element to hold the text to be copied
			const textarea = document.createElement('textarea');
			textarea.value = tdElement.innerHTML;

			// Append the textarea to the document
			document.body.appendChild(textarea);

			// Select the text within the textarea
			textarea.select();

			// Copy the selected text to the clipboard
			document.execCommand('copy');

			// Remove the textarea element
			document.body.removeChild(textarea);

			alert('Content copied to clipboard: ' + tdElement.innerHTML);
		}
	</script>
</body>

</html>