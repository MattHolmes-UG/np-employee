<?php
require './db_inc.php';
require './dbconnect.php';
$employee = new Employee();

// $uqualifications = $employee->getQualifications();
$sql_qual = "SELECT * FROM qualification";
$uqualifications = $emp_db_con->query($sql_qual);

$e_id = $_POST["ue_id"] ?? "";
$f_name = $_POST["uFirstName"] ?? "";
$l_name = $_POST["uLastName"] ?? "";
$udob = $_POST["udob"] ?? "";
$date_joined = $_POST["uDateJoined"] ?? "";
$usalary = $_POST["usalary"] ?? "";
$uqualification = $_POST["uqualification"] ?? "";
$show_update_modal = false;


$updateValidationResult = array();
$validationError = "Validation Error";

if (isset($_POST['update_btn'])) {

  try {
    $initializedEmployee = $employee->initializeEmployee($e_id);
  } catch (Exception $e) {
    $updateValidationResult = array("hasErrors" => true, "errorMsg" => "$e Error connecting to database");
    $show_update_modal = true;
  }

  if (!is_null($initializedEmployee->id)) {
    try {
      $updateValidationResult = $initializedEmployee->updateEmployee($f_name, $l_name, $usalary, $uqualification, $udob, $date_joined);
    } catch (Exception $e) {
      echo "Error occurred connecting to server.";
    }

    if (isset($updateValidationResult["hasErrors"]) && !$updateValidationResult["hasErrors"]) {
      $_SESSION["message"] = "New record updated successfully!";
      header("Refresh:0");
    } else {
      $show_update_modal = true;
    }
  } else {
    $show_update_modal = true;
    $updateValidationResult = array("hasErrors" => true, "errorMsg" => "Failed to initialize employee $e_id");
  }
}


function updateFieldsHasErrors(string $fieldName): bool
{
  global $updateValidationResult;
  if (
    isset($updateValidationResult["hasErrors"]) &&
    $updateValidationResult['hasErrors'] &&
    array_key_exists($fieldName, $updateValidationResult)
  ) {
    return true;
  }
  return false;
}
?>

<div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateEmployeeModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="updateEmployeeModal">Update A New Employee</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <?php 
      // echo "this is the value of the showupdate modal $show_update_modal"; 
      ?>
      <?php 
      // echo "this is the value of the validation result";
      // print_r($updateValidationResult); 
      ?>
      <div class="modal-body">
        <div class="container">
          <div class="card card-register mx-auto mt-5">
            <!-- <div class="card-header">Register an Account</div> -->
            <div class="card-body">
              <?php if (isset($updateValidationResult["errorMsg"])) { ?>
                <div class="alert alert-danger" role="message"> <?= $updateValidationResult["errorMsg"] ?></div>
              <?php
                unset($updateValidationResult["errorMsg"]);
              } ?>
              <form action=<?= $_SERVER["PHP_SELF"] ?> method="POST">
                <div class="form-group <?php if (updateFieldsHasErrors("firstName") || updateFieldsHasErrors("lastName")) : ?> has-error <?php endif; ?>">
                  <div class="form-row">
                    <div class="col-md-6">
                      <div class="form-label-group">
                        <input type="hidden" name="ue_id" id="uID" class="form-control" value="<?= $e_id ?>">
                        <input type="text" name="uFirstName" id="ufirstName" class="form-control <?php if (updateFieldsHasErrors("firstName")) : ?> is-invalid <?php endif; ?>" placeholder="First name" required="required" autofocus="autofocus" value="<?= $f_name ?>">
                        <label for="uFirstName">First name</label>
                        <?php if (updateFieldsHasErrors("firstName")) : ?>
                          <span class="help-block"><?= $updateValidationResult["firstName"] ?></span>
                        <?php endif; ?>
                      </div>
                    </div><br>
                    <div class="col-md-6">
                      <div class="form-label-group">
                        <input type="text" id="ulastName" name="uLastName" class="form-control <?php if (updateFieldsHasErrors("lastName")) : ?> is-invalid <?php endif; ?>" placeholder="Last name" required="required" value="<?= $l_name ?>">
                        <label for="uLastName">Last name</label>

                        <?php if (updateFieldsHasErrors("lastName")) : ?>
                          <span class="help-block"><?= $updateValidationResult["lastName"] ?></span>
                        <?php endif; ?>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <!-- DropDown for Qualification -->
                  <label for="uqualification">Qualification</label>
                  <div class="form-label-group">
                    <select name="uqualification" id="uqualification" class="custom-select" required>
                      <option class="q-options" disabled selected>-- Select a Qualification --</option>
                      <?php
                      if ($uqualifications->num_rows > 0) {
                        while ($qual = $uqualifications->fetch_assoc()) {
                          ?>
                          <option class="qualifications" value="<?= $qual['qualification_id'] ?>"> <?= $qual['name'] ?> </option>
                      <?php
                        }
                      }
                      ?>
                    </select>
                  </div>
                </div>
                <div class="form-group  <?php if (updateFieldsHasErrors("salary")) : ?> has-error <?php endif; ?>">
                  <div class="form-row">
                    <div class="col-md-12">
                      <label for="usalary">Salary</label>
                      <input type="number" name="usalary" id="usalary" class="form-control <?php if (updateFieldsHasErrors("salary")) : ?> is-invalid <?php endif; ?>" placeholder="Salary" required="required" step="10000" min="10000" value="<?= $usalary ?>">

                      <?php if (updateFieldsHasErrors("salary")) : ?>
                        <span class="help-block"><?= $updateValidationResult["salary"] ?></span>
                      <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                      <label for="inputudob" class="label">DOB</label>
                      <input type="date" name="udob" id="udob" class="form-control" required="required" value="<?= $udob ?>">
                    </div>
                    <div class="col-md-6">
                      <label for="inputudob" class="label">Date Joined</label>
                      <input type="date" name="uDateJoined" id="udateJoined" class="form-control" placeholder="" required="required" value="<?= $date_joined ?>">
                    </div>
                  </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block" name="update_btn">Update</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<?php if ($show_update_modal) : ?>
  <script src="vendor/jquery/jquery.min.js"></script>
  <script>
    $(document).ready(function() {
      $("#updateModal").modal('show');
    });
  </script>
<?php endif; ?>