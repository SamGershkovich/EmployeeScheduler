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

//Create the change password view
$output = "<h2>Change Password</h2>";
$output .= "<form id='change-password-form'>
<input type='hidden' id='employee-id' value=".$employee_id.">
<div><i class='fas fa-eye-slash visibility'></i><input type='password' id='old-password' placeholder='Enter old password'></div>
<div><i class='fas fa-eye-slash visibility'></i><input type='password' id='new-password' placeholder='Enter new password'><i id='new-password-status' class='fas fa-check hidden status'></i></div>
<div><i class='fas fa-eye-slash visibility'></i><input type='password' id='confirm-new-password' placeholder='Confirm new password' disabled><i id='confirm-password-status' class='fas fa-check hidden status'></i></div>

<p id='status-text'></p>
<input id='change-password-button' type='submit' value='Change password' disabled></form>";


echo $output;


