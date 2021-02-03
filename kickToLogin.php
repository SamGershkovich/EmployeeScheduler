<?php

/*Author: Sam Gershkovich 000801766
  Date: December 02, 2020
 *I, Sam Gershkovich, 000801766 certify that this material is my original work.
 *No other person's work has been used without due acknowledgement.
 *
*/
session_start();

//If the 'id' variable does not exist in the current session
if(!isset($_SESSION["id"])){
    ?>
    
    <script>
        //Kick the user back to the login page
        window.location.href="index.php";
    </script>
    <?php
}