<?php
/*Author: Sam Gershkovich 000801766
  Date: December 02, 2020
 *I, Sam Gershkovich, 000801766 certify that this material is my original work.
 *No other person's work has been used without due acknowledgement.
 *
*/
include "connect.php";
include "kickToLogin.php";

//Get and filter the input
$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

//Delete an employee
$cmd = "DELETE FROM `employees` WHERE `id` = ?";
$deleteEmployee = $dbh->prepare($cmd);
$success = $deleteEmployee->execute([$id]);

if ($success === false) {
    echo "failed";
} else {
    echo "success";
}