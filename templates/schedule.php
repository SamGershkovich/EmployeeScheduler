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

$can_edit = $employee_data["position"] <= 2;

//The days of the week
$weekdays = ["Saturday", "Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday"];

date_default_timezone_set("America/Toronto");

//Get and filter inputs
$shiftDirection = filter_input(INPUT_GET, "direction", FILTER_SANITIZE_STRING);
$displayPeriod = filter_input(INPUT_GET, "period", FILTER_SANITIZE_STRING);
$theDate = filter_input(INPUT_GET, "date", FILTER_SANITIZE_STRING);


//If a date was provided, override the period shift
if ($theDate != "") {
    $shiftDirection = null;
    $_SESSION["periodShift"] = 0;
}

//If the periodShift is not set, default to 0
if (!isset($_SESSION["periodShift"])) {
    $_SESSION["periodShift"] = 0;
}

//If the display period is not set, default to week
if (!isset($_SESSION["scheduleDisplayPeriod"])) {
    $_SESSION["scheduleDisplayPeriod"] = "week";
}

//If the date is not set and a date was provided default to the date provided
if (!isset($_SESSION["theDate"]) && $theDate != "") {
    $_SESSION["theDate"] = $theDate;
}

//If no date was provided and there is a date stored, use the date stored
if ($theDate == "" && isset($_SESSION["theDate"])) {
    $theDate = $_SESSION["theDate"];
}

//If a proper display period was not provided, default to the one in session
if ($displayPeriod != "week" && $displayPeriod != "day") {
    $displayPeriod = $_SESSION["scheduleDisplayPeriod"];
}

//How many weeks to shift
$periodShift = $_SESSION["periodShift"];

//Set the amount to shift based on the direction given
if ($shiftDirection == "forward") {
    $periodShift++;
} else if ($shiftDirection == "back") {
    $periodShift--;
} else if ($shiftDirection == "now") {
    $periodShift = 0;
    $theDate = "";
}

//Save the state of the current date view
$_SESSION["scheduleDisplayPeriod"] = $displayPeriod;
$_SESSION["periodShift"] = $periodShift;
$_SESSION["theDate"] = $theDate;


//Get the date of the last saturday
if ($theDate == "") {
    $thisWeekSaturday = strtotime("last saturday");
    $thisWeekSaturday = date('w', $thisWeekSaturday) == date('w') ? $thisWeekSaturday + 6 * 86400 : $thisWeekSaturday;
} else {
    $thisWeekSaturday = strtotime("last saturday", strtotime($theDate));
    $thisWeekSaturday = date('w', $thisWeekSaturday) == date('w', strtotime($theDate)) ? $thisWeekSaturday + 7 * 86400 : $thisWeekSaturday;
}

//Shift the saturday 
if ($periodShift > 0) {

    $thisWeekSaturday = strtotime(date("Y-m-d", $thisWeekSaturday) . " +" . (($periodShift) * 7) .  " days");
} else if ($periodShift < 0) {

    $thisWeekSaturday = strtotime(date("Y-m-d", $thisWeekSaturday) . " +" . (($periodShift) * 7) .  " days");
}

//Get the friday of this week
$thisWeekFriday = strtotime(date("Y-m-d", $thisWeekSaturday) . " +6 days");

//The start and end dates for this week
$weekStartDate = date("M. j\<\s\u\p\>S\<\/\s\u\p\>", $thisWeekSaturday);
$weekEndDate = date("M. j\<\s\u\p\>S\<\/\s\u\p\>", $thisWeekFriday);

$output = "";

//Week display mode
if ($displayPeriod == "week") {

    $output = "<h2>My Schedule</h2>";

    //The date controls
    $output .= "<div id='date-bar'> <span id='date-select'> <button id='back'><i class='fas fa-arrow-left'></i></button> <span id='date-range'>" . $weekStartDate . " &#8212; " . $weekEndDate . "</span> <button id='forward'><i class='fas fa-arrow-right'></i></button> </span> <button id='this-week'>This Week</button></div>";

    //Get all the users shifts that are within the start and end dates of the current week
    $cmd = "SELECT * FROM `shifts` WHERE `employee_id` = ? AND `date` between ? and ? ORDER BY `date`, `start_time`";
    $getShifts = $dbh->prepare($cmd);
    $success = $getShifts->execute([$employee_data["id"], date("Y-m-d", $thisWeekSaturday), date("Y-m-d", $thisWeekFriday)]);

    //Build the table
    $output .= "<div id='schedule-grid'>";

    //Store shifts in array
    $shifts = [];
    while ($row = $getShifts->fetch()) {
        $shifts[] = $row;
    }

    //For every day of the week
    for ($i = 0; $i < 7; $i++) {

        //Create the day slot's id
        $slotId = date("Y-m-d", strtotime(date("Y-m-d", $thisWeekSaturday) . " +" . $i . " days"));

        $output .= "<div class='day-slot' id='" . $slotId . "'>";

        $daySlotCreated = false;

        //The date to display in the day slot
        $slotDate = date("M. \- j\<\s\u\p\>S\<\/\s\u\p\>", strtotime(date("Y-m-d", $thisWeekSaturday) . " +" . $i . " days"));

        //If there are shifts on this day
        if (count($shifts) > 0) {

            $date = strtotime($shifts[0]["date"]);

            //Get the day of the week
            $dayOfWeek = date("l", $date);

            //If the day of the week of the shift is equal to the current weekday
            if ($dayOfWeek == $weekdays[$i]) {

                $output .= "<span class='day'><p class='weekday'>" . $weekdays[$i] . "</p> <p class='date'>" . $slotDate . "</p> </span><span class='time-slot'><span class='time-block'><input type='time' class='time-start' value='" . $shifts[0]["start_time"] . "' disabled><input type='time' class='time-end' value='" . $shifts[0]["end_time"] . "' disabled></span></span>";

                //Remove the shift from the array and re-index the array
                unset($shifts[0]);
                $shifts = array_values($shifts);
                $daySlotCreated = true;
            }
        }
        //If a day slot has not yet been created, create an empty one
        if (!$daySlotCreated) {
            $output .= "<span class='day'><p i class='weekday'>" . $weekdays[$i] . "</p> <p class='date'>" . $slotDate . "</p> </span><span class='time-slot'></span>";
        }

        $output .= "</div>";
    }

    $output .= "</div>";
}

