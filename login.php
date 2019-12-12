<?php
require './auth.php';

$username = $_POST["username"] ?? '';
$password = $_POST["password"] ?? '';
$loginResult = array("loginSuccess" => false);

if (isset($_POST["loginBtn"]) && isset($_POST["password"]) && isset($_POST["password"])) {
  $account = new Account();

  try {
    $loginResult = $account->login($username, $password);
  } catch (Exception $e) {
    echo "$e";
    echo "Error occurred connecting to server.";
  }
}

function fieldHasErrors(string $fieldName): bool
{
  global $loginResult;
  if (isset($loginResult["hasErrors"]) && $loginResult['hasErrors'] && array_key_exists($fieldName, $loginResult)) {
    return true;
  }
  return false;
}



print_r($_SESSION);
if ($loginResult["loginSuccess"]) {
  $_SESSION["message"] = $loginResult["message"];
  $_SESSION["userData"] = $loginResult["accountInstance"];
  
  print_r($_SESSION);
  // session_write_close();
  header('Location: index.php');
  exit; 
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>SB Admin - Login</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin.css" rel="stylesheet">

</head>

<body class="bg-dark">

  <div class="container">
    <div class="card card-login mx-auto mt-5">
      <div class="card-header">Login</div>
      <div class="card-body">
        <form action="login.php" method="post">
          <div class="form-group <?php if (fieldHasErrors("username")) : ?> has-error <?php endif; ?>">
            <div class="form-label-group">
              <input type="text" id="inputUsername" name="username" class="form-control <?php if (fieldHasErrors("username")) : ?> is-invalid <?php endif; ?>" placeholder="Username" required="required" autofocus="autofocus" value="<?= $username ?>">
              <label for="inputEmail">Username</label>
              <?php if (fieldHasErrors("username")) : ?>
                <span class="help-block"><?= $loginResult["username"] ?></span>
              <?php endif; ?>
            </div>
          </div>
          <div class="form-group <?php if (fieldHasErrors("password")) : ?> has-error <?php endif; ?>">
            <div class="form-label-group">
              <input type="password" id="inputPassword" name="password" class="form-control <?php if (fieldHasErrors("password")) : ?> is-invalid <?php endif; ?>" placeholder="Password" required="required" value="<?= $password ?>">
              <label for="inputPassword">Password</label>
              <?php if (fieldHasErrors("password")) : ?>
                <span class="help-block"><?= $loginResult["password"] ?></span>
              <?php endif; ?>
            </div>
          </div>

          <?php if (isset($loginResult["message"]) && !$loginResult["loginSuccess"]) { ?>
            <div class="alert alert-danger" role="login-error"> <?= $loginResult["message"] ?></div>
          <?php } ?>
          <!-- <div class="form-group">
            <div class="checkbox">
              <label>
                <input type="checkbox" value="remember-me">
                Remember Password
              </label>
            </div>
          </div> -->
          <button type="submit" name="loginBtn" class="btn btn-primary btn-block">Login</button type="submit">
        </form>
        <div class="text-center">
          <a class="d-block small mt-3" href="register.php">Register an Account</a>
          <a class="d-block small" href="forgot-password.html">Forgot Password?</a>
        </div>
      </div>
    </div>

    <!-- <?php

          // cccc
          // $message="";
          // if(count($_POST)>0) {
          // 	$conn = mysqli_connect("localhost","root","","phppot_examples");
          // 	$result = mysqli_query($conn,"SELECT * FROM users WHERE user_name='" . $_POST["userName"] . "' and password = '". $_POST["password"]."'");
          // 	$count  = mysqli_num_rows($result);
          // 	if($count==0) {
          // 		$message = "Invalid Username or Password!";
          // 	} else {
          // 		$message = "You are successfully authenticated!";
          // 	}
          // }
          ?> -->
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

</body>

</html>