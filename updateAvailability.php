<?php
/*Author: Sam Gershkovich 000801766
  Date: December 02, 2020
 *I, Sam Gershkovich, 000801766 certify that this material is my original work.
 *No other person's work has been used without due acknowledgement.
 *
*/

include "connect.php";
include "kickToLogin.php";

$employee_id = $_SESSION["id"];

//Get and filter all inputs
$saturdayStart = filter_input(INPUT_GET, "saturdayStart", FILTER_SANITIZE_SPECIAL_CHARS);
$saturdayEnd =  filter_input(INPUT_GET, "saturdayEnd", FILTER_SANITIZE_SPECIAL_CHARS);
$sundayStart = filter_input(INPUT_GET, "sundayStart", FILTER_SANITIZE_SPECIAL_CHARS);
$sundayEnd =  filter_input(INPUT_GET, "sundayEnd", FILTER_SANITIZE_SPECIAL_CHARS);
$mondayStart = filter_input(INPUT_GET, "mondayStart", FILTER_SANITIZE_SPECIAL_CHARS);
$mondayEnd =  filter_input(INPUT_GET, "mondayEnd", FILTER_SANITIZE_SPECIAL_CHARS);
$tuesdayStart = filter_input(INPUT_GET, "tuesdayStart", FILTER_SANITIZE_SPECIAL_CHARS);
$tuesdayEnd =  filter_input(INPUT_GET, "tuesdayEnd", FILTER_SANITIZE_SPECIAL_CHARS);
$wednesdayStart = filter_input(INPUT_GET, "wednesdayStart", FILTER_SANITIZE_SPECIAL_CHARS);
$wednesdayEnd =  filter_input(INPUT_GET, "wednesdayEnd", FILTER_SANITIZE_SPECIAL_CHARS);
$thursdayStart = filter_input(INPUT_GET, "thursdayStart", FILTER_SANITIZE_SPECIAL_CHARS);
$thursdayEnd =  filter_input(INPUT_GET, "thursdayEnd", FILTER_SANITIZE_SPECIAL_CHARS);
$fridayStart = filter_input(INPUT_GET, "fridayStart", FILTER_SANITIZE_SPECIAL_CHARS);
$fridayEnd =  filter_input(INPUT_GET, "fridayEnd", FILTER_SANITIZE_SPECIAL_CHARS);

//Make sure unavailable days are null
if($saturdayStart == "null"){
    $saturdayStart = null;
}
if($saturdayEnd == "null"){
    $saturdayEnd = null;
}
if($sundayStart == "null"){
    $sundayStart = null;
}
if($sundayEnd == "null"){
    $sundayEnd = null;
}
if($mondayStart == "null"){
    $mondayStart = null;
}
if($mondayEnd == "null"){
    $mondayEnd = null;
}
if($tuesdayStart == "null"){
    $tuesdayStart = null;
}
if($tuesdayEnd == "null"){
    $tuesdayEnd = null;
}
if($wednesdayStart == "null"){
    $wednesdayStart = null;
}
if($wednesdayEnd == "null"){
    $wednesdayEnd = null;
}
if($thursdayStart == "null"){
    $thursdayStart = null;
}
if($thursdayEnd == "null"){
    $thursdayEnd = null;
}
if($fridayStart == "null"){
    $fridayStart = null;
}
if($fridayEnd == "null"){
    $fridayEnd = null;
}

//Update an employees availability
$cmd = "UPDATE `availability` SET `saturday_start`=?, `saturday_end`=?,`sunday_start`=?,`sunday_end`=?,`monday_start`=?,`monday_end`=?,`tuesday_start`=?,`tuesday_end`=?,`wednesday_start`=?,`wednesday_end`=?,`thursday_start`=?,`thursday_end`= ?,`friday_start`= ?,`friday_end`= ? WHERE `employee_id` = ?";
$updateAvailability = $dbh->prepare($cmd);
$success = $updateAvailability->execute([$saturdayStart, $saturdayEnd, $sundayStart, $sundayEnd, $mondayStart, $mondayEnd, $tuesdayStart, $thursdayEnd, $wednesdayStart, $wednesdayEnd, $thursdayStart, $thursdayEnd, $fridayStart, $fridayEnd, $employee_id]);


if ($success === false) {
    echo "failed";
} else {
    echo "success";
}
