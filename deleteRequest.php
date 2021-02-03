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

//Delete a request
$cmd = "DELETE FROM `off_requests` WHERE `id` = ?";
$deleteRequest = $dbh->prepare($cmd);
$success = $deleteRequest->execute([$id]);

if ($success === false) {
    echo "failed";
} else {
    echo "success";
}