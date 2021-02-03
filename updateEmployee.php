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
$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
$position = filter_input(INPUT_GET, "position", FILTER_SANITIZE_SPECIAL_CHARS);

//A updated an employees position
$cmd = "UPDATE `employees` SET `position`= ? WHERE `id` = ?";
$updateEmployee = $dbh->prepare($cmd);
$success = $updateEmployee->execute([$position, $id]);

if ($success === false) {
    echo "failed";
} else {
    echo "success";
}