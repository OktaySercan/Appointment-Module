<?php
require_once ("LogicLayer/AppointmentManager.php");
require_once ("LogicLayer/Appointment_InfoManager.php");

$errorMeesage = "";
if(isset($_POST["id"])&&isset($_POST["operation"])){
    $operationType = $_POST["operation"];
    if($operationType=="edit"&&isset($_POST["patientName"])&&isset($_POST["doctName"])&&isset($_POST["branchName"])&&isset($_POST["appDate"])&&isset($_POST["appTime"])&&isset($_POST["appInfoId"])){
        $id=$_POST["id"];
        $patientName = $_POST["patientName"];
        $doctorName = $_POST["doctName"];
        $branchName = $_POST["branchName"];
        $appDate = $_POST["appDate"];
        $appTime = $_POST["appTime"];
        $appInfoId = $_POST["appInfoId"];
        $success = AppointmentManager::updateAppointmentByAdmin($id,$patientName,$doctorName,$branchName);
        if(!$success){
            $errorMeesage = "Update was unsuccessfull";
        }
        else{
            $successOfAppInfo = Appointment_InfoManager::updateAppointmentInfoByAdmin($appInfoId,$appDate,$appTime);
            if(!$successOfAppInfo){
                $errorMeesage = "Update of date and time was unsuccessfull";
            }
        }
    }
    else{
        $id=$_POST["id"];
        $result = AppointmentManager::deleteAppointment($id);
        if(!$result) {
            $errorMeesage = "Deletion was unsuccessfull";
        }
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
    <link href="css/style.css" rel="stylesheet" type="text/css">
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
        <a id="delete" name="deleteTab" href="/mhrsTest/admin.php" class="selectedLink">Arrange Appointment</a>
        <a id="addBranch" name="branchTab" href="/mhrsTest/adminBranch.php" class="">Manage Branch</a>
    </div>
    <div id="listContainer">
        <form action="" method="post">
            <table id="tblAppointments" class="table table-bordered">
                <tbody>
                <tr>
                    <th >ID</th>
                    <th >Patient Name</th>
                    <th>Patient SSN</th>
                    <th>Doctor Name</th>
                    <th>Subbranch ID</th>
                    <th>Appointment Date</th>
                    <th>Appointment Time</th>
                    <th></th>
                    <th style="visibility: hidden;"></th>
                </tr>
                <?php
                $appointmentsList = AppointmentManager::getAppointments();
                for($i = 0; $i < count($appointmentsList); $i++) {
                    $appointmentInfoList = Appointment_InfoManager::getAppointmentInfo($appointmentsList[$i]->getAppointmentInfoID());
                    ?>
                    <tr>
                        <td class="id"><?php echo $appointmentsList[$i]->getAppointmentId(); ?></td>
                        <td class="nameTd"><?php echo $appointmentsList[$i]->getPatientName(); ?></td>
                        <td class="ssnTd"><?php echo $appointmentsList[$i]->getPatientSSN(); ?></td>
                        <td class="doctNameTd"><?php echo $appointmentsList[$i]->getDoctorName(); ?></td>
                        <td class="branchNameTd"><?php echo $appointmentsList[$i]->getSubID(); ?></td>
                        <td class="appDateTd"><?php echo $appointmentInfoList[0]->getDate(); ?></td>
                        <td class="appTimeTd"><?php echo $appointmentInfoList[0]->getTime(); ?></td>
                        <td> <button type="button" class="adminEdit" name="edit">Edit</button>
                            <button type='submit' class='adminDel'><span class="glyphicon glyphicon-trash"></span></button>
                            </td>
                        <td style="visibility: hidden" class="appInfoIdTd"><?php echo $appointmentInfoList[0]->getID(); ?></td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
            <input type="hidden" id="appId" name="id""/>
            <input type="hidden" id="patientName" name="patientName"/>
            <input type="hidden" id="doctorName" name="doctName"/>
            <input type="hidden" id="branch" name="branchName"/>
            <input type="hidden" id="date" name="appDate"/>
            <input type="hidden" id="time" name="appTime"/>
            <input type="hidden" id="appInfo" name="appInfoId"/>
            <input type="hidden" id="operationType" name="operation"/>
        </form>
    </div>
</div>
<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script>
    var editOrSave = 1;
    $(document).ready(function () {
        $(".adminDel").click(function() {
            var $row = $(this).closest("tr");    // Find the row
            var id = $row.find(".id").text();
            $("#appId").val(id);
            $("#operationType").val("delete");

        });
        $(".adminEdit").click(function() {
            var $this = $(this);
            if (editOrSave == 1) {
                $this.text('Save');
                $(".nameTd").attr('contentEditable',true);
                $(".doctNameTd").attr('contentEditable',true);
                $(".branchNameTd").attr('contentEditable',true);
                $(".appDateTd").attr('contentEditable',true);
                $(".appTimeTd").attr('contentEditable',true);
                editOrSave = 2;
            } else {
                var $row = $(this).closest("tr");    // Find the row
                var id = $row.find(".id").text();
                var name = $row.find(".nameTd").text();
                var doctName = $row.find(".doctNameTd").text();
                var branchName = $row.find(".branchNameTd").text();
                var appDate = $row.find(".appDateTd").text();
                var appTime = $row.find(".appTimeTd").text();
                var appInfoId = $row.find(".appInfoIdTd").text();
                $this.text('Edit');
                editOrSave = 1;
                $("#appId").val(id);
                $("#patientName").val(name);
                $("#doctorName").val(doctName);
                $("#branch").val(branchName);
                $("#date").val(appDate);
                $("#time").val(appTime);
                $("#appInfo").val(appInfoId);
                $("#operationType").val("edit");
                $("button[name='edit']").prop("type", "submit");
                //inputların değerleri değiştirilecek!! POST işlemleri...
            }
        });
    });

</script>
<script type="text/javascript" src="js/functions2.js"></script>
</body>
</html>
