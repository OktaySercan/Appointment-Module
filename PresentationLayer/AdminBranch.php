<?php
require_once ("LogicLayer/AppointmentManager.php");
require_once ("LogicLayer/GeneralBranchManager.php");
require_once ("LogicLayer/SubBranchManager.php");

$errorMeesage = "";
if(isset($_POST["generalBranchName"])&&isset($_POST["subBranchName"])){
    $generalBranchName = $_POST["generalBranchName"];
    $subBranchName = $_POST["subBranchName"];
    $result = SubBranchManager::insertSubBranch($generalBranchName,$subBranchName);
    if(!$result) {
        $errorMeesage = "Deletion was unsuccessfull";
    }
}
if(isset($_POST['logout'])){
    session_start();
    unset($_SESSION["activeUser"]);  // where $_SESSION["nome"] is your own variable. if you do not have one use only this as follow **session_unset();**
    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MHRS PORTAL</title>
    <link href="css/style2.css" rel="stylesheet" type="text/css">
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div id="container">
    <div id="headerContainer">
        <form action="" method="POST">
            <button type="submit" class="btn btn-default btn-sm" style="margin-left: 110%" id="logOutButton">
                <span class="glyphicon glyphicon-log-out"></span> Log out
            </button>
            <input type="hidden" name="logout" value="LoggedOut"/>
        </form>
        <a href="http://www.saglik.gov.tr" target="_blank" class="logo"></a>
        <span class="owner">DEUCENG HOSPITAL</span>
        <span class="title">APPOINTMENT SYSTEM</span>
    </div>
    <div id="menuContainer">
        <a id="delete" name="deleteTab" href="/mhrsTest/admin.php" class="">Arrange Appointment</a>
        <a id="addBranch" name="branchTab" href="/mhrsTest/adminBranch.php" class="selectedLink">Manage Branch</a>
    </div>
    <div id="mainContainer">
        <form action="" method="post">
            <p style="margin: auto;position: relative;margin-left: 35%;color: white;text-shadow: 3px 2px red;font-weight:bold;font-size: 20px">Select General Branch:</p>
            <select id="generalBranchList" name="generalBranchName">
                <?php
                $generalBranchList = GeneralBranchManager::getGeneralBranches();
                for($i = 0; $i < count($generalBranchList); $i++) {
                    $name=$generalBranchList[$i]->getName();
                    ?>

                    <option value="<?php echo $name; ?>"><?php echo $name; ?></option>
                    <?php
                }
                ?>
            </select>
            <p style="margin: auto;position: relative;margin-left: 35%;color: white;text-shadow: 3px 2px red;font-weight:bold;font-size: 20px">Enter Subbranch Name:</p>
            <input type="text" id="addBranch" name="subBranchName"/>
            <p></p>
            <input type='submit' class='AddBranch' value='Add' style="margin-left: 35%"/>
        </form>
    </div>
</div>
</body>
</html>