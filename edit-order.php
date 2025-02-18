<?php
session_start();
include './database/dbh.php';

//order number
$id = $_GET['id'];

if (isset($_POST['delete'])) {
  $query3 = "DELETE FROM orders WHERE order_no = '$id'";
  mysqli_query($conn, $query3);

  //delete the feedback row
  $deletefeedback = "DELETE FROM feedback WHERE order_no = '$id'";
  mysqli_query($conn, $deletefeedback);

  $query8 = "UPDATE parts SET parts.taken='' WHERE parts.taken='$id'";
  mysqli_query($conn, $query8);

  header("Location: ./index.php");
}
if (isset($_POST['additemtoorder'])) {
  $id = $_POST['id'];
  $onlinecode = $_POST['newitem'];

  $sql7 = "UPDATE parts SET taken = '$id' WHERE online_code='$onlinecode' AND taken = '' LIMIT 1";
  mysqli_query($conn, $sql7);
  $rowscount = mysqli_affected_rows($conn);
  if ($rowscount == 1) {
    header("Location: ./edit-order.php?alertitemdel=success&id=" . $id);
  } else {
    header("Location: ./edit-order.php?alertitemdel=failed&id=" . $id);
  }
}
if (isset($_POST['deleteitem'])) {
  $id = $_POST['id'];
  $accsku = $_POST['accsku'];

  $sql6 = "UPDATE parts SET taken = '' WHERE acc_code = '$accsku'";
  mysqli_query($conn, $sql6);

  header("Location: ./edit-order.php?alertitem=success&id=" . $id);
}

if (isset($_POST['submit'])) {
  $id = $_POST['id'];
  $orderdate = $_POST['orderdate'];
  $confirmation = $_POST['confirmation'];
  $name = $_POST['name'];
  $phone = $_POST['phone'];
  $zone = $_POST['zone'];
  $ddate = $_POST['ddate'];
  $status = $_POST['status'];
  $comment = $_POST['comment'];
  $address = $_POST['address'];


  $sql = "UPDATE `orders` SET `order_no` = '$id', `order_date`='$orderdate', `confirmation`='$confirmation',`name` = '$name' , `phone` = '$phone' , `zone` = '$zone' , `delivery_date` = '$ddate' , `status` = '$status' , `comment` = '$comment' , `address` = '$address' WHERE `order_no` = '$id'";
  mysqli_query($conn, $sql);
  echo mysqli_error($conn);
  echo $ddate;

  $sql5 = "UPDATE parts SET parts.taken = '$id' WHERE parts.taken = '$id'";
  mysqli_query($conn, $sql5);

  $sql13 = "UPDATE cancel SET order_number = '$id' WHERE cancel.order_number = '$id'";
  mysqli_query($conn, $sql13);


  if ($confirmation == 'Cancelled' or $status == 'Cancelled') {

    //add cancelled items to cancel table
    $sql8 = "SELECT * FROM parts WHERE taken = $id";
    $result8 = mysqli_query($conn, $sql8);
    while ($row8 = mysqli_fetch_array($result8)) {
      $onlinecode = $row8["online_code"];
      $sql9 = "INSERT INTO cancel VALUES ('$id','$onlinecode') ";
      mysqli_query($conn, $sql9);
    }

    //remove ordered items from parts table
    $sql11 = "UPDATE `parts` SET `taken` = '' WHERE `parts`.`taken` = '$id'";
    mysqli_query($conn, $sql11);
  }

  header("Location: ./edit-order.php?alert=success&id=" . $id);
}
$sql2 = "SELECT * FROM orders WHERE order_no = '$id'";
$query = mysqli_query($conn, $sql2);

$sql4 = "SELECT * FROM parts WHERE taken = '$id'";
$query4 = mysqli_query($conn, $sql4);
$noofitems = mysqli_num_rows($query4);

//while($row = $result->fetch_assoc()) {
$idnext = $id + 1;
$idprev = $id - 1;

$prev = "SELECT * FROM orders WHERE order_no = '$idprev'";
$prevcount = mysqli_num_rows(mysqli_query($conn, $prev));

