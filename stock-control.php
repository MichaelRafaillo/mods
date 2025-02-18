<?php
session_start();
include './database/user.php';
include './database/dbh.php';


if (isset($_POST['but_import'])) {
  //   $sqldel = "DELETE FROM parts";
  //   mysqli_query($conn,$sqldel);


  $target_dir = "uploads/";
  $target_file = $target_dir . basename($_FILES["importfile"]["name"]);

  $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

  $uploadOk = 1;
  if ($imageFileType != "csv") {
    $uploadOk = 0;
  }

  if ($uploadOk != 0) {
    if (move_uploaded_file($_FILES["importfile"]["tmp_name"], $target_dir . 'importfile.csv')) {

      // Checking file exists or not
      $target_file = $target_dir . 'importfile.csv';
      $fileexists = 0;
      if (file_exists($target_file)) {
        $fileexists = 1;
      }
      if ($fileexists == 1) {

        // Reading file
        $file = fopen($target_file, "r");
        $i = 0;

        $importData_arr = array();

        while (($data = fgetcsv($file, 1000, ",")) !== FALSE) {
          $num = count($data);

          for ($c = 0; $c < $num; $c++) {
            $importData_arr[$i][] = mysqli_real_escape_string($conn, $data[$c]);
          }
          $i++;
        }
        fclose($file);

        $skip = 0;
        // insert import data
        foreach ($importData_arr as $data) {
          // if($skip != 0){
          $id = $data[0];
          $ocode = $data[1];
          $acode = $data[2];
          $weight = $data[3];
          $price = $data[4];
          $taken = $data[5];
          $level = $data[6];


          // Checking duplicate entry
          $sql = "select count(*) as allcount from parts where id='" . $id . "' and online_code='" . $ocode . "' and  acc_code='" . $acode . "' and weight='" . $weight . "' and price='" . $price . "' and taken='" . $taken . "' and level='" . $level . "' ";

          $retrieve_data = mysqli_query($conn, $sql);
          $row = mysqli_fetch_array($retrieve_data);
          $count = $row['allcount'];

          if ($count == 0) {
            // Insert record
            $insert_query = "insert into parts(id,online_code,acc_code,weight,price,taken,level) values('" . $id . "','" . $ocode . "','" . $acode . "','" . $weight . "','" . $price . "','" . $taken . "','" . $level . "')";
            mysqli_query($conn, $insert_query);
          }
          // }
          $skip++;
        }
        $newtargetfile = $target_file;
        if (file_exists($newtargetfile)) {
          unlink($newtargetfile);
        }
      }
    }
  }
}
?>


<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Stock Control - Glamour ODS</title>
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


</head>

