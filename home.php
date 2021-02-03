<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmboy Operations Online</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/f11bc76b47.js" crossorigin="anonymous"></script>
    <script src="js/jquery-3.5.1.min.js"></script>
    <script src="js/home.js"></script>
</head>

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

$cmd = "SELECT * FROM `employees` WHERE `id` = ?";
$stmt = $dbh->prepare($cmd);
$success = $stmt->execute([$employee_id]);

$employee_data = $stmt->fetch();
?>

<body>
    <div id="header">
        <figure>
            <img src="images/logo.png">
        </figure>
        <div id="top-bar">
            <label id='profile-button'><i class="account-button fas fa-user-circle"></i><span>Profile</span></label>
            <button id="log-out">Log out</button>
        </div>
        <div id="name-bar">
            <h2><?= $employee_data["name"]; ?></h2>
            <input id="employee-id" type="hidden" value="<?= $employee_data['position'];?>">
        </div>
    </div>

    <span id='main'>
        <nav>
            <ul>
                <li class="selected">Schedule</li>
                <li>Availability</li>
                <li>Time-off</li>
                <li>Department</li>
            </ul>
        </nav>
        <div class="container-main">
            <div id="schedule" class="tab-container">
            </div>
            <div id="availability" class="tab-container hidden">
            </div>
            <div id="time-off" class="tab-container hidden">
            </div>
            <div id="department" class="tab-container hidden">
            </div>
        </div>
    </span>
    <span id='profile-main' class='hidden'>       
        <nav>
            <ul>
                <li class="selected">Profile</li>
                <li>Security</li>
            </ul>
        </nav>
        <div class="container-profile">
            <div id="profile" class="profile-tab-container">
            </div> 
            <div id="security" class="profile-tab-container hidden">
            </div>          
        </div>
    </span>
</body>

</html>