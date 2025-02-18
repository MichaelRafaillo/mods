<?php
include './database/dbh.php';
session_start();
include './database/user.php';

// Check connection
if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
}

if (isset($_POST['start_date'])) {
    $start_date = $_POST['start_date'];
}
if (isset($_POST['end_date'])) {
    $end_date = $_POST['end_date'];
}

if (!isset($_POST['start_date']) || empty($_POST['start_date'])) {
    $start_date = date('Y-m-01', strtotime('last month'));
}

if (!isset($_POST['end_date']) || empty($_POST['end_date'])) {
    $end_date = date('Y-m-t', strtotime('last month'));
}

// Validate input if needed

// Construct the SQL query
$sql = "SELECT * FROM `orders` WHERE `order_date` BETWEEN '$start_date' AND '$end_date'";
$result = mysqli_query($conn, $sql);

$totalFulfilled = "SELECT * FROM `orders` WHERE `confirmation` = 'Confirmed' AND  `order_date` BETWEEN '$start_date' AND '$end_date'";
$totalFulfilled_res = mysqli_query($conn, $totalFulfilled);

$paidFulfilled = "SELECT * FROM `orders` WHERE `confirmation` = 'Confirmed' AND (`status` = 'Cash' OR `status` = 'Visa') AND  `order_date` BETWEEN '$start_date' AND '$end_date'";
$paidFulfilled_res = mysqli_query($conn, $paidFulfilled);

$cancelledOnDelivery = "SELECT * FROM `orders` WHERE `confirmation` = 'Confirmed' AND (`status` = 'Cancelled') AND  `order_date` BETWEEN '$start_date' AND '$end_date'";
$cancelledOnDelivery_res = mysqli_query($conn, $cancelledOnDelivery);

$cancelledOnCall = "SELECT * FROM `orders` WHERE `confirmation` = 'Cancelled'  AND  `order_date` BETWEEN '$start_date' AND '$end_date'";
$cancelledOnCall_res = mysqli_query($conn, $cancelledOnCall);

$deliverToBranchesAll = "SELECT * FROM `orders` WHERE `confirmation` = 'Confirmed' AND `branch` != ''   AND  `order_date` BETWEEN '$start_date' AND '$end_date'";
$deliverToBranchesAll_res = mysqli_query($conn, $deliverToBranchesAll);

$paidOnBranchesAll = "SELECT *  FROM `orders`  WHERE `confirmation` = 'Confirmed'  AND `branch` != ''  AND (`status` = 'Cash' OR `status` = 'Visa')  AND `order_date` BETWEEN '$start_date' AND '$end_date'";
$paidOnBranchesAll_res = mysqli_query($conn, $paidOnBranchesAll);

$cancelledOnBranchesAll = "SELECT * FROM `orders` WHERE `confirmation` = 'Confirmed' AND `branch` != '' AND (`status` = 'Cancelled')    AND  `order_date` BETWEEN '$start_date' AND '$end_date'";
$cancelledOnBranchesAll_res = mysqli_query($conn, $cancelledOnBranchesAll);

$deliverToBranches = "SELECT branch, COUNT(*) as branch_count FROM `orders` WHERE `confirmation` = 'Confirmed' AND `branch` != ''   AND  `order_date` BETWEEN '$start_date' AND '$end_date' GROUP BY branch 
    ORDER BY FIELD(branch, 'City Centre', 'Downtown', 'Citystars' , 'Korba' , 'Midan El-Gamea' ,'House Of Glamour','House Mivida','Geziret El-Arab','El-Mohandseen','Galleria40','Mall of Arabia','Mall of Egypt','Elegant') ";
$deliverToBranches_res = mysqli_query($conn, $deliverToBranches);

$paidOnBranches = "SELECT branch, COUNT(*) as branch_count  FROM `orders`  WHERE `confirmation` = 'Confirmed'  AND `branch` != ''  AND (`status` = 'Cash' OR `status` = 'Visa')  AND `order_date` BETWEEN '$start_date' AND '$end_date' GROUP BY branch";
$paidOnBranches_res = mysqli_query($conn, $paidOnBranches);