$next = "SELECT * FROM orders WHERE order_no = '$idnext'";
$nextcount = mysqli_num_rows(mysqli_query($conn, $next));

?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Update Order - Glamour ODS</title>
  <link rel="stylesheet" href="./css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="https://unpkg.com/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
  <script src="./js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="./css/style.css">
  <link rel="icon" type="image/x-icon" href="./img/favicon.ico">

  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  <style type="text/css">
    .btn-secondary:not(:disabled):not(.disabled).active,
    .btn-secondary:not(:disabled):not(.disabled):active,
    .show>.btn-secondary.dropdown-toggle {
      color: black;
      background-color: greenyellow;
      border-color: #4e555b;
    }

    .arrows {
      font-size: 30px;
      float: right;
      color: white;
    }

    .btnarrow {
      border: 0;
      background-color: transparent;
      color: white;
    }

    .btnarrow:hover {
      color: cyan;
    }
  </style>
</head>

<body>
  <?php include './includes/navbar.php'; ?>

  <?php while ($row = mysqli_fetch_array($query)) { ?>


    <div class="container-fluid">
      <div class="row">




        <div class="col-md-8">
          <form method="POST">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <h1>
              Update Order
              <span class="arrows">

                <a class="btnarrow" href="./edit-order.php?id=<?php echo $id - 1; ?>" style="display:<?php if ($prevcount == 0) {
                                                                                                        echo "none";
                                                                                                      } ?>;">
                  <i class="bi bi-arrow-left-circle" alt="Prev."></i>
                </a>
                <a class="btnarrow" href="./edit-order.php?id=<?php echo $id + 1; ?>" style="display:<?php if ($nextcount == 0) {
                                                                                                        echo "none";
                                                                                                      } ?>;">
                  <i class="bi bi-arrow-right-circle" alt="Next"></i>
                </a>
              </span>
              <?php if ($row['confirmation'] == 'Cancelled' or $row['status'] == 'Cancelled') { ?>
                <span style="color:red;font-size:18px;">( Cancelled )</span>
              <?php } ?>
            </h1>
            <hr>

            <?php if (isset($_GET['alert'])) {
              if ($_GET['alert'] == 'faild') { ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                  <strong>Update Faild!</strong> Order Number Is Taken in Another Order.
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
              <?php } ?>
              <?php if ($_GET['alert'] == 'success') { ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                  <strong>Done!</strong> Order Updated Successfuly!
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
            <?php }
            } ?>
            <br>
            <div class="form-row">
              <div class="form-group col-md-3">
                <label for="inorderdate">Order No.</label>
                <h2>#G<?php echo $id; ?></h2>
              </div>
              <div class="form-group col-md-4">
                <label for="inorderdate">Order Date</label>
                <input type="date" name="orderdate" class="form-control" id="inorderdate" placeholder="Order Date" value="<?php echo $row['order_date']; ?>">
              </div>
              <div class="form-group col-md-4">
                <label for="inputState">Confirmation</label>
                <select id="inputState" class="form-control" name="confirmation" <?php if ($row['confirmation'] == 'Cancelled' or $row['status'] == 'Cancelled') {
                                                                                    echo 'disabled';
                                                                                  } ?>>
                  <option <?php if ($row['confirmation'] == 'New') {
                            echo 'selected';
                          } ?>>New</option>
                  <option <?php if ($row['confirmation'] == 'Confirmed') {
                            echo 'selected';
                          } ?>>Confirmed</option>
                  <option <?php if ($row['confirmation'] == 'On Hold') {
                            echo 'selected';
                          } ?>>On Hold</option>
                  <option <?php if ($row['confirmation'] == 'No Answer') {
                            echo 'selected';
                          } ?>>No Answer</option>
                  <option <?php if ($row['confirmation'] == 'Cancelled') {
                            echo 'selected';
                          } ?>>Cancelled</option>
                </select>
              </div>
              <div class="form-group col-md-6">
                <label for="inname" id="legend">Name</label>
                <input type="text" class="form-control" id="inname" placeholder="Customer Name" name="name" value="<?php echo $row['name']; ?>">
              </div>
              <div class="form-group col-md-6">
                <label for="inputAddress2">Phone</label>
                <input class="form-control" id="inphone" type="number" placeholder="0123...." name="phone" value="<?php echo $row['phone']; ?>">
              </div>
              <div class="form-group col-md-3">
                <label for="inputState">Zone</label>
                <select id="inputState" class="form-control" name="zone">
                  <option <?php if ($row['zone'] == 'Alexandria') {
                            echo 'selected';
                          } ?>>Alexandria</option>
                  <option <?php if ($row['zone'] == 'Aswan') {
                            echo 'selected';
                          } ?>>Aswan</option>
                  <option <?php if ($row['zone'] == 'Asyut') {
                            echo 'selected';
                          } ?>>Asyut</option>
                  <option <?php if ($row['zone'] == 'Beheira') {
                            echo 'selected';
                          } ?>>Beheira</option>
                  <option <?php if ($row['zone'] == 'Beni Suef') {
                            echo 'selected';
                          } ?>>Beni Suef</option>
                  <option <?php if ($row['zone'] == 'Cairo') {
                            echo 'selected';
                          } ?>>Cairo</option>
                  <option <?php if ($row['zone'] == 'Dakahlia') {
                            echo 'selected';
                          } ?>>Dakahlia</option>
                  <option <?php if ($row['zone'] == 'Damietta') {
                            echo 'selected';
                          } ?>>Damietta</option>
                  <option <?php if ($row['zone'] == 'Faiyum') {
                            echo 'selected';
                          } ?>>Faiyum</option>
                  <option <?php if ($row['zone'] == 'Gharbia') {
                            echo 'selected';
                          } ?>>Gharbia</option>
                  <option <?php if ($row['zone'] == 'Giza') {
                            echo 'selected';
                          } ?>>Giza</option>
                  <option <?php if ($row['zone'] == 'Ismailia') {
                            echo 'selected';
                          } ?>>Ismailia</option>
                  <option <?php if ($row['zone'] == 'Kafr El Sheikh') {
                            echo 'selected';
                          } ?>>Kafr El Sheikh</option>
                  <option <?php if ($row['zone'] == 'Luxor') {
                            echo 'selected';
                          } ?>>Luxor</option>
                  <option <?php if ($row['zone'] == 'Matruh') {
                            echo 'selected';
                          } ?>>Matruh</option>
                  <option <?php if ($row['zone'] == 'Minya') {
                            echo 'selected';
                          } ?>>Minya</option>
                  <option <?php if ($row['zone'] == 'Monufia') {
                            echo 'selected';
                          } ?>>Monufia</option>
                  <option <?php if ($row['zone'] == 'Port Said') {
                            echo 'selected';
                          } ?>>Port Said</option>
                  <option <?php if ($row['zone'] == 'Qalyubia') {
                            echo 'selected';
                          } ?>>Qalyubia</option>
                  <option <?php if ($row['zone'] == 'Qena') {
                            echo 'selected';
                          } ?>>Qena</option>
                  <option <?php if ($row['zone'] == 'Red Sea') {
                            echo 'selected';
                          } ?>>Red Sea</option>
                  <option <?php if ($row['zone'] == 'Sharqia') {
                            echo 'selected';
                          } ?>>Sharqia</option>
                  <option <?php if ($row['zone'] == 'Sohag') {
                            echo 'selected';
                          } ?>>Sohag</option>
                  <option <?php if ($row['zone'] == 'South Sinai') {
                            echo 'selected';
                          } ?>>South Sinai</option>
                  <option <?php if ($row['zone'] == 'Suez') {
                            echo 'selected';
                          } ?>>Suez</option>
                </select>

              </div>
              <div class="form-group col-md-3">
                <label for="inorderdate">Delivery Date</label>
                <input type="date" class="form-control" id="inorderdate" placeholder="Order Date" name="ddate" value="<?php echo $row['delivery_date']; ?>">
              </div>

              <div class="form-group col-md-3">
                <label for="inputState">Status</label>
                <select id="inputState" class="form-control" name="status" <?php if ($row['confirmation'] == 'Cancelled' or $row['status'] == 'Cancelled') {
                                                                              echo 'disabled';
                                                                            } ?>>
                  <option <?php if ($row['status'] == '') {
                            echo 'selected';
                          } ?>></option>
                  <option <?php if ($row['status'] == 'Printed') {
                            echo 'selected';
                          } ?>>Printed</option>
                  <option <?php if ($row['status'] == 'Fulfilled') {
                            echo 'selected';
                          } ?>>Fulfilled</option>
                  <option <?php if ($row['status'] == 'Cash') {
                            echo 'selected';
                          } ?>>Cash</option>
                  <option <?php if ($row['status'] == 'Visa') {
                            echo 'selected';
                          } ?>>Visa</option>
                  <option <?php if ($row['status'] == 'Cancelled') {
                            echo 'selected';
                          } ?>>Cancelled</option>
                </select>
              </div>
              <div class="form-group col-md-6">
                <label for="inaddress">Address</label>
                <input type="text" class="form-control" id="inaddress" placeholder="1234 Main St" name="address" value="<?php echo $row['address']; ?>">
              </div>

              <div class="form-group col-md-6">
                <label for="inaddress">Comment</label>
                <input type="text" class="form-control" id="inaddress" placeholder="At 3 O'clock" name="comment" value="<?php echo $row['comment']; ?>">
              </div>

              <?php if ($row['confirmation'] == 'Cancelled' or $row['status'] == 'Cancelled') { ?>
                <input type="hidden" name="confirmation" value="<?php echo $row['confirmation']; ?>">
                <input type="hidden" name="status" value="<?php echo $row['status']; ?>">

              <?php } ?>

              <div class="form-group col-md-12 text-center">
                <button type="submit" name="submit" class="btn btn-primary col-md-6 float-left">Save</button>
                <button type="submit" name="delete" class="btn btn-danger col-md-2 float-right" onclick="return confirm('Are you sure?')">Delete Order</button>
              </div>
            </div>
          </form>
        </div>
        <div class="col-md-4">
          <h1>Items (<?php echo $noofitems; ?>)</h1>
          <hr>
          <?php if (isset($_GET['alertitem'])) { ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              <strong>Done!</strong><i class="bi bi-trash"></i> Item Deleted Successfuly!
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          <?php } ?>
          <?php if (isset($_GET['alertitemdel'])) {
            if ($_GET['alertitemdel'] == 'success') { ?>
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Done!</strong><i class="bi bi-check-circle"></i> Item Added Successfuly!
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
            <?php }
            if ($_GET['alertitemdel'] == 'failed') { ?>
              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Failed!</strong><i class="bi bi-exclamation-triangle-fill"></i> حـــظ سعيد المرة القادمـــة!
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
          <?php }
          } ?>
          <table class="table table-bordered table-dark">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Online SKU</th>
                <th scope="col">Acc Code</th>
                <th scope="col">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php $x = 1;
              while ($row2 = mysqli_fetch_array($query4)) { ?>
                <tr>
                  <th scope="row"><?php echo $x; ?></th>
                  <td><?php echo $row2['online_code']; ?></td>
                  <td><?php echo $row2['acc_code']; ?></td>
                  <td>
                    <form method="POST">
                      <button type="submit" name="deleteitem" class="btn btn-danger" style="font-size:12px;" onclick="return confirm('Are you sure?')">Delete</button><?php $row2['taken'] ?>
                      <input type="hidden" name="id" value="<?php echo $row['order_no'] ?>">
                      <input type="hidden" name="accsku" value="<?php echo $row2['acc_code']; ?>">
                    </form>
                  </td>
                </tr>
              <?php $x++;
              } ?>
              <?php //if ($row['confirmation']=='Cancelled' or $row['status']=='Cancelled') {
              $sql12 = "SELECT * FROM cancel WHERE order_number = '$id'";
              $result12 = mysqli_query($conn, $sql12);
              while ($row12 = mysqli_fetch_array($result12)) {
              ?>
                <tr>
                  <th scope="row"><?php echo $x; ?></th>
                  <td><?php echo $row12['online_code']; ?></td>
                  <td colspan="3" style="color:#ff6b6b;"><?php echo 'Returned'; ?></td>
                </tr>
              <?php $x++;
              } ?>
            </tbody>
          </table>

          <?php if ($row['confirmation'] != 'Cancelled') {
            if ($row['status'] != 'Cancelled') { ?>
              <div class="container p-0 mt-3 mb-5">
                <form method="POST" class="row col-md-12 p-0 m-0">
                  <input type="text" class="form-control col-8" id="inaddress" placeholder="Online Code" name="newitem" value="" required>
                  <button type="submit" name="additemtoorder" class="btn btn-primary col-4" style="font-size:12px;">Add</button>
                  <input type="hidden" name="id" value="<?php echo $row['order_no'] ?>">
                </form>
              </div>
          <?php }
          } ?>


          <!-- Start of Feedback -->
          <?php
          $feedback_query = "SELECT * FROM feedback WHERE order_no = '$id'";
          $feedback_query_res = mysqli_query($conn, $feedback_query);
          while ($feedback_row = mysqli_fetch_array($feedback_query_res)) {
          ?>
            <h1>Feedback</h1>
            <hr>
            <div class="btn-group btn-group-toggle" data-toggle="buttons">
              <label class="btn btn-secondary <?php if ($feedback_row['feedback_status'] == "positive") {
                                                echo "active";
                                              } ?>" onclick="feedback(<?php echo $id; ?>,1)">
                <input type="radio" name="options" id="option1" autocomplete="off" <?php if ($feedback_row['feedback_status'] == "positive") {
                                                                                      echo "checked";
                                                                                    } ?>><i class="bi bi-emoji-smile-fill"></i> Positive
              </label>
              <label class="btn btn-secondary <?php if ($feedback_row['feedback_status'] == "negative") {
                                                echo "active";
                                              } ?>" onclick="feedback(<?php echo $id; ?>,2)">
                <input type="radio" name="options" id="option2" autocomplete="off" <?php if ($feedback_row['feedback_status'] == "negative") {
                                                                                      echo "checked";
                                                                                    } ?>><i class="bi bi-emoji-frown-fill"></i> Negative
              </label>
              <label class="btn btn-secondary <?php if ($feedback_row['feedback_status'] == "no answer") {
                                                echo "active";
                                              } ?>" onclick="feedback(<?php echo $id; ?>,3)">
                <input type="radio" name="options" id="option3" autocomplete="off" <?php if ($feedback_row['feedback_status'] == "no answer") {
                                                                                      echo "checked";
                                                                                    } ?>><i class="bi bi-emoji-neutral-fill"></i> No Answer
              </label>
            </div>
            <textarea class="comment" id="thecomment" style="border:1!important;" placeholder="Write the Feedback here...." onchange="changecomment(<?php echo $id; ?>)"><?php echo $feedback_row["feedback_comment"]; ?></textarea>
          <?php } ?>
          <!-- End of Feedback -->

        </div>
      </div>
    </div>
    </div>
  <?php } ?>





  <script type="text/javascript">
    function feedback(str, num) {
      if (str == "") {
        document.getElementById("confirmation" + str).innerHTML = "";
        return;
      }
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          //if i want make a change when select
        }
      };
      if (num == "1") {
        xmlhttp.open("GET", "./database/changefeedback.php?q=" + str + "&feedback=positive", true);
      }
      if (num == "2") {
        xmlhttp.open("GET", "./database/changefeedback.php?q=" + str + "&feedback=negative", true);
      }
      if (num == "3") {
        xmlhttp.open("GET", "./database/changefeedback.php?q=" + str + "&feedback=noanswer", true);
      }
      if (num == "4") {
        xmlhttp.open("GET", "./database/changefeedback.php?q=" + str + "&feedback=none", true);
      }

      xmlhttp.send();
    }

    function changecomment(str) {
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          let $com = document.getElementById("thecomment").value;
        }
      };
      var $com = document.getElementById("thecomment").value;
      xmlhttp.open("GET", "./database/changefeedbackcomm.php?q=" + str + "&comm=" + $com, true);
      xmlhttp.send();
    }
  </script>
</body>

</html>