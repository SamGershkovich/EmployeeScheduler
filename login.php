<?php
/*Author: Sam Gershkovich 000801766
  Date: December 02, 2020
 *I, Sam Gershkovich, 000801766 certify that this material is my original work.
 *No other person's work has been used without due acknowledgement.
 *
*/

session_start();
include "connect.php";

//Get and filter inputs
$username = filter_input(INPUT_GET, "username", FILTER_VALIDATE_INT);
$password = filter_input(INPUT_GET, "password", FILTER_SANITIZE_SPECIAL_CHARS);

//Get the employee with the matchin employee number(username)
$cmd = "SELECT * FROM `employees` WHERE `employee_number` = ?";
$stmt = $dbh->prepare($cmd);
$success = $stmt->execute([$username]);

$user_data = $stmt->fetch();

//If a record was returned, the login was successful
if (is_array($user_data)) {
    
    //If the users password is blank and so was the entered password, the login is successful
    if ($user_data["password"] == "" &&  $password == "") {
        //Store the users id
        $_SESSION["id"] = $user_data["id"];
        echo "Success";
    } 
    else {
        $user_password = $user_data["password"];

        //If the verification of the password was successful
        if (password_verify($password, $user_password)) {
            //Store the users id
            $_SESSION["id"] = $user_data["id"];
            echo "Success";
        } 
        //The verification failed
        else {
            echo "Incorrect username or password!";
        }
    }
} 
//The login was not successful
else {
    echo "Incorrect username or password!";
}