$cancelledOnBranches = "SELECT branch, COUNT(*) as branch_count FROM `orders` WHERE `confirmation` = 'Confirmed' AND `branch` != '' AND (`status` = 'Cancelled')    AND  `order_date` BETWEEN '$start_date' AND '$end_date' GROUP BY branch";
$cancelledOnBranches_res = mysqli_query($conn, $cancelledOnBranches);

$DelieverdToHome = "SELECT *  FROM `orders`  WHERE `confirmation` = 'Confirmed'  AND `branch` = ''   AND `order_date` BETWEEN '$start_date' AND '$end_date'";
$DelieverdToHome_res = mysqli_query($conn, $DelieverdToHome);

$PaidAtHome = "SELECT *  FROM `orders`  WHERE `confirmation` = 'Confirmed'  AND `branch` = ''  AND (`status` = 'Cash' OR `status` = 'Visa')  AND `order_date` BETWEEN '$start_date' AND '$end_date'";
$PaidAtHome_res = mysqli_query($conn, $PaidAtHome);

$CancelledAtHome = "SELECT *  FROM `orders`  WHERE `confirmation` = 'Confirmed'  AND `branch` = ''  AND (`status` = 'Cancelled')  AND `order_date` BETWEEN '$start_date' AND '$end_date'";
$CancelledAtHome_res = mysqli_query($conn, $CancelledAtHome);

$confirmedOrdersQuery = "SELECT zone, COUNT(*) as total, 
                         COUNT(CASE WHEN `status` IN ('Cash', 'Visa') THEN 1 END) as total_paid,
                         COUNT(CASE WHEN `status` = 'Cancelled' THEN 1 END) as total_cancelled
                         FROM `orders`
                         WHERE `confirmation` = 'Confirmed'
                         AND `zone` != ''
                         AND `order_date` BETWEEN '$start_date' AND '$end_date'
                         GROUP BY zone
                         ORDER BY total DESC";

$confirmedOrdersResult = mysqli_query($conn, $confirmedOrdersQuery);

$typesQuery = "SELECT 
                CASE 
                    WHEN SUBSTRING(p.online_code, 1, 3) = 'RG0' THEN 'R00'
                    WHEN SUBSTRING(p.online_code, 1, 3) = 'CHG' THEN 'CH0'
                    WHEN SUBSTRING(p.online_code, 1, 3) = 'EG0' THEN 'E00'
                    WHEN SUBSTRING(p.online_code, 1, 3) = 'AG0' THEN 'A00'
                    WHEN SUBSTRING(p.online_code, 1, 3) = 'NG0' THEN 'N00'
                    ELSE SUBSTRING(p.online_code, 1, 3)
                END AS code_prefix,
                COUNT(*) AS count
              FROM parts p
              JOIN orders o ON p.taken = o.order_no
              WHERE o.order_date BETWEEN '$start_date' AND '$end_date'
              GROUP BY code_prefix
              ORDER BY count DESC ";

$typesQuery_res = mysqli_query($conn, $typesQuery);

