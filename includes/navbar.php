<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="#">
    <img src="./img/favicon.ico" width="30" height="30" class="d-inline-block align-top" alt="">
  </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="./index.php">Home <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="./stock-control.php">Stock Control</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="./addpart.php">Add Item to Order</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="./feedback.php">Feedback</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="./auto.php">Fetch Orders</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="./checkout.php">Checkout Reminder</a>
      </li>
      <?php
      if ($_SESSION['user_type'] === 'admin') {
        echo
        '<li class="nav-item">
        <a class="nav-link" href="./report.php">Report</a>
      </li>';
      }  ?>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <?php echo $_SESSION['user']; ?>
        </a>

        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <!--<a class="dropdown-item" href="#">Action</a>
          <a class="dropdown-item" href="#">Another action</a>
          <div class="dropdown-divider"></div>-->
          <a class="dropdown-item" href="./database/logout.php">Logout</a>
        </div>
      </li>
    </ul>
  </div>











</nav>