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

//Whether user has admin permissions
$can_edit = $employee_data["position"] <= 2;

$employee_id = $employee_data["id"];

//If the user has admin permissions
if ($can_edit) {

    //Get all the time off requests for the employees in this department where the requested date has not passed yet
    $cmd = "SELECT o.id, o.employee_id, o.start_date, o.end_date, o.reason, o.status, e.name 'name', p.name 'position'
        FROM `off_requests` o 
        JOIN `employees` e 
        on e.id = o.employee_id
        JOIN `positions` p
        ON e.position = p.id
        WHERE e.department = ? AND o.end_date >= (SELECT CURDATE())";
    $getRequests = $dbh->prepare($cmd);
    $success = $getRequests->execute([$employee_data["department"]]);
} 
//Otherwise just get this employees request
else {
    $cmd = "SELECT * FROM `off_requests` WHERE `employee_id` = ? AND `end_date` >= (SELECT CURDATE())";
    $getRequests = $dbh->prepare($cmd);
    $success = $getRequests->execute([$employee_id]);
}

$output = "";

//Build the new time off request form
$output .= "<h2> New Time Off Requests</h2> 
    <form id='new-request-form' name='new_request_form'>
        <label><span>Start Date</span><input name='start' type='date' placeholder='Start date' required>
        </label>
        <label><span>End Date</span><input name='end' type='date' placeholder='End date' required>
        </label>
        <input name='reason' type='text' placeholder='Reason' maxlength='30' required>
        <input type='submit' value='Add request'>
    </form>";


//The time off request table
$output .= "<h2>Current Requests</h2> <table> <thead> <tr> <th> Start Date </th> <th> End Date </th> <th> Reason </th> <th> Status </th> <th> Edit </th></tr> </thead> <tbody>";


//For every request
while ($row = $getRequests->fetch()) {
    
    //Get the status
    $status = $row["status"];   
    switch ($status) {
        case "-1":
            $status = "Rejected";
            break;
        case "0":
            $status = "Pending";
            break;
        case "1":
            $status = "Approved";
            break;
    }
   
    //If the user has admin permissions
    if ($can_edit) {
        
        //Add the employees name to the request ticket
        $output .= "<tr> 
        <td>" . substr($row["name"], 0, strpos($row["name"], " ") + 2) . ".</td>
        <td><input type='date' class='start request-input' value='" . $row["start_date"] . "' disabled></td>
        <td><input type=date class='end request-input' value='" . $row["end_date"] . "' disabled></td> 
        <td class='time-off-reason'><span>" . $row["reason"] . "</span></td>";

        //Show an icon based on the status of the request
        switch ($status) {
            case "Rejected":
                $output .= "<td class='request-action-row' id='request-" . $row["id"] . "-action'><i class='fas fa-times status-complete' title='Request Rejected'></i></td>";

                break;
            case "Approved":
                $output .= "<td class='request-action-row' id='request-" . $row["id"] . "-action'><i class='fas fa-check status-complete' title='Request Accepted'></i></td>";

                break;
            case "Pending":
                $output .= "<td class='request-action-row' id='request-" . $row["id"] . "-action'><i class='accept-request-button fas fa-check' id='accept-" . $row["id"] . "' title='Accept Request'></i><i class='fas fa-times reject-request' id='reject-" . $row["id"] . "' title='Reject Request'></i></td>";

                break;
        }

        $output .= "</tr>";
    } 
    //The uses is not admin
    else {
        
        //Create the request ticket
        $output .= "<tr>  
        <td><input type='date' class='start request-input' value='" . $row["start_date"] . "' disabled></td>
        <td><input type=date class='end request-input' value='" . $row["end_date"] . "' disabled></td> 
        <td class='time-off-reason'><span>" . $row["reason"] . "</span></td>
        <td><span>" . $status . "</span></td>";
        if ($status == "Pending") {
            $output .= "<td class='request-edit-row' id='request-" . $row["id"] . "-edit'><i class='edit-request-button fas fa-edit' id='request-" . $row["id"] . "'></i><i class='fas fa-trash-alt delete-request' id='delete-" . $row["id"] . "'></i></td>";
        } else {
            $output .= "<td></td>";
        }
        $output .= "</tr>";
    }
}


$output .= "</tbody> </table>";

echo $output;
