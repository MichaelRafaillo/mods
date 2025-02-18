<?php
include './database/dbh.php';
session_start();
if (isset($_POST['login'])) {
	$email = $_POST['email'];
	$password = $_POST['password'];
	$query = "SELECT * FROM users WHERE email='$email' AND password='$password'";
	$result = $conn->query($query);
	$resultno = mysqli_num_rows($result);
	if ($resultno == 1) {
		while($row = $result->fetch_assoc()) {
			$_SESSION['user'] = $row['email'];
			$_SESSION['user_type'] = $row['type'];
		}
	 	header("Location: ./index.php");
	 }else{
	 	$faild = 0;
	 } 
}
?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title>Login - Glamour ODS</title>
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

<div class="d-flex justify-content-center">

<form method="post" class="col-md-4">
	<div class="text-center"><img src="./img/logo.png" width="200"></div>

<?php if (isset($faild)) { ?>
	<div class="alert alert-danger" role="alert">
  Wrong Email or Password!
	</div>
<?php } ?>
	


  <!-- Email input -->
  <div class="form-outline mb-4">
    <input type="email" name="email" id="form2Example1" class="form-control" required />
    <label class="form-label" for="form2Example1">Email</label>
  </div>

  <!-- Password input -->
  <div class="form-outline mb-4">
    <input type="password" name="password" id="form2Example2" class="form-control" required />
    <label class="form-label" for="form2Example2">Password</label>
  </div>

  <!-- 2 column grid layout for inline styling -->
  <div class="row mb-4">
    <div class="col d-flex justify-content-center">
      <!-- Checkbox -->
      <div class="form-check">
        <input class="form-check-input" type="checkbox" value="" id="form2Example31" checked />
        <label class="form-check-label" for="form2Example31"> Remember me </label>
      </div>
    </div>

    <div class="col">
      <!-- Simple link -->
      <a href="#!">Forgot password?</a>
    </div>
  </div>

  <!-- Submit button -->
  <button type="submit" name="login" class="btn btn-primary btn-block mb-4">Sign in</button>

  <!-- Register buttons -->
  <!--
  <div class="text-center">
    <p>Not a member? <a href="#!">Register</a></p>
    <p>or sign up with:</p>
    <button type="button" class="btn btn-link btn-floating mx-1">
      <i class="fab fa-facebook-f"></i>
    </button>

    <button type="button" class="btn btn-link btn-floating mx-1">
      <i class="fab fa-google"></i>
    </button>

    <button type="button" class="btn btn-link btn-floating mx-1">
      <i class="fab fa-twitter"></i>
    </button>

    <button type="button" class="btn btn-link btn-floating mx-1">
      <i class="fab fa-github"></i>
    </button>
  </div>
  -->
</form>
</div>

<br>
 <div class="text-center" style="font-size:12px;">
    <p>Designed By <a href="https://www.facebook.com/Michael.Rafaillo">Michael Rafaillo</a></p>

</div>

</body>
</html>