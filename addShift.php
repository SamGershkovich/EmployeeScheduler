<?php
/*Author: Sam Gershkovich 000801766
  Date: December 02, 2020
 *I, Sam Gershkovich, 000801766 certify that this material is my original work.
 *No other person's work has been used without due acknowledgement.
 *
*/
include "connect.php";
include "kickToLogin.php";


//Get and filter the inputs
$newEmployeeId = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
$date =  filter_input(INPUT_GET, "date", FILTER_SANITIZE_SPECIAL_CHARS);
$startTime = filter_input(INPUT_GET, "start", FILTER_SANITIZE_SPECIAL_CHARS);
$endTime = filter_input(INPUT_GET, "end", FILTER_SANITIZE_SPECIAL_CHARS);

//Add a shift
$cmd = "INSERT INTO `shifts`(`employee_id`, `date`, `start_time`, `end_time`) VALUES (?,?,?,?)";
$addShift = $dbh->prepare($cmd);
$success = $addShift->execute([$newEmployeeId, $date, $startTime, $endTime]);

if ($success === false) {
    echo "failed";
} else {
    echo "success";
}
