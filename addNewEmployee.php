<?php
require './db_inc.php';


$employee = new Employee();

$qualifications = $employee->getQualifications();

$f_name = $_POST["firstName"] ?? "";
$l_name = $_POST["lastName"] ?? "";
$dob = $_POST["dob"] ?? "";
$date_joined = $_POST["dateJoined"] ?? "";
$salary = $_POST["salary"] ?? "";
$qualification = $_POST["qualification"] ?? "";

$show_add_modal = false;
$addValidationResult = array();

if (isset($_POST['add_btn'])) {

  try {
    $addValidationResult = $employee->addEmployee($f_name, $l_name, $salary, $qualification, $dob, $date_joined);
  } catch (Exception $e) {
    echo "Error occurred connecting to server.";
  }

  if (!$addValidationResult["hasErrors"]) {
    $_SESSION["message"] = "New record added successfully!";
  } else {
    $show_add_modal = true;
  }
}


function fieldHasErrors(string $fieldName): bool
{
  global $addValidationResult;
  if (isset($addValidationResult["hasErrors"]) && $addValidationResult['hasErrors'] && array_key_exists($fieldName, $addValidationResult)) {
    return true;
  }
  return false;
}

?>

<!-- Add new Employee Modal-->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addEmployeeModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addEmployeeModal">Add A New Employee</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container">
          <div class="card card-register mx-auto mt-5">
            <!-- <div class="card-header">Register an Account</div> -->
            <div class="card-body">
              <form action=<?= $_SERVER["PHP_SELF"] ?> method="POST">
                <div class="form-group <?php if (fieldHasErrors("firstName") || fieldHasErrors("lastName")) : ?> has-error <?php endif; ?>">
                  <div class="form-row">
                    <div class="col-md-6">
                      <div class="form-label-group">
                        <input type="text" name="firstName" id="firstName" class="form-control <?php if (fieldHasErrors("firstName")) : ?> is-invalid <?php endif; ?>" placeholder="First name" required="required" autofocus="autofocus" value="<?= $f_name ?>">
                        <label for="firstName">First name</label>
                        <?php if (fieldHasErrors("firstName")) : ?>
                          <span class="help-block"><?= $addValidationResult["firstName"] ?></span>
                        <?php endif; ?>
                      </div>
                    </div><br>
                    <div class="col-md-6">
                      <div class="form-label-group">
                        <input type="text" id="lastName" name="lastName" class="form-control <?php if (fieldHasErrors("lastName")) : ?> is-invalid <?php endif; ?>" placeholder="Last name" required="required" value="<?= $l_name ?>">
                        <label for="lastName">Last name</label>

                        <?php if (fieldHasErrors("lastName")) : ?>
                          <span class="help-block"><?= $addValidationResult["lastName"] ?></span>
                        <?php endif; ?>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <!-- DropDown for Qualification -->
                  <label for="qualification">Qualification</label>
                  <div class="form-label-group">
                    <select name="qualification" id="qualification" class="custom-select" required>
                      <option class="" disabled selected>-- Select a Qualification --</option>
                      <?php
                      if (is_array($qualifications) && count($qualifications) > 0) {
                        foreach ($qualifications as $qualification) {
                          ?>
                          <option class="" value="<?= $qualification["qualification_id"] ?>">"<?= $qualification["name"] ?>"</option>
                      <?php
                        }
                      }
                      ?>
                    </select>
                  </div>
                </div>
                <div class="form-group  <?php if (fieldHasErrors("salary")) : ?> has-error <?php endif; ?>">
                  <div class="form-row">
                    <div class="col-md-12">
                      <label for="salary">Salary</label>
                      <input type="number" name="salary" id="salary" class="form-control <?php if (fieldHasErrors("salary")) : ?> is-invalid <?php endif; ?>" placeholder="Salary" required="required" step="10000" min="10000" value="<?= $salary ?>">

                      <?php if (fieldHasErrors("salary")) : ?>
                        <span class="help-block"><?= $addValidationResult["salary"] ?></span>
                      <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                      <label for="inputdob" class="label">DOB</label>
                      <input type="date" name="dob" id="inputdob" class="form-control" required="required" value="<?= $dob ?>">
                    </div>
                    <div class="col-md-6">
                      <label for="inputdob" class="label">Date Joined</label>
                      <input type="date" name="dateJoined" id="input_date_joined" class="form-control" placeholder="" required="required" value="<?= $date_joined ?>">
                    </div>
                  </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block" name="add_btn">Add</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php if ($show_add_modal) : ?>
  <script src="vendor/jquery/jquery.min.js"></script>
  <script>
    $(document).ready(function() {
      $("#addModal").modal('show');
    });
  </script>

<?php endif; ?>