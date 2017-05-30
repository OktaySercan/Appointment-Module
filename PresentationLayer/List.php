<?php
require_once ("LogicLayer/AppointmentManager.php");
require_once ("LogicLayer/Appointment_InfoManager.php");
require_once ("LogicLayer/SubBranchManager.php");

$errorMeesage = "";
if(isset($_POST["id"])&&isset($_POST["operation"])){
    $operationType = $_POST["operation"];
    if($operationType=="edit"&&isset($_POST["patientSSN"])&&isset($_POST["patientName"])&&isset($_POST["oldSSN"])){
        $id=$_POST["id"];
        $patientSSN=$_POST["patientSSN"];
        $patientName = $_POST["patientName"];
        $oldSSN=$_POST["oldSSN"];
        $success = AppointmentManager::updateAppointmentByUser($patientSSN,$patientName,$oldSSN);
        if(!$success){
            $errorMeesage = "Update was unsuccessfull";
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
    unset($_SESSION["activePatient"]);  // where $_SESSION["nome"] is your own variable. if you do not have one use only this as follow **session_unset();**
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
        <a id="home" name="homeTab" href="/mhrsTest/index.php" class="">Home</a>
        <a id="list" name="listTab" href="/mhrsTest/Listing.php" class="selectedLink">List Appointments</a>
    </div>
    <div id="listContainer">
        <form action="" method="post">
            <table id="tblAppointments">
                <tbody>
                <tr>
                    <th>ID</th>
                    <th>Patient Name</th>
                    <th>Patient SSN</th>
                    <th>Doctor Name</th>
                    <th>Branch Name</th>
                    <th>Appointment Date</th>
                    <th>Appointment Time</th>
                    <th></th>
                </tr>
                <?php
                //session_start();
                $patientSSN = "";
                foreach ($_SESSION['activePatient'] as $item) {
                    $patientSSN =  $item['patientssn'];
                }
                $appointmentsList = AppointmentManager::getPersonalAppointments($patientSSN);

                for($i = 0; $i < count($appointmentsList); $i++) {
                    $appointmentInfoList = Appointment_InfoManager::getAppointmentInfo($appointmentsList[$i]->getAppointmentInfoID());
                    $subBranchName = SubBranchManager::getSubBranchNameWithID($appointmentsList[$i]->getSubID());
                    ?>
                    <tr>
                        <td class="id"><?php echo $appointmentsList[$i]->getAppointmentId(); ?></td>
                        <td class="nameTd"><?php echo $appointmentsList[$i]->getPatientName(); ?></td>
                        <td class="ssnTd"><?php echo $appointmentsList[$i]->getPatientSSN(); ?></td>
                        <td class="doctNameTd"><?php echo $appointmentsList[$i]->getDoctorName(); ?></td>
                        <td class="branchNameTd"><?php echo $subBranchName[0]->getName(); ?></td>
                        <td class="appDateTd"><?php echo $appointmentInfoList[0]->getDate(); ?></td>
                        <td class="appTimeTd"><?php echo $appointmentInfoList[0]->getTime(); ?></td>
                        <td> <button type="button" class="userEdit" name="edit">Edit</button>
                            <button type="submit" class="userDelApp"><span class="glyphicon glyphicon-trash" name="delete"></span></button>
                        </td>

                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
            <input type="hidden" id="appId" name="id"/>
            <input type="hidden" id="oldPSSN" name="oldSSN"/>
            <input type="hidden" id="patSSN" name="patientSSN""/>
            <input type="hidden" id="patName" name="patientName"/>
            <input type="hidden" id="operationType" name="operation"/>
        </form>
    </div>
</div>
</body>
<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
<script>
    var editOrSave = 1;
    $(document).ready(function () {
        $(".userDelApp").click(function() {
            var $row = $(this).closest("tr");    // Find the row
            var id = $row.find(".id").text();
            $("#appId").val(id);
            $("#operationType").val("delete");
        });
        $(".userEdit").click(function() {
            var $this = $(this);
            if (editOrSave == 1) {
                $this.text('Save');
                $(".nameTd").attr('contentEditable',true);
                $(".ssnTd").attr('contentEditable',true);
                var $row = $(this).closest("tr");
                var oldPSSN = $row.find(".ssnTd").text();
                $("#oldPSSN").val(oldPSSN);
                editOrSave = 2;
            } else {
                var $row = $(this).closest("tr");    // Find the row
                var id = $row.find(".id").text();
                var ssn = $row.find(".ssnTd").text();
                var name = $row.find(".nameTd").text();
                $this.text('Edit');
                editOrSave = 1;
                $("#patSSN").val(ssn);
                $("#patName").val(name);
                $("#appId").val(id);
                $("#operationType").val("edit");
                $("button[name='edit']").prop("type", "submit");
            }
        });
    });

</script>
<script type="text/javascript" src="js/functions2.js"></script>

</html>

