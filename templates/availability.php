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

$employee_id = $employee_data["id"];

//Get the employees availability
$cmd = "SELECT * FROM `availability` WHERE `employee_id` = ?";
$getAvailability = $dbh->prepare($cmd);
$success = $getAvailability->execute([$employee_id]);

//The days of the week
$weekdays = ["Saturday", "Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday"];


$row = $getAvailability->fetch();

//Remove the emplyee and availability id from the array
unset($row[0]);
unset($row[1]);
unset($row["id"]);
unset($row["employee_id"]);


//Create the table headers
$output = "<h2>My Availability</h2> <table> <thead> <tr> <th>Day</th> <th>From</th> <th>To</th> <th>Available</th> </tr> </thead> <tbody>";

//For every day of the week:
for ($i = 0; $i < 7; $i++) {


    $unavailable = "checked";

    //Store the start time for the current day
    $start = $row[strtolower($weekdays[$i]) . "_start"];

    //If the start time is blank(00:00:00), the employee is availble to start anytime
    if ($start == "00:00:00") {
        $start = "<input type='button' value='Open' disabled>";
    }
    //If the start time is not null
    else if ($start !== null) {
        $start = "<input  type=time value=" . $start . " disabled>";
    }
    //The employee is not available
    else {
        $start = "<button disabled value='null'>N/A</button>";
        $unavailable = "";
    }

    //Store the end time for the current day
    $end = $row[strtolower($weekdays[$i]) . "_end"];

    //If the start time is blank(00:00:00), the employee is availble to end anytime
    if ($end == "00:00:00") {
        $end = "<input type='button' value='Close' disabled>";
    }
    //If the end time is not null
    else  if ($end !== null) {
        $end = "<input type=time value=" . $end . " disabled>";
    }
    //The employee is not available
    else {
        $end = "<button disabled value='null'>N/A</button>";
        $unavailable = "";
    }

    $output .= '<tr> <td>' . $weekdays[$i] . '</td> <td id="' . $weekdays[$i] . '-start">' . $start . '</td> <td id="' . $weekdays[$i] . '-end">' . $end . '</td> <td><input id="' . $weekdays[$i] . '" type="checkbox" ' . $unavailable . ' disabled></td></tr>';
}
$output .=  "</tbody> </table>";

//Create the Edit and Save buttons
$output .= '<div class="button-container">
    <button id="edit-button">Edit</button>
    <button id="save-button" disabled>Save</button>
    </div>';

echo $output;
