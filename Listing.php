<?php
session_start();
if(isset($_SESSION['activePatient'])) {
    require_once ("PresentationLayer/List.php");
}
else{
    require_once ("login.php");
}

?>

