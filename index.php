<?php
include './database/dbh.php';
session_start();
include './database/user.php';



if (isset($_POST["selecteddate"])) {
    $_SESSION["smonth"] = $_POST["selecteddate"];
    if (date('d') == 31) {
        $date = strtotime("-5 day", strtotime($_SESSION["smonth"]));
    }
}

if (!isset($_SESSION["smonth"])) {
    $_SESSION["smonth"]  = date("Y-m");
    if (date('d') == 31) {
        $date = strtotime("-5 day", strtotime($_SESSION["smonth"]));
    }
}

if (isset($date)) {
    $selectedmonth = date('Y/m/d', $date);
} else {
    $selectedmonth = date('Y/m/d', strtotime($_SESSION["smonth"]));
}




// Filter From Menu
if (isset($_GET['confirmation'])) {
    $confirmation = $_GET['confirmation'];

    if ($confirmation == 'On Hold') {
        $sql = "SELECT * FROM orders WHERE (confirmation = 'On Hold' OR confirmation = 'No Answer') AND YEAR(order_date) = YEAR('$selectedmonth') AND MONTH(order_date) = MONTH('$selectedmonth') ORDER BY order_no DESC";
    } elseif ($confirmation == 'cashvisa') {
        $sql = "SELECT * FROM orders WHERE (status = 'cash' OR status = 'visa') AND YEAR(order_date) = YEAR('$selectedmonth') AND MONTH(order_date) = MONTH('$selectedmonth') ORDER BY order_no DESC";
    } elseif ($confirmation == 'New' || $confirmation == 'Confirmed' || $confirmation == 'Cancelled on confirmation') {
        if ($confirmation == 'Cancelled on confirmation') {
            $confirmation = 'Cancelled';
        }
        $sql = "SELECT * FROM orders WHERE confirmation = '$confirmation' AND YEAR(order_date) = YEAR('$selectedmonth') AND MONTH(order_date) = MONTH('$selectedmonth') ORDER BY order_no DESC";
    } elseif ($confirmation == 'Printed' || $confirmation == 'Fulfilled' || $confirmation == 'Cancelled on delivery' || $confirmation == 'Printed') {
        if ($confirmation == 'Cancelled on delivery') {
            $confirmation = 'Cancelled';
        }
        $sql = "SELECT * FROM orders WHERE status = '$confirmation' AND YEAR(order_date) = YEAR('$selectedmonth') AND MONTH(order_date) = MONTH('$selectedmonth') ORDER BY order_no DESC";
    } elseif ($confirmation == 'Not Printed') {
        $sql = "SELECT * FROM orders WHERE confirmation = 'Confirmed' AND status = '' AND YEAR(order_date) = YEAR('$selectedmonth') AND MONTH(order_date) = MONTH('$selectedmonth') ORDER BY order_no DESC";
    } elseif ($confirmation == 'All Cancelled') {
        $sql = "SELECT * FROM orders WHERE (confirmation = 'Cancelled' OR status = 'Cancelled') AND YEAR(order_date) = YEAR('$selectedmonth') AND MONTH(order_date) = MONTH('$selectedmonth') ORDER BY order_no DESC";
    } else {
        $sql = "SELECT * FROM orders WHERE YEAR(order_date) = YEAR('$selectedmonth') AND MONTH(order_date) = MONTH('$selectedmonth') ORDER BY order_no DESC";
    }
} else {
    $sql = "SELECT * FROM orders WHERE YEAR(order_date) = YEAR('$selectedmonth') AND MONTH(order_date) = MONTH('$selectedmonth') ORDER BY order_no DESC";
}


// filtering from analysis
if (isset($_GET['filter'])) {
    if ($_GET['filter'] == 'inbranches') {
        $sql = "SELECT * FROM orders WHERE branch <> '' AND status = 'Fulfilled' ORDER BY order_no DESC";
    }
    if ($_GET['filter'] == 'packaging') {
        $datetimetom = new DateTime('tomorrow');
        $tom = $datetimetom->format('l');
        if ($tom == 'Friday') {
            $sql = "SELECT * FROM orders WHERE delivery_date IN (CURDATE() + INTERVAL 2 DAY) ORDER BY order_no DESC";
        } else {
            $sql = "SELECT * FROM orders WHERE delivery_date IN (CURDATE() + INTERVAL 1 DAY) ORDER BY order_no DESC";
        }
    }
    if ($_GET['filter'] == 'indelivery') {
        $todaydate = date('Y/m/d');
        $sql = "SELECT * FROM orders WHERE delivery_date = '$todaydate' ORDER BY order_no DESC";
    }
} // End filtering from analysis


$result = $conn->query($sql);


