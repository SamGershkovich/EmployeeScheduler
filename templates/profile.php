<?php
/*Author: Sam Gershkovich 000801766
  Date: December 02, 2020
 *I, Sam Gershkovich, 000801766 certify that this material is my original work.
 *No other person's work has been used without due acknowledgement.
 *
*/

//If the employee data array is not set, connect, start the session, and then get the employee data
if (!isset($employee_data)) {
    include "../connect.php";
    session_start();
    $employee_id = $_SESSION["id"];

    $cmd = "SELECT * FROM `employees` WHERE `id` = ?";
    $stmt = $dbh->prepare($cmd);
    $success = $stmt->execute([$employee_id]);
    
    $employee_data = $stmt->fetch();
}

//Get the position of the employee
$cmd = "SELECT * FROM `positions` WHERE `id` = ?";
$getPositions = $dbh->prepare($cmd);
$success = $getPositions->execute([$employee_data["position"]]);

//Create the profile info view
$output = "<h2>Profile</h2></i>";
$output .= "<div><span>Name:</span> " . $employee_data["name"] . "</div>";
$output .= "<div><span>Department:</span> " . $employee_data["department"] . "</div>";
$output .= "<div><span>Position:</span> " . $getPositions->fetch()["name"] . "</div>";

echo $output;