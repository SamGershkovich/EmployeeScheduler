<?php
/*Author: Sam Gershkovich 000801766
  Date: December 02, 2020
 *I, Sam Gershkovich, 000801766 certify that this material is my original work.
 *No other person's work has been used without due acknowledgement.
 *
*/

try{
    $dbh = new PDO("mysql:host=localhost;dbname=000801766","root","");
}catch(Exception $e){
    die("ERROR. Couldn't get DB Connection. ".$e->getMessage());
}