$todaydate = date('Y/m/d');
$sqldate = "SELECT * FROM orders WHERE delivery_date = '$todaydate'";
$resultdate = $conn->query($sqldate);
$totaltodayorders = mysqli_num_rows($resultdate);

$sqldate2 = "SELECT * FROM orders WHERE delivery_date = '$todaydate' AND status = 'Fulfilled'";
$resultdate2 = $conn->query($sqldate2);
$totalfulfromtod = mysqli_num_rows($resultdate2);

$sqlbranch = "SELECT * FROM orders WHERE branch != ''";
$resultbranches = $conn->query($sqlbranch);
$totalbranches = mysqli_num_rows($resultbranches);





?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Marly Silver - ODS</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <!--<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">-->
    <link rel="stylesheet" href="https://unpkg.com/bootstrap-icons@1.8.1/font/bootstrap-icons.css">

    <script src="./js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="icon" type="image/x-icon" href="./img/favicon.ico">
    <script src="./js/jquery.min.js"></script>
    <script src="./js/e-search.min.js"></script>
    <script src="https://kit.fontawesome.com/a6e251be7b.js" crossorigin="anonymous"></script>

   <!-- Open Graph meta tags -->
   <meta property="og:title" content="Marly Silver - ODS">
    <meta property="og:description" content="Delivery Management System for Marly Silver">
    <meta property="og:image" content="./img/logo.png">
    <meta property="og:url" content="http://ods.marlysilver.com/">
    <meta property="og:type" content="website">
    
    <!-- Twitter Card (Optional) -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:image" content="./img/logo.png">

</head>

