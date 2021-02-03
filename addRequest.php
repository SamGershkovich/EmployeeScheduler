<?php
/*Author: Sam Gershkovich 000801766
  Date: December 02, 2020
 *I, Sam Gershkovich, 000801766 certify that this material is my original work.
 *No other person's work has been used without due acknowledgement.
 *
*/
include "connect.php";
include "kickToLogin.php";

$employee_id = $_SESSION["id"];

//Get and filter inputs
$startDate = filter_input(INPUT_GET, "start", FILTER_SANITIZE_SPECIAL_CHARS);
$endDate =  filter_input(INPUT_GET, "end", FILTER_SANITIZE_SPECIAL_CHARS);
$reason = filter_input(INPUT_GET, "reason", FILTER_SANITIZE_SPECIAL_CHARS);

//Insert a time off request
$cmd = "INSERT INTO `off_requests`(`employee_id`, `start_date`, `end_date`, `reason`) VALUES (?,?,?,?)";
$addRequest = $dbh->prepare($cmd);
$success = $addRequest->execute([$employee_id, $startDate, $endDate, $reason]);

if ($success === false) {
    echo "failed";
} else {
    echo "success";
}