<body>

  <?php include './includes/navbar.php'; ?>

  <br>
  <h4 class="text-center">Stock Control & Items Managment</h4>

  <!-- Export form (start) -->

  <div class="container text-center">

    <form method='post' action='download.php'>
      <input type='submit' value='Export Excel Sheet' name='Export' class="btn btn-secondary">

      <?php
      $query = "SELECT * FROM parts ORDER BY id asc";
      $result = mysqli_query($conn, $query);
      $user_arr = array();
      while ($row = mysqli_fetch_array($result)) {
        $id = $row['id'];
        $ocode = $row['online_code'];
        $acode = $row['acc_code'];
        $weight = $row['weight'];
        $price = $row['price'];
        $taken = $row['taken'];
        $level = $row['level'];
        $user_arr[] = array($id, $ocode, $acode, $weight, $price, $taken, $level);
      }
      $serialize_user_arr = serialize($user_arr);
      ?>
      <textarea name='export_data' style='display: none;'><?php echo $serialize_user_arr; ?></textarea>
    </form>
  </div>

  <!-- Import form (End) -->




  <br>
  <H5 class="text-center">Excel Sheet (.CSV)</H5>
  <br>
  <!-- Import form (start) -->
  <div class="popup_import text-center">
    <form method="post" action="" enctype="multipart/form-data" id="import_form">

      <input type='file' name="importfile" id="importfile" class="btn btn-secondary" required>
      <input type="submit" id="but_import" name="but_import" value="Upload" class="btn btn-primary">


    </form>
  </div>
  <br>
  <H5 class="text-center">Deleted Excel Sheet (.CSV)</H5>
  <br>
  <!-- Import form (start) -->
  <div class="popup_import text-center">
    <form method="post" action="delete-stock.php" enctype="multipart/form-data" id="import_form">

      <input type='file' name="importfile" id="importfile" class="btn btn-secondary" required>
      <input type="submit" id="but_delete" name="but_delete" value="Upload" class="btn btn-primary">


    </form>
  </div>
  <!-- Import form (end) -->
  <?php if (isset($_GET['delete'])) { ?>
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
      <strong>item!</strong> deleted Successfuly.
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  <?php } ?>


  <div class="container-fluid searchcontainer">
    <div class="row">
      <div class="col-md-4 col-sm-8">
        <input class="inputsearch" type="text" id="myInput" onkeyup="mysearchFunction()" placeholder="Search for names.." title="Type in a name">
      </div>
      <div class="col-md-2 col-sm-4">
        <select class="inputsearch" id="filtersearchbar">
          <option value="ocode" Selected>Online Code</option>
          <option value="acode">Acc. Code</option>

        </select>
      </div>
      <div class="col-md-3 col-sm-12">
        <button type="button" class="btn btn-success col-12" onclick="openbtn()"><i class="bi bi-plus-lg"></i> Create New Order</button>
      </div>

    </div>
  </div>
  <br>
  <!-- Displaying imported users -->
  <div class="container-fluid m-0 p-0">
    <div class="row justify-content-md-center text-center m-0">
      <table border="1" id="userTable" class="table table-striped table-dark" style="font-size:0.8rem;">
        <tr>
          <td>id</td>
          <td>Online Code</td>
          <td>Acc Code</td>
          <td>Order</td>
          <td>Level</td>
          <td>Action</td>
        </tr>
        <?php
        $sql = "select * from parts order by id DESC";
        $sno = 1;
        $retrieve_data = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_array($retrieve_data)) {
          $id = $row['id'];
          $onlinecode = $row['online_code'];
          $acccode = $row['acc_code'];
          $takens = $row['taken'];
          $levels = $row['level'];

          echo "<tr>
            <td>" . $id . "</td>
            <td>" . $onlinecode . "</td>
            <td>" . $acccode . "</td>
            <td>" . $takens . "</td>
            <td>" . $levels . "</td>" ?>
          <td>
            <form method="POST" name="update" action="update.php">
              <button class='btn btn-success btn-sm rounded-0' type='submit' data-toggle='tooltip' data-placement='top' title='Edit' name='edit'><i class='fa fa-edit'></i></button>
              <button class='btn btn-danger btn-sm rounded-0' type='submit' data-toggle='tooltip' data-placement='top' title='Delete' name="delete"><i class='fa fa-trash' onclick="confirmit()"></i></button>
              <input type="hidden" name="id" value="<?php echo $id; ?>">
            </form>
          </td>

          </tr>
        <?php
          $sno++;
        }
        ?>
      </table>
    </div>
  </div>

  <script type="text/javascript">
    function mysearchFunction() {
      var input, filter, table, tr, td, i, txtValue;
      input = document.getElementById("myInput");
      filter = input.value.toUpperCase();
      table = document.getElementById("userTable");
      tr = table.getElementsByTagName("tr");
      for (i = 0; i < tr.length; i++) {
        svalue = document.getElementById("filtersearchbar").value
        if (svalue == 'ocode') {
          td = tr[i].getElementsByTagName("td")[1];
        }
        if (svalue == 'acode') {
          td = tr[i].getElementsByTagName("td")[2];
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

    function confirmit() {
      confirm("Are You Sure ?");
    }
  </script>
</body>

</html>