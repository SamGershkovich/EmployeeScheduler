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
$startDate = filter_input(INPUT_GET, "start", FILTER_SANITIZE_SPECIAL_CHARS);
$endDate =  filter_input(INPUT_GET, "end", FILTER_SANITIZE_SPECIAL_CHARS);
$status = filter_input(INPUT_GET, "status", FILTER_VALIDATE_INT);

//If a manager has accepted or rejected the request, update the status
if ($status != null) {  
    $cmd = "UPDATE `off_requests` SET `status`= ? WHERE `id` = ?";
    $addRequest = $dbh->prepare($cmd);
    $success = $addRequest->execute([$status, $id]);
} 
//An employee has edited the request so update the request
else {
    $cmd = "UPDATE `off_requests` SET `start_date`= ?,`end_date`=? WHERE `id` = ?";
    $addRequest = $dbh->prepare($cmd);
    $success = $addRequest->execute([$startDate, $endDate, $id]);
}

if ($success === false) {
    echo "failed";
} else {
    echo "success";
}