<body>
    <script type="text/javascript" src="./js/script.js"></script>
    <?php include './includes/navbar.php'; ?>
    <div id="onlinecheck">
        <p>Offline</p>
    </div>
    <div class="addneworder" id="addneworder">
        <form action="./database/addneworder.php" method="POST">
            <i class="bi bi-x-lg closeaddnew" id="closebtn" onclick="closebtn()"></i>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="inorderdate">Order Date</label>
                    <input type="date" name="orderdate" class="form-control" id="inorderdate" placeholder="Order Date" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="inputState">Zone</label>
                    <select id="inputState" class="form-control" name="zone">
                        <option selected>Choose...</option>
                        <option>Alexandria</option>
                        <option>Aswan</option>
                        <option>Asyut</option>
                        <option>Beheira</option>
                        <option>Beni Suef</option>
                        <option>Cairo</option>
                        <option>Dakahlia</option>
                        <option>Damietta</option>
                        <option>Faiyum</option>
                        <option>Gharbia</option>
                        <option>Giza</option>
                        <option>Ismailia</option>
                        <option>Kafr El Sheikh</option>
                        <option>Luxor</option>
                        <option>Matruh</option>
                        <option>Minya</option>
                        <option>Monufia</option>
                        <option>Port Said</option>
                        <option>Qalyubia</option>
                        <option>Qena</option>
                        <option>Red Sea</option>
                        <option>Sharqia</option>
                        <option>Sohag</option>
                        <option>South Sinai</option>
                        <option>Suez</option>
                    </select>

                </div>
                <div class="form-group col-md-6">
                    <label for="inname" id="legend">Name</label>
                    <input type="text" class="form-control" id="inname" placeholder="Customer Name" name="cname">
                </div>
                <div class="form-group col-md-6">
                    <label for="inputAddress2">Phone</label>
                    <input class="form-control" id="inphone" type="number" placeholder="0123...." name="phone">
                </div>
                <div class="form-group col-md-12">
                    <label for="inaddress">Address</label>
                    <input type="text" class="form-control" id="inaddress" placeholder="1234 Main St" name="address">
                </div>


                <legend>
                    Add Items
                    <button id="addRow" type="button" class="btn btn-info" onclick="addnewinput()">+</button>
                    <button id="" type="button" class="btn btn-danger" onclick="removeinput()">-</button>
                </legend>

                <input type="hidden" id="noofitems" name="noofitems" value="1">
                <div class="form-group col-md-6" id="inputgroupss">
                    <input type="text" name="item1" class="form-control hey99" id="hey99" placeholder="1" required>
                </div>


            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>


    <div class="container-fluid searchcontainer">
        <div class="row">
            <div class="col-md-4 col-sm-8">
                <input class="inputsearch" type="text" id="myInput" onkeyup="mysearchFunction()" placeholder="Search for names.." title="Type in a name">
            </div>
            <div class="col-md-2 col-sm-4">
                <select class="inputsearch" id="filtersearchbar">
                    <option value="order" Selected>Order No.</option>
                    <option value="name">Name</option>
                    <option value="phone">Phone</option>
                </select>
            </div>
            <div class="col-md-2 col-sm-12">
                <button type="button" class="btn btn-success col-12" onclick="openbtn()"><i class="bi bi-plus-lg"></i> Create New Order</button>
            </div>
            <form action="./index.php" method="POST" class="col-md-2 col-sm-6">
                <input type="month" onchange="this.form.submit()" name="selecteddate" class="inputsearch" value="<?php echo $_SESSION["smonth"]; ?>">

            </form>
            <!-- Filter Dropdown Menu -->
            <form action="./index.php" method="GET" class="col-md-2 col-sm-6">
                <select name="confirmation" class="inputsearch" onchange="this.form.submit()">
                    <option value="All">All</option>
                    <option value="New" <?php if (isset($_GET['confirmation'])) {
                                            if ($_GET['confirmation'] == "New") {
                                                echo 'selected';
                                            }
                                        } ?>>New</option>
                    <option value="Confirmed" <?php if (isset($_GET['confirmation'])) {
                                                    if ($_GET['confirmation'] == "Confirmed") {
                                                        echo 'selected';
                                                    }
                                                } ?>>Confirmed</option>
                    <option value="On Hold" <?php if (isset($_GET['confirmation'])) {
                                                if ($_GET['confirmation'] == "On Hold") {
                                                    echo 'selected';
                                                }
                                            } ?>>On Hold / No Answer</option>
                    <option value="Cancelled on confirmation" <?php if (isset($_GET['confirmation'])) {
                                                                    if ($_GET['confirmation'] == "Cancelled on confirmation") {
                                                                        echo 'selected';
                                                                    }
                                                                } ?>>Cancelled on call</option>
                    <option value="Not Printed" <?php if (isset($_GET['confirmation'])) {
                                                    if ($_GET['confirmation'] == "Not Printed") {
                                                        echo 'selected';
                                                    }
                                                } ?>>Not Printed</option>
                    <option value="Printed" <?php if (isset($_GET['confirmation'])) {
                                                if ($_GET['confirmation'] == "Printed") {
                                                    echo 'selected';
                                                }
                                            } ?>>Printed</option>
                    <option value="Fulfilled" <?php if (isset($_GET['confirmation'])) {
                                                    if ($_GET['confirmation'] == "fulfilled") {
                                                        echo 'selected';
                                                    }
                                                } ?>>Fulfilled</option>
                    <option value="cashvisa" <?php if (isset($_GET['confirmation'])) {
                                                    if ($_GET['confirmation'] == "cashvisa") {
                                                        echo 'selected';
                                                    }
                                                } ?>>Cash/ Visa</option>
                    <option value="Cancelled on delivery" <?php if (isset($_GET['confirmation'])) {
                                                                if ($_GET['confirmation'] == "Cancelled on delivery") {
                                                                    echo 'selected';
                                                                }
                                                            } ?>>Cancelled on delivery</option>
                    <option value="All Cancelled" <?php if (isset($_GET['confirmation'])) {
                                                        if ($_GET['confirmation'] == "All Cancelled") {
                                                            echo 'selected';
                                                        }
                                                    } ?>>All Cancelled</option>
                </select>
            </form>
            <!-- End Filter Dropdown Menu -->
        </div>
    </div>


    <?php
    if (isset($_GET['alert'])) {
        // Check the value of the 'alert' GET parameter
        switch ($_GET['alert']) {
            case 'success':
                echo '<div class="alert alert-success" role="alert" id="ppp">';
                echo 'Order Added Successfully!';
                break;
            case 'failed':
                echo '<div class="alert alert-danger" role="alert" id="ppp">';
                echo 'Failed to Add Order!';
                break;
            default:
                // If the 'alert' parameter value is unknown, no alert should be displayed
                break;
        }
        echo '</div>'; // Close the alert div here, to avoid repeating this in each case
    }
    ?>
    <div>






        <div class="container-fluid" id="analysiscontainer">
            <div class="row" style="width:100%;">
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="card-body">
                        <div class="float-end analysisicons">
                            <a href="index.php?filter=indelivery"><i class="fa-solid fa-dolly"></i></a>
                        </div>
                        <h6 class="text-muted fw-normal mt-0" title="Number of Customers">Orders Deliver Today</h6>
                        <h4 class="mt-1 mb-1" id="todaydorders"><?php include './analysistotalddate.php' ?></h4>
                        <p class="mb-0 text-muted">
                        <div class="float-end analysisicons" style="font-size:15px;background-color:transparent;cursor:pointer;" onclick="printExternal('./print-today.php')">
                            <i class="bi bi-printer-fill"></i>
                        </div>
                        <span class="text-success me-2" id="fulfilledfromtoday"><?php include './fulfilledfromtoday.php' ?></span>
                        <span class="text-nowrap">Fulfilled</span>
                        </p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="card-body">
                        <div class="float-end analysisicons">
                            <i class="fa-solid fa-earth-africa"></i>
                        </div>
                        <h6 class="text-muted fw-normal mt-0" title="Number of Customers">Today Zone</h6>
                        <h4 class="mt-1 mb-1"><?php include './zones.php'; ?></h4>
                        <p class="mb-0 text-muted">
                        <div class="float-end analysisicons" style="font-size:15px;background-color:transparent;cursor:pointer;" onclick="printExternal('./print-curior.php')">
                            <i class="bi bi-printer-fill"></i>
                        </div>
                        <span class="text-success me-2"><?php include './todfulfromall.php' ?></span>
                        <span class="text-nowrap">From all Orders</span>
                        </p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="card-body">
                        <div class="float-end analysisicons">
                            <a href="index.php?filter=inbranches"><i class="fa-solid fa-store"></i></a>
                        </div>
                        <h6 class="text-muted fw-normal mt-0" title="Number of Customers">Orders in Branch</h6>
                        <h4 class="mt-1 mb-1" id="totalinbranches"><?php include './analysistotalinbranches.php' ?></h4>
                        <p class="mb-0 text-muted">
                        <div class="float-end analysisicons" style="font-size:15px;background-color:transparent;cursor:pointer;" onclick="printExternal('./print-inbranches.php')">
                            <i class="bi bi-printer-fill"></i>
                        </div>
                        <span class="text-success me-2" id="lateonbranch"><?php include './lateonbranch.php' ?></span>
                        <span class="text-nowrap">More Than 3-Days</span>
                        </p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="card-body">
                        <div class="float-end analysisicons">
                            <a href="index.php?filter=packaging"><i class="fa-solid fa-bag-shopping"></i></a>
                        </div>
                        <h6 class="text-muted fw-normal mt-0" title="Number of Customers">Pack for Tomorrow</h6>
                        <h4 class="mt-1 mb-1" id="pactomorrow"><?php include './packtom.php' ?></h4>
                        <p class="mb-0 text-muted">
                        <div class="float-end analysisicons" style="font-size:15px;background-color:transparent;cursor:pointer;" onclick="printExternal('./print-pack.php')">
                            <i class="bi bi-printer-fill"></i>
                        </div>
                        <span class="text-success me-2" id="tomtobranch"><?php include './tomtobranch.php' ?></span>
                        <span class="text-nowrap">Deliver to Branches</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <table class="table table-bordered table-dark" id="myTable">
                <thead>
                    <tr>
                        <th scope="col">Date</th>
                        <th scope="col">Order No.</th>
                        <th scope="col">Confirmation</th>
                        <th scope="col">Name</th>
                        <th scope="col"><i class="bi bi-telephone-fill"></i> Phone</th>
                        <th scope="col"><i class="bi bi-geo-alt-fill"></i> Zone</th>
                        <th scope="col">Delivery Date</th>
                        <th scope="col">Items</th>
                        <th scope="col">Status</th>
                        <th scope="col">Comment</th>
                    </tr>
                </thead>
                <tbody id="tbody row containerItems">
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr name="datarow" class="rowconfirmed" data-search="<?php echo $row["order_no"]; ?>" data-filter="<?php echo $row["order_no"]; ?>">


                            <th scope="row">
                                <p class="order-date">
                                    <?php
                                    $datetime = DateTime::createFromFormat('Y-m-d', $row["order_date"]);
                                    echo $datetime->format('l');
                                    echo "<br>" . $row["order_date"]; ?>

                                </p><a href="<?php echo './edit-order.php?id=' . $row["order_no"]; ?>" style="font-size:20px;color:#bdbdbd;"><i class="bi bi-pencil-square"></i></a>
                            </th>

                            <td>
                                <p class="order-number">#G<?php echo $row["order_no"]; ?></p>
                            </td>

                            <td>

                                <?php
                                if ($row["confirmation"] == 'Confirmed') {
                                    echo '<p class="order-status" style="color:greenyellow;" id="confirmation' . $row["order_no"] . '"><i class="bi bi-check-lg"></i>' . ' ' . $row["confirmation"] . '</p>';
                                }
                                if ($row["confirmation"] == 'No Answer') {
                                    echo '<p class="order-status" style="color:yellow;" id="confirmation' . $row["order_no"] . '"><i class="bi bi-telephone-x-fill"></i>' . ' ' . $row["confirmation"] . '</p>';
                                }
                                if ($row["confirmation"] == 'On Hold') {
                                    echo '<p class="order-status" style="color:orange;" id="confirmation' . $row["order_no"] . '"><i class="bi bi-phone-vibrate-fill"></i>' . ' ' . $row["confirmation"] . '</p>';
                                }
                                if ($row["confirmation"] == 'Cancelled') {
                                    echo '<p class="order-status" style="color:red;" id="confirmation' . $row["order_no"] . '"><i class="bi bi-x-circle-fill"></i>' . ' ' . $row["confirmation"] . '</p>';
                                }
                                if ($row["confirmation"] == 'New') {
                                    echo '<p class="order-status" style="color:cyan;" id="confirmation' . $row["order_no"] . '">' . ' ' . $row["confirmation"] . '</p>';
                                }
                                ?>



                                <span id="confirmationbtns<?php echo $row["order_no"]; ?>">
                                    <button title="Confirmed" class="fulfil-btn" onclick="setconfirmed(<?php echo $row["order_no"]; ?>)">
                                        <i class="bi bi-check-lg"></i>
                                    </button>

                                    <button title="No Answer" class="fulfil-btn" onclick="setnoanswer(<?php echo $row["order_no"]; ?>)">
                                        <i class="bi bi-telephone-x-fill"></i>
                                    </button>

                                    <button title="On Hold" class="fulfil-btn" onclick="setonhold(<?php echo $row["order_no"]; ?>)">
                                        <i class="bi bi-phone-vibrate-fill"></i>
                                    </button>

                                    <button title="Cancel" class="fulfil-btn" onclick="setcancelled(<?php echo $row["order_no"]; ?>)">
                                        <i class="bi bi-x-circle-fill"></i>
                                    </button>
                                </span>



                            </td>

                            <td>

                                <?php
                                $customerName = $row["name"];
                                $customerOrderNumber = '#G' . $row["order_no"];
                                $customerContactNumber =  '2' . $row["phone"];
                                $customerAddress = $row["zone"];
                                $deliveryDateTimestamp = strtotime($row["delivery_date"]);
                                $formattedDeliveryDate = date("l d/m/Y", $deliveryDateTimestamp);

                                ?>





                                <p class="order-name d-inline-block" title="<?php echo $row["address"]; ?>">
                                    <?php echo $row["name"]; ?>
                                </p>

                                <a target="_blank" class="d-inline-block float-right" title="Confirmation Message" href="https://api.whatsapp.com/send?phone=<?php echo $customerContactNumber; ?>&text=<?php echo rawurlencode("
