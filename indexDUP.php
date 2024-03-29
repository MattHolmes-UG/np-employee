<?php
require 'dbconnect.php';

$sql = "SELECT * FROM employee JOIN qualification ON employee.qualification_id=qualification.qualification_id";
$sql_qual = "SELECT * FROM qualifications";
$qualifications = $emp_db_con->query($sql_qual);
$stmt = $emp_db_con->query($sql);
$update = $emp_db_con->query($sql);
$qualification = $emp_db_con->query($sql_qual);

print_r($_COOKIE);
?>


<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>SB Admin - Dashboard</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
  <!-- Page level plugin CSS-->
  <link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">

</head>

<body id="page-top">

  <nav class="navbar navbar-expand navbar-dark bg-dark static-top">

    <a class="navbar-brand mr-1" href="index.html">Todo List</a>

    <button class="btn btn-link btn-sm text-white order-1 order-sm-0" id="sidebarToggle" href="#">
      <i class="fas fa-bars"></i>
    </button>

    <!-- Navbar Search -->
    <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
      <div class="input-group">
        <input type="text" class="form-control" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
        <div class="input-group-append">
          <button class="btn btn-primary" type="button">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </form>

    <!-- Navbar -->
    <ul class="navbar-nav ml-auto ml-md-0">
      <li class="nav-item dropdown no-arrow">
        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <?= $_SESSION["userData"]["username"] ?> <i class="fas fa-user-circle fa-fw"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
          <a class="dropdown-item" href="#">Settings</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">Logout</a>
        </div>
      </li>
    </ul>

  </nav>

  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="sidebar navbar-nav">
      <li class="nav-item active">
        <a class="nav-link" href="index.html">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span>
        </a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-fw fa-folder"></i>
          <span>Todos</span>
        </a>
        <div class="dropdown-menu" aria-labelledby="pagesDropdown">
          <a class="dropdown-item" href="login.html">Login</a>
          <a class="dropdown-item" href="register.html">Register</a>
          <a class="dropdown-item" href="forgot-password.html">Forgot Password</a>
          <div class="dropdown-divider"></div>
          <h6 class="dropdown-header">Other Pages:</h6>
          <a class="dropdown-item" href="404.html">404 Page</a>
          <a class="dropdown-item" href="blank.html">Blank Page</a>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="charts.html">
          <i class="fas fa-fw fa-chart-area"></i>
          <span>Charts</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="tables.html">
          <i class="fas fa-fw fa-table"></i>
          <span>Tables</span></a>
      </li>
    </ul>

    <div id="content-wrapper">

      <div class="container-fluid">

        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="#">Dashboard</a>
          </li>
          <li class="breadcrumb-item active">Overview</li>
        </ol>

        <!-- Icon Cards-->
        <div class="row">
          <div class="col-xl-3 col-sm-6 mb-3">
            <div class="card text-white bg-primary o-hidden h-100">
              <div class="card-body">
                <div class="card-body-icon">
                  <i class="fas fa-fw fa-comments"></i>
                </div>
                <div class="mr-5">26 New Messages!</div>
              </div>
              <a class="card-footer text-white clearfix small z-1" href="#">
                <span class="float-left">View Details</span>
                <span class="float-right">
                  <i class="fas fa-angle-right"></i>
                </span>
              </a>
            </div>
          </div>
          <div class="col-xl-3 col-sm-6 mb-3">
            <div class="card text-white bg-warning o-hidden h-100">
              <div class="card-body">
                <div class="card-body-icon">
                  <i class="fas fa-fw fa-list"></i>
                </div>
                <div class="mr-5">11 New Tasks!</div>
              </div>
              <a class="card-footer text-white clearfix small z-1" href="#">
                <span class="float-left">View Details</span>
                <span class="float-right">
                  <i class="fas fa-angle-right"></i>
                </span>
              </a>
            </div>
          </div>
          <div class="col-xl-3 col-sm-6 mb-3">
            <div class="card text-white bg-success o-hidden h-100">
              <div class="card-body">
                <div class="card-body-icon">
                  <i class="fas fa-fw fa-shopping-cart"></i>
                </div>
                <div class="mr-5">123 New Orders!</div>
              </div>
              <a class="card-footer text-white clearfix small z-1" href="#">
                <span class="float-left">View Details</span>
                <span class="float-right">
                  <i class="fas fa-angle-right"></i>
                </span>
              </a>
            </div>
          </div>
          <div class="col-xl-3 col-sm-6 mb-3">
            <div class="card text-white bg-danger o-hidden h-100">
              <div class="card-body">
                <div class="card-body-icon">
                  <i class="fas fa-fw fa-life-ring"></i>
                </div>
                <div class="mr-5">13 New Tickets!</div>
              </div>
              <a class="card-footer text-white clearfix small z-1" href="#">
                <span class="float-left">View Details</span>
                <span class="float-right">
                  <i class="fas fa-angle-right"></i>
                </span>
              </a>
            </div>
          </div>
        </div>

        <!-- Area Chart Example-->
        <div class="card mb-3">
          <div class="card-header">
            <i class="fas fa-chart-area"></i>
            Area Chart Example</div>
          <div class="card-body">
            <canvas id="myAreaChart" width="100%" height="30"></canvas>
          </div>
          <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
        </div>

        <!-- DataTables Example -->
        <div class="card mb-3">
          <div class="card-header">
            <i class="fas fa-table"></i>
            Data Table Example

            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#addModal" style="float: right; width: 2em;"><i class="fa fa-plus"></i></a>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Qualification</th>
                    <th>Age</th>
                    <th>Start date</th>
                    <th>Salary</th>
                    <th colspan="2" class="text-align-center">Actions</th>
                  </tr>
                </thead>
                <tfoot>
                  <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Qualification</th>
                    <th>Age</th>
                    <th>Start date</th>
                    <th>Salary</th>
                    <th colspan="2">Actions</th>
                  </tr>
                </tfoot>
                <tbody>
                  <?php
                  if ($stmt->num_rows > 0) {
                    while ($row = $stmt->fetch_assoc()) {

                      ?>
                      <tr>
                        <td><?= $row['employee_id'] ?></td>
                        <td><?= $row['first_name'] ?> <?= $row['last_name'] ?></td>
                        <td><?= $row['qualification_name'] ?></td>
                        <td><?= date('Y') - substr($row['dob'], 0, 4) ?></td>
                        <td><?= substr($row['date_joined'], 0, 10) ?></td>
                        <td>$<?= $row['salary'] ?></td>
                        <td style="text-align: center;">
                          <a class="dropdown-item" href="#" data-toggle="modal" data-target="#updateModal<?= $row["employee_id"] ?>"><i class="fa fa-edit"></i></a>
                        </td>
                        <td style="text-align: center;">
                          <a href="delete.php?del=<?= $row['employee_id'] ?>" class="dropdown-item"><i class="fa fa-trash-o"></i></a>
                        </td>
                      </tr>
                  <?php
                    }
                  } else {
                    echo "<em>No records yet. Click the add button insert a new record.</em> </br>";
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
          <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
        </div>

      </div>
      <!-- /.container-fluid -->

      <!-- Sticky Footer -->
      <footer class="sticky-footer">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright © Your Website 2019</span>
          </div>
        </div>
      </footer>

    </div>
    <!-- /.content-wrapper -->

  </div>
  <!-- /#wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a class="btn btn-primary" href="login.html">Logout</a>
        </div>
      </div>
    </div>
  </div>
  <!-- Update Modal-->
  <?php
  if ($update->num_rows > 0) {
    while ($row = $update->fetch_assoc()) {
      ?>
      <div class="modal fade" id="updateModal<?= $row["employee_id"] ?>" tabindex="-1" role="dialog" aria-labelledby="updateEmployeeModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="updateEmployeeModal">Update Employee</h5>
              <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="container">
                <div class="card card-register mx-auto mt-5">
                  <!-- <div class="card-header">Register an Account</div> -->
                  <div class="card-body">
                    <form action="updateEmployee.php" method="POST">
                      <div class="form-group has-error">
                        <div class="form-row">
                          <div class="col-md-6">
                            <div class="form-label-group">
                              <input type="text" value="<?= $row["first_name"] ?>" name="firstName" id="firstName" class="form-control" placeholder="First name" required="required" autofocus="autofocus">
                              <label for="firstName">First name</label>
                            </div>
                          </div><br>
                          <div class="col-md-6">
                            <div class="form-label-group">
                              <input type="text" value="<?= $row["last_name"] ?>" id="lastName" name="lastName" class="form-control" placeholder="Last name" required="required">
                              <label for="lastName">Last name</label>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="form-group">
                        <!-- DropDown for Qualification -->
                        <label for="qualification">Qualification</label>
                        <div class="form-label-group">
                          <select name="qual" id="qual" class="custom-select">
                            <option class="" disabled selected>-- Select a Qualification --</option>
                            <?php
                                if ($qualifications->num_rows > 0) {
                                  while ($qual = $qualifications->fetch_assoc()) {
                                    ?>
                                <option class="" value="<?= $qual['qualification_id'] ?>"> <?= $qual['qualification_name'] ?> </option>
                            <?php
                                  }
                                }
                                ?>
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="form-row">
                          <div class="col-md-12">
                            <label for="salary">Salary</label>
                            <input type="number" value="<?= $row["salary"] ?>" name="salary" id="salary" class="form-control" placeholder="Salary" required="required" step="10000" min="10000">
                          </div>
                          <div class="col-md-6">
                            <label for="inputdob" class="label">DOB</label>
                            <input type="date" value="<?= substr($row['dob'], 0, 10) ?>" name="dob" id="inputdob" class="form-control" required="required">
                          </div>
                          <div class="col-md-6">
                            <label for="datejoined" class="label">Date Joined</label>
                            <input type="date" value="<?= substr($row['date_joined'], 0, 10) ?>" name="date_joined" id="datejoined" class="form-control" placeholder="" required="required">
                          </div>
                        </div>
                      </div>
                  </div>

                  <button type="submit" class="btn btn-primary btn-block" name="update_btn">Update</button>
                  <!-- <button name="registerBtn" type="submit">Register</button> -->
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      </div>
  <?php
    }
  }
  ?>
  </div>
  <?php
  include("addNewEmployee.php");
  ?>




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
                <form action="addNewEmployee.php" method="POST">
                  <div class="form-group has-error">
                    <div class="form-row">
                      <div class="col-md-6">
                        <div class="form-label-group">
                          <input type="text" name="firstName" id="firstName" class="form-control" placeholder="First name" required="required" autofocus="autofocus">
                          <label for="firstName">First name</label>
                        </div>
                      </div><br>
                      <div class="col-md-6">
                        <div class="form-label-group">
                          <input type="text" id="lastName" name="lastName" class="form-control" placeholder="Last name" required="required">
                          <label for="lastName">Last name</label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <!-- DropDown for Qualification -->
                    <label for="qualification">Qualification</label>
                    <div class="form-label-group">
                      <select name="qual" id="qual" class="custom-select">
                        <option class="" disabled selected>-- Select a Qualification --</option>
                        <?php
                        if ($qualification->num_rows > 0) {
                          while ($qual = $qualification->fetch_assoc()) {
                            ?>
                            <option class="" value="<?= $qual['qualification_id'] ?>"> <?= $qual['qualification_name'] ?> </option>
                        <?php
                          }
                        }
                        ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-row">
                      <div class="col-md-12">
                        <label for="salary">Salary</label>
                        <input type="number" name="salary" id="salary" class="form-control" placeholder="Salary" required="required" step="10000" min="10000">
                      </div>
                      <div class="col-md-6">
                        <label for="inputdob" class="label">DOB</label>
                        <input type="date" name="dob" id="inputdob" class="form-control" required="required">
                      </div>
                      <div class="col-md-6">
                        <label for="inputdob" class="label">Date Joined</label>
                        <input type="date" name="dob" id="inputdob" class="form-control" placeholder="" required="required">
                      </div>
                    </div>
                  </div>
              </div>

              <button type="submit" class="btn btn-primary btn-block" name="add_btn">Add</button>
              <!-- <button name="registerBtn" type="submit">Register</button> -->
              </form>
              <!-- <div class="text-center">
          <a class="d-block small mt-3" href="login.html">Login Page</a>
          <a class="d-block small" href="forgot-password.html">Forgot Password?</a>
        </div> -->
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
        <a class="btn btn-primary" href="login.html">Logout</a>
      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Page level plugin JavaScript-->
  <script src="vendor/chart.js/Chart.min.js"></script>
  <script src="vendor/datatables/jquery.dataTables.js"></script>
  <script src="vendor/datatables/dataTables.bootstrap4.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin.min.js"></script>

  <!-- Demo scripts for this page-->
  <script src="js/demo/datatables-demo.js"></script>
  <script src="js/demo/chart-area-demo.js"></script>

</body>

</html>