//Day display mode
if ($displayPeriod == "day") {

    //Get the date of the day
    if ($theDate != "") {
        $day = strtotime($theDate . " +" . ($periodShift) .  " days");
    } else {
        $day = strtotime(date("Y-m-d") . " +" . ($periodShift) .  " days");
    }

    //The date to display
    $today = "<span id='" . date("Y-m-d", $day) . "' class='day-view-date'>" . date("D M. ", $day) . date("j", $day) . "<sup>" .  date("S", $day) . "</sup></span>";

    //If the user has admin permissions
    if ($can_edit) {

        //Get the day of the week for the day
        $dayOfTheWeek = strtolower(date("l", $day));

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
            AND(a." . $dayOfTheWeek . "_start IS NOT NULL AND a." . $dayOfTheWeek . "_end IS NOT NULL)
            AND  (( ? = '00:00:00' AND ? = '00:00:00') OR ((? >= a." . $dayOfTheWeek . "_start) AND (? <= a." . $dayOfTheWeek . "_end OR a." . $dayOfTheWeek . "_end = '00:00:00' OR ? = '00:00:00')))
            GROUP BY e.name
            ORDER BY e.id";

        $getAvailableEmployees = $dbh->prepare($cmd);

        //Set the start and end times so all employees available today will be shown when first loaded
        $startTime = '00:00:00';
        $endTime = '00:00:00';

        $success = $getAvailableEmployees->execute([$employee_data["department"], date("Y-m-d", $day), $startTime, $endTime, $startTime, $endTime, $endTime]);

        $availableEmployees = array();

        //Create the employee select
        $employeeSelect = "<select id='available-employees' required><option value=''> - Select Employee - </option>";

        //Create an array of the available employees
        while ($row = $getAvailableEmployees->fetch()) {
            $availableEmployees[] = $row;
        }

        //For every available employee
        for ($i = 0; $i < count($availableEmployees); $i++) {
            //Create an option
            $employeeSelect .= "<option value='" . $availableEmployees[$i]["id"] . "'>" . $availableEmployees[$i]["name"] . "</option>";
        }

        $employeeSelect .= "</select>";

        //Create the New shift form
        $output .= "<div id='new-shift-container'>
        <h2>New Shift</h2> 
        <form id='new-shift-form' name='new-shift-form'>
        <label>
            <span>Start Time</span>
            <input  id='new-shift-start-time' type='time' placeholder='Start Time' required>
        </label>
        <label>
            <span>End Time</span>
            <input id='new-shift-end-time' type='time' placeholder='End Time' required>
        <label>
        ";

        //Add the employee select element to the form
        $output .= $employeeSelect . "<input type='submit' value='Add Shift'></form></div>";
    }

    $output .= "<h2>" . $employee_data["department"] . " Schedule</h2>";

    //Create the date controls
    $output .= "<div id='date-bar'> <span id='date-select'> <button id='back'><i class='fas fa-arrow-left'></i></button> <span id='date-range'>" . $today . "</span> <button id='forward'><i class='fas fa-arrow-right'></i></button> </span> <button id='this-week'>Today</button><button id='week-view'>Week View</button> </div>";


    //Get all the shifts for the day
    $cmd = "SELECT s.id AS id, s.date, s.start_time, s.end_time, e.name 'name', p.name 'position'
    FROM `shifts` s 
    JOIN `employees` e 
    on e.id = s.employee_id
    JOIN `positions` p
    ON e.position = p.id
    WHERE e.department = ? AND `date` = ? ORDER BY `start_time`, p.id";

    $getShifts = $dbh->prepare($cmd);
    $success = $getShifts->execute([$employee_data["department"], date("Y-m-d", $day)]);

    //Build the table
    $output .= "<div id='schedule-grid'>";

    //For every shift:
    while ($row = $getShifts->fetch()) {
        $output .= "<div class='day-slot'>";

        //The employee name and position display
        $output .= "<span class='day'>
                        <p class='weekday'>" . substr($row["name"], 0, strpos($row["name"], " ") + 2) . ".</p> 
                        <p class='date'>" . $row["position"] . "</p>";

        //If the user has admin permissions
        if ($can_edit) {

            //Add a delete icon to the row
            $output .= "<i class='fas fa-trash-alt delete-shift' id='shift-" . $row["id"] . "'></i>";
        }

        //Add time start and end inputs that will be used to show the shift on the time slot 
        $output .=  "</span>
                    <span class='time-slot'>
                        <span class='time-block'>
                            <input type='time' class='time-start' value='" . $row["start_time"] . "' disabled>
                            <input type='time' class='time-end' value='" . $row["end_time"] . "' disabled>
                        </span>
                    </span>";

        $output .= "</div>";
    }

    $output .= "</div>";
}
echo $output;
