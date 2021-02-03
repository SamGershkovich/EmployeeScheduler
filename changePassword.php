<?php
/*Author: Sam Gershkovich 000801766
  Date: December 02, 2020
 *I, Sam Gershkovich, 000801766 certify that this material is my original work.
 *No other person's work has been used without due acknowledgement.
 *
*/
session_start();
include "connect.php";

//Get and filter the inputs
$employee_id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
$old = filter_input(INPUT_GET, "old", FILTER_SANITIZE_SPECIAL_CHARS);
$new = filter_input(INPUT_GET, "new", FILTER_SANITIZE_SPECIAL_CHARS);

$hash = password_hash($new, PASSWORD_DEFAULT);

//Get the employee with the matching id
$cmd = "SELECT * FROM `employees` WHERE `id` = ?";
$stmt = $dbh->prepare($cmd);
$success = $stmt->execute([$employee_id]);

$user_data = $stmt->fetch();

//If an employee was returned
if (is_array($user_data)) {
    
    //If the users old password and the old password entered are both empty
    if ($user_data["password"] == "" && $old == "" ) {

        //Update the employees password
        $cmd = "UPDATE `employees` SET `password`= ? WHERE `id` = ?";
        $stmt = $dbh->prepare($cmd);
        $success = $stmt->execute([$hash, $employee_id]);
        echo "Password changed";
    }
    else {
        $user_password = $user_data["password"];

        //If the old password verification passed
        if (password_verify($old, $user_password)) {
            //Update the employees password
            $cmd = "UPDATE `employees` SET `password`= ? WHERE `id` = ?";
            $stmt = $dbh->prepare($cmd);
            $success = $stmt->execute([$hash, $employee_id]);

            echo "Password changed";
        } else {
            echo "Incorrect password";
        }
    }
} 
else {
    echo "Something went wrong";
}
