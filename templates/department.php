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

//The user has admin permissions
$can_edit = $employee_data["position"] <= 2;

$department = $employee_data["department"];

//Get all the employees in the department
$cmd = "SELECT * FROM `employees` WHERE `department` = ? ORDER BY `position`";
$getEmployees = $dbh->prepare($cmd);
$success = $getEmployees->execute([$department]);

//Get all the positions
$cmd = "SELECT * FROM `positions`";
$getPositions = $dbh->prepare($cmd);
$success = $getPositions->execute([]);

$positions = [];

while ($row = $getPositions->fetch()) {
    $positions[$row["id"]] = $row["name"];
}

$output = "";

//If the user has admin permissions
if ($can_edit) {

    //Creat the add employee form
    $output .= "<div id='new-employee-container'>
    <h2>New Employee</h2> 
    <form id='new-employee-form' name='new-employee-form'>
    <input  id='new-employee-department' type='hidden' value='" . $employee_data["department"] . "'>
    <input  id='new-employee-name' type='text' placeholder='First and last name' required>
    <input id='new-employee-number' type='number' placeholder='Employee number' required>
    ";

    //Position selector
    $positionSelect = "<select id='new-employee-position'>";
    foreach ($positions as $index => $position) {
        if ($position == "Admin") {
            continue;
        }
        $selected = "";
        if ($position == "Customer Service Rep") {
            $selected = "selected";
        }
        $positionSelect .= "<option value='" . $index . "' " . $selected . ">" . $position . "</option>";
    }
    $positionSelect .= "</select>";

    //The Add employee button
    $output .= $positionSelect . "<input type='submit' value='Add employee'></form></div>";
}

$output .= "<h2>" . $employee_data["department"] . " Department</h2>";

//If the user has admin permission
if ($can_edit) {
    //Add the Edit column to the table
    $output .= "<table> <thead> <tr> <th>Name</th> <th>Position</th> <th>Edit</th></tr> </thead> <tbody>";
} else {
    $output .= "<table> <thead> <tr> <th>Name</th> <th>Position</th> </tr> </thead> <tbody>";
}

//For every employee in the department:
while ($row = $getEmployees->fetch()) {
    
    //If the user has admin permissions, add edit buttons to each employee row and a position selector
    if ($can_edit) {

        //Create the position selector element
        $positionEdit = "<select disabled>";
        
        //Create the options for the select
        foreach ($positions as $index => $position) {
            if ($position == "Admin") {
                continue;
            }
            $selected = "";
            if ($position == $positions[$row["position"]]) {
                $selected = "selected";
            }
            $positionEdit .= "<option value='" . $index . "' " . $selected . ">" . $position . "</option>";
        }
        $positionEdit .= "</select>";

        //Creat the employees row
        $output .= "<tr> 
                        <td>" . $row["name"] . "</td> 
                        <td>" . $positionEdit . "</td> 
                        <td class='employee-edit-row' id='employee-" . $row["id"] . "-edit'>
                            <i class='edit-employee-button fas fa-edit' id='employee-" . $row["id"] . "'></i>
                            <i class='fas fa-trash-alt delete-employee' id='delete-" . $row["id"] . "'></i>
                        </td>
                    </tr>";
    } 
    //Otherwise, just show the employee info
    else {
        $output .= "<tr> <td>" . $row["name"] . "</td> <td>" . $positions[$row["position"]] . "</td> </tr>";
    }
}

$output .= "</tbody> </table>";


echo $output;
