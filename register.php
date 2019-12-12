<?php
require './auth.php';

$username = $_POST["username"] ?? '';
$email = $_POST["email"] ?? '';
$password = $_POST["password"] ?? '';
$confirmPassword = $_POST["confirmPassword"] ?? '';
$validationResult = array("hasErrors" => true);
$passError;


if (isset($_POST["registerBtn"]) && isset($_POST["password"]) && isset($_POST["password"])) {
  $account = new Account();

  try {
    $validationResult = $account->addAccount($username, $email, $password, $confirmPassword);
  } catch (Exception $e) {
    echo "$e";
    echo "Error occurred connecting to the database.";
  }
}

function fieldHasErrors(string $fieldName): bool
{
  global $validationResult;
  if (isset($validationResult["hasErrors"]) && $validationResult['hasErrors'] && array_key_exists($fieldName, $validationResult)) {
    return true;
  }
  return false;
}

$passError = fieldHasErrors("password");

echo "$passError";

if (!$validationResult["hasErrors"]) {
  $_COOKIE["message"] = "User successfully registered";
  $_COOKIE["userData"] = $validationResult["resultData"];
  header('Location: index.php');
}
?>




<!DOCTYPE html>
<html lang="en">

<?php include("head_file.php"); ?>

<body class="bg-dark">

  <div class="container">
    <div class="card card-register mx-auto mt-5">
      <div class="card-header">Register an Account</div>
      <div class="card-body">
        <form action="register.php" method="post">
          <div class="form-group <?php if (fieldHasErrors("username") || fieldHasErrors("email") || fieldHasErrors("email_na")) : ?> has-error <?php endif; ?>">
            <div class="form-row">
              <div class="col-md-6">
                <div class="form-label-group">
                  <input type="text" name="username" id="username" class="form-control 
                  <?php if (fieldHasErrors("username")) : ?> is-invalid <?php endif; ?>" placeholder="First name" required="required" autofocus value="<?= $username ?>">
                    
                  <label for="firstName">User Name</label>

                  <?php if (fieldHasErrors("username")): ?> 
                    <span class="help-block"><?=$validationResult["username"]?></span>
                  <?php elseif (fieldHasErrors("username_na")): ?> 
                    <span class="help-block"><?=$validationResult["username_na"]?></span>
                  <?php endif; ?>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-label-group">
                  <input type="email" name="email" id="inputEmail" class="form-control 
                  <?php if (fieldHasErrors("email")) : ?> is-invalid <?php endif; ?>" placeholder="Email address" required="required" value="<?= $email ?>">
                  <label for="inputEmail">Email</label>
                  <?php if (fieldHasErrors("email")): ?> 
                    <span class="help-block"><?=$validationResult["email"]?></span>
                  <?php elseif (fieldHasErrors("email_na")): ?> 
                    <span class="help-block"><?=$validationResult["email_na"]?></span>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group <?php if (fieldHasErrors("password") || fieldHasErrors("password_confirm")) : ?> has-error <?php endif; ?>">
            <div class="form-row">
              <div class="col-md-6">
                <div class="form-label-group">
                  <input type="password" name="password" id="inputPassword" class="form-control
                  <?php if (fieldHasErrors("password")) : ?> is-invalid <?php endif; ?>" placeholder="Password" required="required">
                  <label for="inputPassword">Password</label>
                  
                  <?php if (fieldHasErrors("password")): ?> 
                    <span class="help-block"><?=$validationResult["password"]?></span>
                  <?php elseif (fieldHasErrors("password_confirm")): ?> 
                    <span class="help-block"><?=$validationResult["password_confirm"]?></span>
                  <?php endif; ?>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-label-group">
                  <input type="password" name="confirmPassword" id="confirmPassword" class="form-control 
                  <?php if (fieldHasErrors("password_confirm")) : ?> is-invalid <?php endif; ?>" placeholder="Confirm password" required="required">
                  <label for="confirmPassword">Confirm password</label>
                  <?php if (fieldHasErrors("password")): ?> 
                    <span class="help-block"><?=$validationResult["password"]?></span>
                  
                  <?php elseif (fieldHasErrors("password_confirm")): ?> 
                    <span class="help-block"><?=$validationResult["password_confirm"]?></span>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
          <!-- <a class="btn btn-primary btn-block" href="login.html">Register</a> -->
          <button class="btn btn-primary btn-block" name="registerBtn" type="submit">Register</button>
        </form>
        <div class="text-center">
          <a class="d-block small mt-3" href="login.html">Login Page</a>
          <a class="d-block small" href="forgot-password.html">Forgot Password?</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

</body>

</html>