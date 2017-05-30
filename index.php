<?php
session_start();
if(isset($_SESSION['activePatient'])) {
    require_once("PresentationLayer/UI.php");
}
else{
    require_once ("login.php");
}
?>
