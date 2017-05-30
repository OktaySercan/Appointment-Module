<?php
session_start();
$activeUser = null;
if(isset($_SESSION['activeUser'])) {
    $activeUser =  $_SESSION['activeUser'];
    require_once ("PresentationLayer/AdminBranch.php");
}
else{
    require_once ("login.php");
}
?>