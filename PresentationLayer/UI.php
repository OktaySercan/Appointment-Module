<?php
require_once ("LogicLayer/AppointmentManager.php");
require_once ("LogicLayer/SubBranchManager.php");
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
        <a id="home" name="homeTab" href="/mhrsTest/index.php" class="selectedLink">Home</a>
        <a id="list" name="listTab" href="/mhrsTest/Listing.php" class="">List Appointments</a>
    </div>
    <div class="progress" id="progressDiv">
        <div class="progress-bar progress-bar-striped active" id="firstStep" role="progressbar" style="width:25%">
            Pick Branch and Date
        </div>
        <div class="progress-bar progress-bar-warning" id ="secondStep" role="progressbar" style="width:25%">
            Pick Doctor
        </div>
        <div class="progress-bar progress-bar-warning" id ="thirdStep" role="progressbar" style="width:25%">
            Pick Appointment Time
        </div>
        <div class="progress-bar progress-bar-warning" id ="fourthStep" role="progressbar" style="width:25%">
            Success
        </div>
    </div>
    <div id="mainContainer">
        <p style="margin: auto;position: relative;margin-left: 35%;color: black;text-shadow: 3px 2px white;font-weight:bold;font-size: 20px" >SELECT BRANCH</p>
        <p style="margin: auto;position: relative;margin-left: 60%">Date: <input type="date" id="datepicker"></p>
        <select id="branchList" style="margin-left: 35%">
            <?php
            $subBranchList = SubBranchManager::getSubBranches();
            for($i = 0; $i < count($subBranchList); $i++) {
                $name=$subBranchList[$i]->getName();
                ?>

                <option value="<?php echo $name; ?>"><?php echo $name; ?></option>
                <?php
            }
            ?>
        </select>
        <p></p>
        <form action="doctor.php" method="POST">
            <input class="button" style="vertical-align:middle" onclick="progressChange()" type="button" id="submit1" value="Submit" />
        </form>
        <div id="divCallResult">

        </div>
    </div>

    <div id="mainContainerStep2">
        <form action="doctor.php" method="POST">
            <p style="margin: auto;position: relative;margin-left: 35%;color: black;text-shadow: 3px 2px white;font-weight:bold;font-size: 20px" >SELECT DOCTOR</p>
            <select id="dropdown">

            </select>
            <p></p>
            <input class="button" style="vertical-align:middle" onclick="progressChange()" type="button" id="submit2" value="Submit" />
        </form>
        <div id="divCallResult">

        </div>
    </div>
    <div id="mainContainerStep3">
        <form action="doctor.php" method="POST">
            <p style="margin: auto;position: relative;margin-left: 35%;color: black;text-shadow: 3px 2px white;font-weight:bold;font-size: 20px" >SELECT EXAMINATION TIME</p>
            <table id="tblAppointments">
                <tbody>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                <?php
                for($i = 9; $i < 16; $i++) {
                    ?>
                    <tr>
                        <td><?php
                            if($i==9)
                                {$time = "0".$i.":00";}
                            else
                                {$time = $i.":00";}
                            echo "<input class='timeButton' type='button' id='tblButton' value='$time' onclick='deneme(this.value)'/>"?> </td>
                        <td><?php
                            if($i==9)
                            {$time = "0".$i.":10";}
                            else
                            {$time = $i.":10";}
                            echo "<input class='timeButton' type='button' id='tblButton' value='$time' onclick='deneme(this.value)'/>"?> </td>
                        <td><?php
                            if($i==9)
                            {$time = "0".$i.":20";}
                            else
                            {$time = $i.":20";}
                            echo "<input class='timeButton' type='button' id='tblButton' value='$time' onclick='deneme(this.value)'/>"?> </td>
                        <td><?php
                            if($i==9)
                            {$time = "0".$i.":30";}
                            else
                            {$time = $i.":30";}
                            echo "<input class='timeButton' type='button' id='tblButton' value='$time' onclick='deneme(this.value)'/>"?> </td>
                        <td><?php
                            if($i==9)
                            {$time = "0".$i.":40";}
                            else
                            {$time = $i.":40";}
                            echo "<input class='timeButton' type='button' id='tblButton' value='$time' onclick='deneme(this.value)'/>"?> </td>
                        <td><?php
                            if($i==9)
                            {$time = "0".$i.":50";}
                            else
                            {$time = $i.":50";}
                            echo "<input class='timeButton' type='button' id='tblButton' value='$time' onclick='deneme(this.value)'/>"?> </td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
            <input type="text" id="dene" style="visibility: hidden;"/>
            <input class="button" style="vertical-align:middle" onclick="progressChange()" type="button" id="submit3" value="Submit" />
            <input type="text" style="visibility: hidden;" id="patientSessionName"/>
            <input type="text" style="visibility: hidden;" id="patientSessionSSN"/>
        </form>
        <div id="divCallResult">

        </div>
    </div>
    <div id="mainContainerStep4">
        <form action="AppointmentInfoMng.php" method="POST">
            <p style="margin: auto;position: relative;margin-left: 25%;color: white;text-shadow: 3px 2px red;font-weight:bold;font-size: 20px" id="approveApp">asdasd</p>
            <p style="margin: auto;position: relative;margin-left: 25%;color: white;text-shadow: 3px 2px red;font-weight:bold;font-size: 20px;visibility: hidden" id="resultMsg">You Have Succesfully Taken An Appointment</p>
            <input class="button" style="vertical-align:middle" onclick="progressChange()" type="button" id="submit4" value="Confirm" />
        </form>
        <div id="divCallResult">

        </div>
    </div>
    <div id="mainContainerStep5">
        <p style="margin: auto;position: relative;margin-left: 25%;color: white;text-shadow: 3px 2px red;font-weight:bold;font-size: 20px;visibility: hidden" id="resultMsg">You Have Succesfully Taken An Appointment</p>
    </div>

</div>

<script type="text/javascript" src="js/functions.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script>
    // JQuery
    $(document).ready(function() { // when DOM is ready, this will be executed
        $("#submit1").click(function(e) { // click event for "btnCallSrvc"

            var date = $("#datepicker").val(); // get date
            var branch = $("#branchList").val();
            if(date == "") {
                alert("Enter country code!");
                $("#datepicker").focus();
                return;
            }

            var retType = "json"; // get reply format
            $.ajax({ // start an ajax POST
                type	: "post",
                url		: "doctor.php",
                data	:  {
                    "branch" : branch,
                    "format": retType,
                },
                success : function(reply) { // when ajax executed successfully
                   // console.log(reply);
                    if(retType == "json") {
                        var crappyJSON = JSON.stringify(reply);
                        console.log(branch);
                        console.log(crappyJSON);
                        //console.log(crappyJSON);
                        var jsonData = JSON.parse(crappyJSON);

                        for (var i = 0; i < jsonData.Doctors.length; i++) {
                            var doctor = jsonData.Doctors[i];
                            var doctorName = doctor.Name;
                            var doctorSurname = doctor.Surname;
                            var doctorSSN = doctor.TC;
                            $("#dropdown").append('<option id=' + doctorSSN + '>' + doctorName +' '+ doctorSurname + '</option>')
                            console.log(doctor.Name);
                        }
                    }
                    else {
                        $("#divCallResult").html( new XMLSerializer().serializeToString(reply) );
                    }

                },
                error   : function(err) { // some unknown error happened
                    console.log(err);
                    alert(" There is an error! Please try again. " + err);
                }
            });

        });

        $("#submit2").click(function(e) { // click event for "btnCallSrvc"

            var doctorName = $("#dropdown").val(); // get date

            if(doctorName == "") {
                alert("Enter country code!");
                $("#dropdown").focus();
                return;
            }

            var retType = "xml"; // get reply format
            $.ajax({ // start an ajax POST
                type	: "post",
                url		: "doctor.php",
                data	:  {
                    "doctorName": doctorName,
                    "format": retType,
                },
                success : function(reply) { // when ajax executed successfully
                    console.log(reply);
                    if(retType == "json") {
                        $("#divCallResult").html( JSON.stringify(reply) );
                    }
                    else {
                        $("#divCallResult").html( new XMLSerializer().serializeToString(reply) );
                    }

                },
                error   : function(err) { // some unknown error happened
                    console.log(err);
                    alert(" There is an error! Please try again. " + err);
                }
            });

        });
        $("#submit3").click(function(e) { // click event for "btnCallSrvc"

            var examinationTime = $("#dene").val(); // get date
            var doctorName = $("#dropdown").val();
            var branchName = $("#branchList").val();
            var date = $("#datepicker").val();
            var msg = "Date: " + date +"<br />"+"Branch Name: "+branchName+"<br />"+ "Doctor Name: "+ doctorName +"<br />"+"Examination Time: "+examinationTime+"<br />"+"Do You Confirm These Information ?";
            //display_txt = display_txt.replace(/\n/g, "<br />");
            $("#approveApp").html(msg);
            if(examinationTime == "") {
                alert("Please Select Examination Time!");
                $("#tblAppointments").focus();
                return;
            }
           // var date =$('#datepicker').val();
            var retType = "json"; // get reply format
            $.ajax({ // start an ajax POST
                type	: "post",
                url		: "getInfoFromSession.php",
                data	:  {
                    "format": retType,
                },
                success : function(reply) { // when ajax executed successfully
                    console.log(reply);
                    if(retType == "json") {
                        var crappyJSON = JSON.stringify(reply);
                        var jsonData = JSON.parse(crappyJSON);
                        if(jsonData.ActivePatient.length > 0)
                        {
                            var patient = jsonData.ActivePatient[0];
                            var patientssn = patient.PatientSSN;
                            var patientName = patient.PatientName;
                            $("#patientSessionSSN").val(patientssn);
                            $("#patientSessionName").val(patientName);

                        }
                    }
                    else {
                        $("#divCallResult").html( new XMLSerializer().serializeToString(reply) );
                    }

                },
                error   : function(err) { // some unknown error happened
                    console.log(err);
                    alert(" There is an error! Please try again. " + err);
                }
            });
        });
        $("#submit4").click(function(e) { // click event for "btnCallSrvc"

            var doctorName = $("#dropdown").val(); // get date
            var examinationTime = $("#dene").val();
            var date = $("#datepicker").val();
            var branchName = $("#branchList").val();
            var doctorSSN = $("#dropdown").children(":selected").attr("id");
            var patientSSN = $("#patientSessionSSN").val();
            var patientName = $("#patientSessionName").val();
            if(doctorName == "") {
                alert("Enter country code!");
                $("#dropdown").focus();
                return;
            }

            var retType = "xml"; // get reply format
            $.ajax({ // start an ajax POST
                type	: "post",
                url		: "AppointmentInfoMng.php",
                data	:  {
                    "date": date,
                    "time" : examinationTime,
                    "patientName" : patientName,
                    "patientSSN" : patientSSN,
                    "doctorID" : doctorSSN,
                    "doctorName": doctorName,
                    "branchName" : branchName,
                    "format": retType,
                },
                success : function(reply) { // when ajax executed successfully
                    console.log(reply);
                    if(retType == "json") {
                        $("#divCallResult").html( JSON.stringify(reply) );
                    }
                    else {
                        $("#divCallResult").html( new XMLSerializer().serializeToString(reply) );
                    }

                },
                error   : function(err) { // some unknown error happened
                    console.log(err);
                    alert(" There is an error! Please try again. " + err);
                }
            });
            $('#submit4').hide();
        });

    });
</script>

</body>
</html>

