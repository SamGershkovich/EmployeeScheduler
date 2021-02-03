<?php
/*Author: Sam Gershkovich 000801766
  Date: December 02, 2020
 *I, Sam Gershkovich, 000801766 certify that this material is my original work.
 *No other person's work has been used without due acknowledgement.
 *
*/
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmboy Operations Online</title>
    <script src="js/login.js"></script>
    <script src="https://kit.fontawesome.com/f11bc76b47.js" crossorigin="anonymous"></script>
    <script src="js/jquery-3.5.1.min.js"></script>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div id="header">
        <figure>
            <img src="images/logo.png">
        </figure>
        <div id="top-bar">
        </div>
        <div id="name-bar">
        </div>
    </div>
    <form id='login' method="post">
        <div>       
            <input type="number" name="username" placeholder="Employee Number">
            <i class='fas fa-eye-slash' style='visibility: hidden;'></i>
        </div>
        <div>
            <input type="password" name="password" placeholder="Password">
            <i class='fas fa-eye-slash visibility'></i>
        </div>
        <p id="login-status"></p>
        <input type="submit" name="submit" value='Log in'>
    </form>
</body>

</html>