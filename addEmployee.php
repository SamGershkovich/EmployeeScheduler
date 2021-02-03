<?php
/*Author: Sam Gershkovich 000801766
  Date: December 02, 2020
 *I, Sam Gershkovich, 000801766 certify that this material is my original work.
 *No other person's work has been used without due acknowledgement.
 *
*/
include "connect.php";
include "kickToLogin.php";

//Get and filter inputs
$name = filter_input(INPUT_GET, "name", FILTER_SANITIZE_SPECIAL_CHARS);
$number =  filter_input(INPUT_GET, "number", FILTER_VALIDATE_INT);
$position = filter_input(INPUT_GET, "position", FILTER_VALIDATE_INT);
$department = filter_input(INPUT_GET, "department", FILTER_SANITIZE_SPECIAL_CHARS);

//Add an employee
$cmd = "INSERT INTO `employees`(`employee_number`, `name`, `position`, `department`) VALUES (?,?,?,?)";
$addEmployee = $dbh->prepare($cmd);
$success = $addEmployee->execute([$number, $name, $position, $department]);

//Get the new employees id
$cmd = "SELECT `id` FROM `employees` WHERE `employee_number` = ?";
$getNewEmployeeId = $dbh->prepare($cmd);
$success = $getNewEmployeeId->execute([$number]);

$newEmployeeId = $getNewEmployeeId->fetch()["id"];

//Create an availaibility record for the employee
$cmd = "INSERT INTO `availability`(`employee_id`) VALUES (?)";
$addEmployeeAvailability = $dbh->prepare($cmd);
$success = $addEmployeeAvailability->execute([$newEmployeeId]);

if ($success === false) {
    echo "failed";
} else {
    echo "success";
}