if (!$result) {
    die('Query failed: ' . mysqli_error($conn));
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Report Query - Glamour ODS</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <script src="./js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="icon" type="image/x-icon" href="./img/favicon.ico">
    <script src="./js/jquery.min.js"></script>
    <script src="./js/e-search.min.js"></script>
    <script src="https://kit.fontawesome.com/a6e251be7b.js" crossorigin="anonymous"></script>


    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <style>
        .print-only {
            display: none !important;
        }

        @media print {
            body {
                color: black !important;
            }

            table {
                color: black !important;
            }

            .print-only {
                display: block !important;
            }

            /* You can add more specific styling if needed */
            /* For example, to target only table cells */
            table td {
                color: black !important;
            }
        }
    </style>
</head>

<body>

    <?php include './includes/navbar.php'; ?>

    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="mt-4 mb-2 d-inline-block">
        <label for="start_date">Start Date:</label>
        <input type="date" name="start_date" class="btn btn-secondary" value="<?php echo $start_date; ?>">
        <label for="end_date">End Date:</label>
        <input type="date" name="end_date" class="btn btn-secondary" value="<?php echo $end_date; ?>">
        <input type="submit" value="Submit" class="btn btn-success">
    </form>


    <div class="mb-2 ml-2 d-inline-block float-right mt-4">
        <button class="btn btn-success float-right" onclick="printDiv('printThis')">Print</button>
    </div>

    <?php
    // Display the results if available
    if (isset($result) && mysqli_num_rows($result) > 0) {

        // Add Export as CSV button using JavaScript
        echo '<div class="mb-2 d-inline-block float-right mt-4"><button onclick="exportToCSV()" class="btn btn-dark">Export Orders as CSV</button>
        <button id="exportCSV" class="btn btn-dark">Export Report as CSV</button>
        </div>';
    ?>




        <div class="container-fliud mt-4" id="printThis">
            <div class="row" style="width: 100%;">
                <div class="col-md-6 mb-5">
                    <div class="print-only text-center mb-5">
                        <!-- Your content to be shown only when printing goes here -->
                        <h1>Online Orders Report</h1>
                        <h4>From: <?php echo $start_date; ?> &nbsp;&nbsp; To: <?php echo $end_date; ?> </h4>
                    </div>

                    <h2>Orders Reports</h2>
                    <table class="table table-bordered text-white">
                        <thead style="position:relative;">
                            <tr>
                                <th>Category</th>
                                <th>Total</th>
                                <th class="p-1">
                                    <p class="m-0">Percentage</p>
                                    <p class="m-0 text-secondary"><small>From total orders</small></p>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center h2">Total Orders</td>
                                <td class="h3 text-center targetCell" colspan="2"><?php echo mysqli_num_rows($result); ?></td>
                            </tr>
                            <tr>
                                <td class="text-center h4">Total Fulfilled</td>
                                <td class="h3 targetCell"><?php echo mysqli_num_rows($totalFulfilled_res); ?></td>
                                <td class="h3">
                                    <?php echo sprintf('%.0f%%', mysqli_num_rows($result) > 0 ? (mysqli_num_rows($totalFulfilled_res) / mysqli_num_rows($result)) * 100 : 0); ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center h4">Total Paid</td>
                                <td class="h3 targetCell"><?php echo mysqli_num_rows($paidFulfilled_res); ?></td>
                                <td class="h3">
                                    <?php echo sprintf('%.0f%%', mysqli_num_rows($result) > 0 ? (mysqli_num_rows($paidFulfilled_res) / mysqli_num_rows($result)) * 100 : 0); ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center h4">Cancelled On Delivery</td>
                                <td class="h3 targetCell"><?php echo mysqli_num_rows($cancelledOnDelivery_res); ?></td>
                                <td class="h3">
                                    <?php echo sprintf('%.0f%%', mysqli_num_rows($result) > 0 ? (mysqli_num_rows($cancelledOnDelivery_res) / mysqli_num_rows($result)) * 100 : 0); ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center h4">Cancelled On Call</td>
                                <td class="h3 targetCell"><?php echo mysqli_num_rows($cancelledOnCall_res); ?></td>
                                <td class="h3 ">
                                    <?php echo sprintf('%.0f%%', mysqli_num_rows($result) > 0 ? (mysqli_num_rows($cancelledOnCall_res) / mysqli_num_rows($result)) * 100 : 0); ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>




                    <!-- Table for the second set of data -->
                    <table class="table table-bordered mt-4  text-white">
                        <thead style="position:relative;">
                            <tr>
                                <th>Category</th>
                                <th class="p-1">
                                    <p class="m-0">Percentage</p>
                                    <p class="m-0 text-secondary"><small>From Fulfilled orders</small></p>
                                </th>
                                <th>Paid</th>
                                <th>Cancelled</th>
                                <th>Total</th>

                            </tr>
                        </thead>
                        <tbody>

                            <tr>
                                <td class="text-center h4">Doorstep</td>

                                <?php
                                echo "
                                                                <td class='h4 " .
                                    (mysqli_num_rows($totalFulfilled_res) > 0 && (mysqli_num_rows($DelieverdToHome_res) / mysqli_num_rows($totalFulfilled_res)) * 100 < 50 ? 'bg-danger' : 'bg-success') .
                                    "'>" .
                                    sprintf('%.0f%%', mysqli_num_rows($totalFulfilled_res) > 0 ? (mysqli_num_rows($DelieverdToHome_res) / mysqli_num_rows($totalFulfilled_res)) * 100 : 0) .
                                    '</td>';

                                ?>

                                <td class="h3 targetCell"><?php echo mysqli_num_rows($PaidAtHome_res); ?></td>
                                <td class="h3 targetCell"><?php echo mysqli_num_rows($CancelledAtHome_res); ?></td>
                                <td class="h3 targetCell"><?php echo mysqli_num_rows($DelieverdToHome_res); ?></td>
                            </tr>


                            <tr>
                                <td class="text-center h4">Branches</td>

                                <?php
                                echo "
                                                                <td class='h4 " .
                                    (mysqli_num_rows($totalFulfilled_res) > 0 && (mysqli_num_rows($deliverToBranchesAll_res) / mysqli_num_rows($totalFulfilled_res)) * 100 < 50 ? 'bg-danger' : 'bg-success') .
                                    "'>" .
                                    sprintf('%.0f%%', mysqli_num_rows($totalFulfilled_res) > 0 ? (mysqli_num_rows($deliverToBranchesAll_res) / mysqli_num_rows($totalFulfilled_res)) * 100 : 0) .
                                    '</td>';

                                ?>

                                <td class="h3 targetCell"><?php echo mysqli_num_rows($paidOnBranchesAll_res); ?></td>
                                <td class="h3 targetCell"><?php echo mysqli_num_rows($cancelledOnBranchesAll_res); ?></td>
                                <td class="h3 targetCell"><?php echo mysqli_num_rows($deliverToBranchesAll_res); ?></td>
                            </tr>


                        </tbody>
                    </table>


                </div>
                <div class="col-md-6 mb-5">
                    <h2>Branch Reports</h2>

                    <table class="table table-bordered text-white">
                        <thead style="position:relative;">
                            <tr>
                                <th>Branch</th>
                                <th>Delivered</th>
                                <th>Paid</th>
                                <th>Cancelled</th>
                                <th>Percentage</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($row = mysqli_fetch_assoc($deliverToBranches_res)) {
                                $branch = $row['branch'];
                                $branch_count_delivered = $row['branch_count'];

                                // Find corresponding values in other result sets
                                $branch_count_paid = 0;
                                $branch_count_cancelled = 0;

                                // Find branch in $paidOnBranches_res
                                while ($paidRow = mysqli_fetch_assoc($paidOnBranches_res)) {
                                    if ($paidRow['branch'] === $branch) {
                                        $branch_count_paid = $paidRow['branch_count'];
                                        break;
                                    }
                                }

                                // Reset pointer for the next loop
                                mysqli_data_seek($paidOnBranches_res, 0);

                                // Find branch in $cancelledOnBranches_res
                                while ($cancelledRow = mysqli_fetch_assoc($cancelledOnBranches_res)) {
                                    if ($cancelledRow['branch'] === $branch) {
                                        $branch_count_cancelled = $cancelledRow['branch_count'];
                                        break;
                                    }
                                }

                                // Reset pointer for the next loop
                                mysqli_data_seek($cancelledOnBranches_res, 0);

                                echo "<tr>
                                                    <td class='h5 '>$branch</td>
                                                    <td class='h4 targetCell'>$branch_count_delivered</td>
                                                    <td class='h4 targetCell'>$branch_count_paid</td>
                                                    <td class='h4 targetCell'>$branch_count_cancelled</td>
                                                    <td class='h4 " .
                                    ($branch_count_delivered > 0 && ($branch_count_paid / $branch_count_delivered) * 100 < 50 ? 'bg-danger' : 'bg-success') .
                                    "'>" .
                                    sprintf('%.0f%%', $branch_count_delivered > 0 ? ($branch_count_paid / $branch_count_delivered) * 100 : 0) .
                                    "</td>
                                                </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <div class="col-md-6 p-0">
                    <h2>Zones Reports</h2>
                    <table class="table table-bordered text-white">
                        <thead style="position:relative;">
                            <tr>
                                <th scope="col">Zone</th>
                                <th scope="col">Total</th>
                                <th scope="col">Total Paid</th>
                                <th scope="col">Total Cancelled</th>
                                <th scope="col" class="p-1">
                                    <p class="m-0">Percentage</p>
                                    <p class="m-0 text-secondary"><small>Paid from Total</small></p>
                                </th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php

                            while ($row = mysqli_fetch_assoc($confirmedOrdersResult)) {
                                echo '<tr >';
                                echo '<td  class="h4 py-1">' . $row['zone'] . '</td>';
                                echo '<td  class="h3 py-1 targetCell">' . $row['total'] . '</td>';
                                echo '<td  class="h3 py-1 targetCell">' . $row['total_paid'] . '</td>';
                                echo '<td  class="h3 py-1 targetCell">' . $row['total_cancelled'] . '</td>';
                                echo "<td class='h4 " .
                                    ($row['total'] > 0 && ($row['total_paid'] / $row['total']) * 100 < 70 ? 'bg-danger' : 'bg-success') .
                                    "'>" .
                                    sprintf('%.0f%%', $row['total'] > 0 ? ($row['total_paid'] / $row['total']) * 100 : 0) .
                                    "</td>";
                                echo '</tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <div class="col-md-4">
                    <?php

                    if ($typesQuery_res->num_rows > 0) {
                    ?>
                        <h2>Ordered Items</h2>
                        <table class="table table-bordered text-white">
                            <thead style="position:relative;">
                                <tr>
                                    <th>Item Type</th>
                                    <th>Count</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while ($row = $typesQuery_res->fetch_assoc()) {
                                ?>
                                    <tr>
                                        <td class="py-1">
                                            <?php

                                            if ($row['code_prefix'] === 'NG0') {
                                                echo 'Gold Necklaces';
                                            }
                                            if ($row['code_prefix'] == 'EG0') {
                                                echo 'Gold Earrings';
                                            }
                                            if ($row['code_prefix'] == 'RG0') {
                                                echo 'Gold Rings';
                                            }
                                            if ($row['code_prefix'] == 'BG0') {
                                                echo 'Gold Bracelets';
                                            }
                                            if ($row['code_prefix'] == 'EGK') {
                                                echo 'Kids Earrings';
                                            }
                                            if ($row['code_prefix'] == 'CHG') {
                                                echo 'Gold Chains';
                                            }
                                            if ($row['code_prefix'] == 'AG0') {
                                                echo 'Gold Anklets';
                                            }
                                            if ($row['code_prefix'] == 'PRG') {
                                                echo 'Gold Piercings';
                                            }
                                            if ($row['code_prefix'] == 'E00') {
                                                echo 'Gold Earrings';
                                            }
                                            if ($row['code_prefix'] == 'BGM') {
                                                echo 'Gold Men Bracelets';
                                            }
                                            if ($row['code_prefix'] == 'BGK') {
                                                echo 'Kids Bracelets';
                                            }
                                            if ($row['code_prefix'] == 'ND0') {
                                                echo 'Diamond Necklaces';
                                            }
                                            if ($row['code_prefix'] == 'A00') {
                                                echo 'Gold Anklets';
                                            }
                                            if ($row['code_prefix'] == 'NGK') {
                                                echo 'Kids Necklaces';
                                            }
                                            if ($row['code_prefix'] == 'N00') {
                                                echo 'Gold Necklaces';
                                            }
                                            if ($row['code_prefix'] == 'CH0') {
                                                echo 'Gold Chains';
                                            }
                                            if ($row['code_prefix'] == 'RD0') {
                                                echo 'Diamond Rings';
                                            }
                                            if ($row['code_prefix'] == 'ED0') {
                                                echo 'Diamond Earrings';
                                            }
                                            if ($row['code_prefix'] == 'PNG') {
                                                echo 'Gold Pendants';
                                            }
                                            if ($row['code_prefix'] == 'BRD') {
                                                echo 'Diamond Bracelets';
                                            }
                                            if ($row['code_prefix'] == 'NGU') {
                                                echo 'Unisex Necklaces';
                                            }
                                            if ($row['code_prefix'] == 'B00') {
                                                echo 'Gold Bracelets';
                                            }
                                            if ($row['code_prefix'] == 'PRD') {
                                                echo 'Diamond Piercings';
                                            }
                                            if ($row['code_prefix'] == 'BG') {
                                                echo 'Gold Brcelets';
                                            }
                                            if ($row['code_prefix'] == 'R00') {
                                                echo 'Gold Rings';
                                            }

                                            ?>


                                        </td>
                                        <td class="py-1  targetCell"><?php echo $row['count']; ?></td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    <?php
                    } else {
                        echo "No results found.";
                    }

                    ?>
                </div>

            </div>
        </div>

    <?php
        //     echo "<table border='1' id='userTable' class='table table-striped table-dark' style='font-size:0.8rem;'>
        //         <tr>
        //             <th>Order ID</th>
        //             <th>Order Date</th>
        //             <th>Confirmation</th>
        //             <th>Delivery Status</th>
        //             <th>Branch</th>
        //             <!-- Add other columns as needed -->
        //         </tr>";

        //     while ($row = mysqli_fetch_assoc($result)) {
        //         echo "<tr>
        //             <td>" . $row["order_no"] . "</td>
        //             <td>" . $row["order_date"] . "</td>
        //             <td>" . $row["confirmation"] . "</td>
        //             <td>" . $row["status"] . "</td>
        //             <td>" . $row["branch"] . "</td>
        //             <!-- Add other columns as needed -->
        //         </tr>";
        //     }

        //     echo "</table>";
    }
    ?>








    <script>
        function exportToCSV() {
            var table = document.querySelector('table');
            var csv = [];

            // Get table headers
            var headers = [];
            for (var i = 0; i < table.rows[0].cells.length; i++) {
                headers.push(table.rows[0].cells[i].innerText);
            }
            csv.push(headers.join(','));

            // Get table data
            for (var i = 1; i < table.rows.length; i++) {
                var row = [];
                for (var j = 0; j < table.rows[i].cells.length; j++) {
                    row.push(table.rows[i].cells[j].innerText);
                }
                csv.push(row.join(','));
            }

            // Create a data URI and trigger download
            var csvData = 'data:text/csv;charset=utf-8,' + encodeURIComponent(csv.join('\n'));
            var link = document.createElement('a');
            link.setAttribute('href', csvData);
            link.setAttribute('download', 'export.csv');
            link.click();
        }
    </script>

    <script>
        document.getElementById('exportCSV').addEventListener('click', function() {
            // Function to convert HTML table to CSV
            function convertToCSV(table) {
                var csv = [];
                var rows = table.querySelectorAll('tr');

                for (var i = 0; i < rows.length; i++) {
                    var row = [],
                        cols = rows[i].querySelectorAll('td, th');

                    for (var j = 0; j < cols.length; j++) {
                        row.push(cols[j].innerText);
                    }

                    csv.push(row.join(','));
                }

                return csv.join('\n');
            }

            // Get the tables
            var tables = document.querySelectorAll('table');

            // Create a blob with the CSV data
            var blob = new Blob([convertToCSV(tables[0]) + '\n\n' + convertToCSV(tables[1]) + '\n\n' + convertToCSV(tables[2])], {
                type: 'text/csv'
            });

            // Create a download link and trigger a click to download the file
            var a = document.createElement('a');
            a.href = window.URL.createObjectURL(blob);
            a.download = 'exported_data.csv';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        });
    </script>

    <script>
        function printDiv(divId) {
            var printContents = document.getElementById(divId).innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;

            window.print();

            document.body.innerHTML = originalContents;
        }
    </script>

    <script>
        // Function to increment number in the td element gradually
        function incrementNumber(targetNumber, tdElement, duration) {
            var steps = targetNumber / duration;
            var currentNumber = 0;

            var intervalId = setInterval(function() {
                tdElement.textContent = Math.round(currentNumber);

                currentNumber += steps;

                if (currentNumber >= targetNumber) {
                    tdElement.textContent = targetNumber;
                    clearInterval(intervalId);
                }
            }, 1); // Adjust the interval duration (in milliseconds) as needed
        }

        // Get all td elements with the class 'targetCell' and call incrementNumber for each
        document.addEventListener('DOMContentLoaded', function() {
            var targetCells = document.querySelectorAll('.targetCell');

            targetCells.forEach(function(td) {
                var targetNumber = parseInt(td.textContent, 10);
                incrementNumber(targetNumber, td, 200); // 2000 milliseconds (2 seconds)
            });
        });
    </script>
</body>

</html>

<?php
// Close the connection
mysqli_close($conn);
?>