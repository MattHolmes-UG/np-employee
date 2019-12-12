<?php
require './auth.php';
require './db_inc.php';
require './dbconnect.php';

// $de_id = $_POST["de_id"] ?? "";
if (isset($_POST['delete_btn'])) {
  $q = "DELETE FROM `employee` WHERE employee_id=" . $_POST["de_id"];
  $x = mysqli_query($emp_db_con, $q);
  
  if ($x == 1) {
    $_SESSION["message"] = "Deleted successfully!";
    header("Location: index.php");
  } else {
  /* TODO: Error report */ }
  
  mysqli_close($emp_db_con);
}


// $employee = new Employee();
// if (isset($_POST['delete_btn'])) {
//   try {
//     $initializedEmployee = $employee->initializeEmployee($e_id);
//   } catch (Exception $e) {
//     $deleteActionResult = array("errorMsg" => "Error connecting to database");
//   }
//   if (!is_null($initializedEmployee->id)) {
//     try {
//       $initializedEmployee->deleteEmployee();
//       $_SESSION["message"] = "Deleted successfully!";
//     } catch (Exception $e) {
//       $deleteActionResult = array("errorMsg" => "Failed to delete record. Please try again.");
//     }
//     header("Refresh:0");
//   }
// }
?>

