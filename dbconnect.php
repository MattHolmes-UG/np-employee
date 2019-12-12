<?php
$time_zone = 'Africa/Lagos';

$emp_db_con = mysqli_connect("localhost", "root", "", "employee_management");

if(!$emp_db_con) {
    echo "Error connecting to MySQL <br>";
    exit;
}
?>
