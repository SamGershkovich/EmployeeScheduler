<?php
/*Author: Sam Gershkovich 000801766
  Date: December 02, 2020
 *I, Sam Gershkovich, 000801766 certify that this material is my original work.
 *No other person's work has been used without due acknowledgement.
 *
*/
if (!isset($employee_data)) {
    include "connect.php";
    session_start();
    $employee_id = $_SESSION["id"];

    $cmd = "SELECT * FROM `employees` WHERE `id` = ?";
    $stmt = $dbh->prepare($cmd);
    $success = $stmt->execute([$employee_id]);

    $employee_data = $stmt->fetch();
}

//Get and filter inputs
$theDate = filter_input(INPUT_GET, "date", FILTER_SANITIZE_STRING);
$startTime = filter_input(INPUT_GET, "start", FILTER_SANITIZE_STRING);
$endTime = filter_input(INPUT_GET, "end", FILTER_SANITIZE_STRING);

//Get the day of the week for the date given
$theDate = strtotime($theDate);
$dayOfTheWeek = strtolower(date("l", $theDate));

//Get all employees in the department who do not have time off on the given date, and who are available between the start and end time given
$cmd = "SELECT e.id, e.name, o.start_date, o.end_date, o.status, a.saturday_start, a.saturday_end
            FROM employees e 
            LEFT JOIN off_requests o 
            ON e.id = o.employee_id 
            LEFT JOIN availability a
            ON e.id = a.employee_id
            WHERE e.department = ? 
            AND ((o.status IS NULL) 
            OR (o.status >= 0 AND ? NOT BETWEEN o.start_date AND o.end_date)) 
            AND(a.".$dayOfTheWeek."_start IS NOT NULL AND a.".$dayOfTheWeek."_end IS NOT NULL)
            AND  (( ? = '00:00:00' AND ? = '00:00:00') OR ((? >= a.".$dayOfTheWeek."_start) AND (? <= a.".$dayOfTheWeek."_end OR a.".$dayOfTheWeek."_end = '00:00:00' OR ? = '00:00:00')))
            GROUP BY e.name
            ORDER BY e.id";

$getAvailableEmployees = $dbh->prepare($cmd);

$success = $getAvailableEmployees->execute([$employee_data["department"], date("Y-m-d", $theDate), $startTime, $endTime, $startTime, $endTime, $endTime]);

$output = "";
$options = array();

while ($row = $getAvailableEmployees->fetch()) {
    $options[] = $row;
}

//Create the options for the available employee select element
$output = "<option value=''> - Select Employee - </option>";

for ($i = 0; $i < count($options); $i++) {
    $output .= "<option value='" . $options[$i]["id"] . "'>" . $options[$i]["name"] . "</option>";
}

echo $output;