عزيزي/عزيزتي *$customerName*,

كما تم الاتفاق خلال محادثتنا، نرغب في تأكيد تاريخ التوصيل *$formattedDeliveryDate*.

نقدر تفهمكم.

شكرًا لكم،
مجوهرات جلامور

Dear *$customerName*,

As per our Agreement through the call, we want to confirm that the order will be deliverd on *$formattedDeliveryDate*

We appreciate your understanding.

Thank you,
Marly Silver
"); ?>"><i class="bi bi-whatsapp"></i></a>

                                <a target="_blank" class="d-inline-block float-right mr-2" title="Reschedule Message" href="https://api.whatsapp.com/send?phone=<?php echo $customerContactNumber; ?>&text=<?php echo rawurlencode("
عزيزي/عزيزتي *$customerName*,

كما تم الاتفاق خلال محادثتنا، نرغب في إبلاغكم بأن تاريخ توصيل الطلب تم تأجيله إلى *$formattedDeliveryDate*. 

نقدر تفهمكم.

شكرًا لكم،
مجوهرات جلامور

Dear *$customerName*,

As per our Agreement through the call, we wanted to inform you that the order delivery date has been rescheduled to *$formattedDeliveryDate*. 

We appreciate your understanding.

Thank you,
Marly Silver
            "); ?>"><i class="bi bi-arrow-clockwise"></i></a>

                                <a target="_blank" class="d-inline-block float-right mr-2" title="deliver to branch Message" href="https://api.whatsapp.com/send?phone=<?php echo $customerContactNumber; ?>&text=<?php echo rawurlencode("
عزيزي / عزيزتي  *$customerName*،

يسعدنا أن نؤكد أنه تم توصيل الاوردر لفرع (اسم الفرع) بنجاح. 
ونود تاكيد ان الاوردر يتم ابقائه ف الفرع لمده يومان فقط من تاريخ توصيله ومن بعدها يتم ارجاعه للاداره و الغاءه تلقائيا لذلك يجب التوجه للفرع ف اسرع وقت 

شكراً لكم
مجوهرات جلامور


Dear *$customerName*,

We are pleased to confirm that your order has been successfully delivered to our branch in (Branch name).
We would like to ensure that the order will be kept at the branch for ONLY TWO DAYS from the delivery date.

After this period, it will be returned to the management and automatically canceled. 

Please visit the branch as soon as possible!

Thank you
Marly Silver
            "); ?>"><i class="bi bi-shop-window"></i></a>

                            </td>

                            <td>
                                <p class="phone"><?php echo $row["phone"]; ?></p>
                            </td>

                            <td>
                                <p class="address"><?php echo $row["zone"]; ?></p>
                            </td>

                            <td>
                                <p class="deliverydate"></p>
                                <input type="date" name="orderdate" class="form-control ddate" id="theddate<?php echo $row["order_no"]; ?>" onchange="changeddate(<?php echo $row["order_no"]; ?>)" placeholder="Order Date" value="<?php echo $row["delivery_date"]; ?>" format="dd-mm-yyyy">



                                <p class="order-date text-danger">
                                    <?php
                                    $deliveryDate = strtotime($row["delivery_date"]); // Assuming delivery_date is a valid date string

                                    // Check if branch is not empty and delivery date has passed 3 days
                                    if (!empty($row["branch"]) && (time() - $deliveryDate) > (3 * 24 * 60 * 60) && ($row["status"] === 'Fulfilled')) {
                                        // If both conditions are met, display the date in the <td>
                                        echo 'Late in Branch';
                                    }
                                    ?>
                                </p>



                            </td>

                            <td>
                                <div class="item-number popup">
                                    <span class="popuptext" id="myPopup<?php echo $row["order_no"]; ?>" name="itemslist">
                                        <!-- the popup content -->
                                    </span>
                                </div>
                                <p class="item-number" onclick="myFunction(<?php echo $row["order_no"]; ?>)">
                                    <?php echo $row["items"]; ?>
                                </p>
                            </td>

                            <td>

                                <?php
                                if ($row["status"] == 'Printed') {
                                    echo '<p class="order-status" style="color:yellow;" id="ostatus' . $row["order_no"] . '"><i class="bi bi-printer-fill"></i>' . ' ' . $row["status"] . '</p>';
                                };
                                if ($row["status"] == 'Fulfilled') {
                                    echo '<p class="order-status" style="color:yellow;" id="ostatus' . $row["order_no"] . '"><i class="bi bi-truck"></i>' . ' ' . $row["status"] . '</p>';
                                };
                                if ($row["status"] == 'Cash') {
                                    echo '<p class="order-status" style="color:greenyellow;" id="ostatus' . $row["order_no"] . '"><i class="bi bi-cash-coin"></i>' . ' ' . $row["status"] . '</p>';
                                };
                                if ($row["status"] == 'Visa') {
                                    echo '<p class="order-status" style="color:greenyellow;" id="ostatus' . $row["order_no"] . '"><i class="bi bi-credit-card-2-front-fill"></i>' . ' ' . $row["status"] . '</p>';
                                };
                                if ($row["status"] == 'Cancelled') {
                                    echo '<p class="order-status" style="color:red;" id="ostatus' . $row["order_no"] . '"><i class="bi bi-x-circle-fill"></i>' . ' ' . $row["status"] . '</p>';
                                };
                                if ($row["status"] == '') {
                                    echo '<p class="order-status" style="color:red;" id="ostatus' . $row["order_no"] . '">' . ' ' . $row["status"] . '</p>';
                                }
                                ?>


                                <select class="form-select zonesselect" aria-label="Default select example" id="thebranch<?php echo $row["order_no"]; ?>" onchange="changebranch(<?php echo $row["order_no"]; ?>)" style="display:none;">
                                    <option value="" <?php if ($row["branch"] == '') {
                                                            echo 'selected';
                                                        } ?>></option>
                                    <option value="City Stars Mall 1" <?php if ($row["branch"] == 'City Stars Mall 1') {
                                                                            echo 'selected';
                                                                        } ?>>City Stars Mall 1</option>
                                    <option value="City Stars Mall 2" <?php if ($row["branch"] == 'City Stars Mall 2') {
                                                                            echo 'selected';
                                                                        } ?>>City Stars Mall 2</option>
                                    <option value="City Center Almaza Mall" <?php if ($row["branch"] == 'City Center Almaza Mall') {
                                                                                echo 'selected';
                                                                            } ?>>City Center Almaza Mall</option>
                                    <option value="Downtown Mall" <?php if ($row["branch"] == 'Downtown Mall') {
                                                                        echo 'selected';
                                                                    } ?>>Downtown Mall</option>
                                    <option value="Cairo Festival City Mall" <?php if ($row["branch"] == 'Cairo Festival City Mall') {
                                                                                    echo 'selected';
                                                                                } ?>>Cairo Festival City Mall</option>
                                    <option value="Mar.V Mall" <?php if ($row["branch"] == 'Mar.V Mall') {
                                                                    echo 'selected';
                                                                } ?>>Mar.V Mall</option>
                                    <option value="Maxim Mall" <?php if ($row["branch"] == 'Maxim Mall') {
                                                                    echo 'selected';
                                                                } ?>>Maxim Mall</option>
                                    <option value="Point 90 Mall" <?php if ($row["branch"] == 'Point 90 Mall') {
                                                                        echo 'selected';
                                                                    } ?>>Point 90 Mall</option>
                                    <option value="Branch Phone" <?php if ($row["branch"] == 'Branch Phone') {
                                                                        echo 'selected';
                                                                    } ?>>Branch Phone</option>
                                    <option value="El-Mohandessin" <?php if ($row["branch"] == 'El-Mohandessin') {
                                                                        echo 'selected';
                                                                    } ?>>El-Mohandessin</option>
                                    <option value="Galleria 40" <?php if ($row["branch"] == 'Galleria 40') {
                                                                    echo 'selected';
                                                                } ?>>Galleria 40</option>
                                    <option value="Mall Of Arabia 1" <?php if ($row["branch"] == 'Mall Of Arabia 1') {
                                                                            echo 'selected';
                                                                        } ?>>Mall Of Arabia 1</option>
                                    <option value="Mall Of Arabia 2" <?php if ($row["branch"] == 'Mall Of Arabia 2') {
                                                                            echo 'selected';
                                                                        } ?>>Mall Of Arabia 2</option>
                                    <option value="Mall Of Egypt" <?php if ($row["branch"] == 'Mall Of Egypt') {
                                                                        echo 'selected';
                                                                    } ?>>Mall Of Egypt</option>
                                </select>





                                <span id="statusbtns<?php echo $row["order_no"]; ?>">

                                    <button title="Printed" class="fulfil-btn" onclick="setprinted(<?php echo $row["order_no"]; ?>)"><i class="bi bi-printer-fill"></i></button>

                                    <button title="Fulfilled" class="fulfil-btn" onclick="setfulfilled(<?php echo $row["order_no"]; ?>)"><i class="bi bi-truck"></i></button>

                                    <button title="Cash" class="fulfil-btn" onclick="setcash(<?php echo $row["order_no"]; ?>)"><i class="bi bi-cash-coin"></i></button>

                                    <button title="Visa" class="fulfil-btn" onclick="setvisa(<?php echo $row["order_no"]; ?>)"><i class="bi bi-credit-card-2-front-fill"></i></button>

                                    <button title="Cancel" class="fulfil-btn" onclick="setocancelled(<?php echo $row["order_no"]; ?>)"><i class="bi bi-x-circle-fill"></i></button>

                                </span>
                            </td>

                            <td>
                                <textarea class="comment" id="thecomment<?php echo $row["order_no"]; ?>" onchange="changecomment(<?php echo $row["order_no"]; ?>)"><?php echo $row["comment"]; ?></textarea>
                            </td>
                        </tr>
                    <?php } ?>



                </tbody>
            </table>
        </div>






        <button onclick="topFunction()" id="myBtn" title="Go to top"><i class="bi bi-arrow-up-circle-fill"></i></button>
        <script>
            <?php foreach ($result as $row) { ?>
                statusbtns = document.getElementById('statusbtns' + <?php echo $row['order_no']; ?>);
                confbtns = document.getElementById('confirmationbtns' + <?php echo $row['order_no']; ?>);


                <?php if ($row["confirmation"] == 'New') { ?>
                    confbtns.style.display = 'block';
                    statusbtns.style.display = 'none';
                <?php } ?>

                <?php if ($row["confirmation"] == 'On Hold' or $row["confirmation"] == 'No Answer') { ?>
                    confbtns.style.display = 'block';
                    statusbtns.style.display = 'none';
                <?php } ?>

                <?php if ($row["confirmation"] == 'Cancelled' or $row["status"] == 'Cancelled' or $row["status"] == 'Cash' or $row["status"] == 'Visa') { ?>
                    confbtns.style.display = 'none';
                    statusbtns.style.display = 'none';
                <?php } ?>

                <?php if ($row["confirmation"] == 'Confirmed' && $row["status"] == '') { ?>
                    confbtns.style.display = 'none';
                    statusbtns.style.display = 'block';
                    statusbtns.getElementsByTagName('button')[0].style.display = 'inline-block'; //Printed Btn
                    statusbtns.getElementsByTagName('button')[1].style.display = 'none'; //Fulfilled Btn
                    statusbtns.getElementsByTagName('button')[2].style.display = 'none'; //Cash Btn
                    statusbtns.getElementsByTagName('button')[3].style.display = 'none'; //Visa Btn
                    statusbtns.getElementsByTagName('button')[4].style.display = 'inline-block'; //Cancel Btn
                <?php } ?>

                <?php if ($row["confirmation"] == 'Confirmed' && $row["status"] == 'Printed') { ?>
                    confbtns.style.display = 'none';
                    statusbtns.style.display = 'block';
                    statusbtns.getElementsByTagName('button')[0].style.display = 'none'; //Printed Btn
                    statusbtns.getElementsByTagName('button')[1].style.display = 'inline-block'; //Fulfilled Btn
                    statusbtns.getElementsByTagName('button')[2].style.display = 'none'; //Cash Btn
                    statusbtns.getElementsByTagName('button')[3].style.display = 'none'; //Visa Btn
                    statusbtns.getElementsByTagName('button')[4].style.display = 'none'; //Cancel Btn
                <?php } ?>

                <?php if ($row["confirmation"] == 'Confirmed' && $row["status"] == 'Fulfilled') { ?>
                    confbtns.style.display = 'none';
                    statusbtns.style.display = 'block';
                    statusbtns.getElementsByTagName('button')[0].style.display = 'none'; //Printed Btn
                    statusbtns.getElementsByTagName('button')[1].style.display = 'none'; //Fulfilled Btn
                    statusbtns.getElementsByTagName('button')[2].style.display = 'inline-block'; //Cash Btn
                    statusbtns.getElementsByTagName('button')[3].style.display = 'inline-block'; //Visa Btn
                    statusbtns.getElementsByTagName('button')[4].style.display = 'inline-block'; //Cancel Btn
                    document.getElementById('thebranch<?php echo $row['order_no']; ?>').style.display = 'inline-block';
                <?php } ?>



            <?php } ?>






            $('.search-game').search();
            // $fn.search(callback,timeout);
            $('#searchin').search(function() {
                // execute after filtering
            }, 3000);






            // When the user clicks on div, open the popup
            function myFunction(x) {


                var y = "myPopup" + x;

                var popup = document.getElementById(y);

                if (window.getComputedStyle(popup).visibility === "visible") {
                    popup.style.visibility = "hidden";
                } else {
                    popup.style.visibility = "visible";
                }


                var slides = document.getElementsByClassName("popuptext");

                for (var i = 0; i < slides.length; i++) {

                    if (window.getComputedStyle(slides[i]).visibility === "visible") {
                        slides[i].style.visibility = "hidden";
                        popup.style.visibility = "visible";
                        $(popup).load("popupcontent.php?id=" + x);
                    }

                }
            }

            function mysearchFunction() {
                var input, filter, table, tr, td, i, txtValue;
                input = document.getElementById("myInput");
                filter = input.value.toUpperCase();
                table = document.getElementById("myTable");
                tr = table.getElementsByTagName("tr");
                for (i = 0; i < tr.length; i++) {
                    svalue = document.getElementById("filtersearchbar").value
                    if (svalue == 'order') {
                        td = tr[i].getElementsByTagName("td")[0];
                    }
                    if (svalue == 'name') {
                        td = tr[i].getElementsByTagName("td")[2];
                    }
                    if (svalue == 'phone') {
                        td = tr[i].getElementsByTagName("td")[3];
                    }

                    if (td) {
                        txtValue = td.textContent || td.innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            tr[i].style.display = "";
                        } else {
                            tr[i].style.display = "none";
                        }
                    }
                }
            }



            //Get the button
            var mybutton = document.getElementById("myBtn");

            // When the user scrolls down 20px from the top of the document, show the button
            window.onscroll = function() {
                scrollFunction()
            };

            function scrollFunction() {
                if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                    mybutton.style.display = "block";
                } else {
                    mybutton.style.display = "none";
                }
            }

            // When the user clicks on the button, scroll to the top of the document
            function topFunction() {
                document.body.scrollTop = 0;
                document.documentElement.scrollTop = 0;
            }


            $(function() {
                var dtToday = new Date();

                var month = dtToday.getMonth() + 1;
                var day = dtToday.getDate();
                var year = dtToday.getFullYear();
                if (month < 10)
                    month = '0' + month.toString();
                if (day < 10)
                    day = '0' + day.toString();

                var maxDate = year + '-' + month + '-' + day;

                // or instead:
                // var maxDate = dtToday.toISOString().substr(0, 10);
                $('.ddate').attr('min', maxDate);
            });

            function printExternal(url) {
                var printWindow = window.open(url, 'Print', 'left=200, top=200, width=950, height=500, toolbar=0, resizable=0');
                printWindow.addEventListener('load', function() {
                    printWindow.print();
                }, true);
            }
        </script>
</body>

</html>