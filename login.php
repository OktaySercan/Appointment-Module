<?php
if( isset($_POST["patientSSN"]) && isset($_POST["patientName"]) && isset($_POST["patientPassword"])&& isset($_POST["status"]))
{
    $status = $_POST["status"];
    if ($status == "patient"){
        $patientSSN = $_POST["patientSSN"];
        $patientName = $_POST["patientName"];
        $patientPassword = $_POST["patientPassword"];
        session_start();
        $_SESSION['patientssn'] = $patientSSN;
        $_SESSION['patientname'] = $patientName;
        $activePatientInfo = array (
            'patientssn' => $_SESSION['patientssn'],
            'patientname' => $_SESSION['patientname']
        );

        $_SESSION['activePatient'][] = $activePatientInfo;

        header("location: index.php");
    }
    else{
        $patientSSN = $_POST["patientSSN"];
        $patientPassword = $_POST["patientPassword"];

        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "hospitaldb";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection Error: " . $conn->connect_error);
        }

        $conn->set_charset("utf8");

        $query = "select username from admin where username = '".$patientSSN."' and password = '".$patientPassword."'";
        $result = $conn->query($query);

        $row = $result->fetch_assoc();

        if($row['username'] == null)
        {
            //$message = "Incorrect entry, try again";
        }
        else
        {
            session_start();
            $_SESSION['activeUser'] = $row['username'];
            header("location: admin.php");
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>MHRS LOGIN</title>

    <link href="dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <link href="css/signin.css" rel="stylesheet">

</head>

<body>

<div class="container">

    <form class="form-signin" action="<?php $_PHP_SELF ?>" method="POST">
        <h2 class="form-signin-heading">Please sign in</h2>
        <label for="inputEmail" class="sr-only">Email address</label>
        <input type="text" id="inputEmail" class="form-control" placeholder="Username" name="uName" required autofocus>
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" id="inputPassword" class="form-control" placeholder="Password" name="uPassword" required>
        <div class="checkbox">
            <label>
                <input type="checkbox" value="remember-me"> Remember me
            </label>
        </div>
        <div id="asd">

        </div>
        <button class="btn btn-lg btn-primary btn-block" type="button" id="signInButton">Sign in</button>
        <button type="submit" id="directing" style="visibility: hidden"></button>
        <input type="text" id="patientInfossn" style="visibility: hidden" name="patientSSN"/>
        <input type="text" id="patientInfoPassword" style="visibility: hidden" name="patientPassword"/>
        <input type="text" id="patientInfoStatus" style="visibility: hidden" name="status"/>
        <input type="text" id="patientInfoName" style="visibility: hidden" name="patientName"/>
    </form>

</div>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script>
    $(document).ready(function() { // when DOM is ready, this will be executed

        $("#signInButton").click(function(e) { // click event for "btnCallSrvc"

            var patientSSN = $("#inputEmail").val(); // get date
            var patientPassword = $("#inputPassword").val();
            if(patientSSN == "") {
                alert("Enter country code!");
                $("#inputEmail").focus();
                return;
            }
            var retType = "json"; // get reply format
            $.ajax({ // start an ajax POST
                type	: "post",
                url		: "patientPhp.php",
                data	:  {
                    "patientSSN"	: patientSSN,
                    "patientPassword" : patientPassword,
                    "format": retType,
                },
                success : function(reply) { // when ajax executed successfully
                    if(retType == "json") {
                        var crappyJSON = JSON.stringify(reply);
                        var jsonData = JSON.parse(crappyJSON);
						
                        if(jsonData.Patient.length > 0)
                        {
                            var patient = jsonData.Patient[0];
                            var patientssn = patient.PatientSSN;
                            var patientName = patient.PatientName;

                            $("#patientInfossn").val(patientssn);
                            $("#patientInfoPassword").val(patientPassword);
                            $("#patientInfoName").val(patientName);
                            $("#patientInfoStatus").val("patient");
                            $("#directing").trigger("click");
                        }
                        else
                        {
							console.log(crappyJSON);
                            $("#patientInfossn").val(patientSSN);
                            $("#patientInfoPassword").val(patientPassword);
                            $("#patientInfoStatus").val("adminOrNot");
                            $("#directing").trigger("click");
                        }
                    }
                    else {
                        //$("#asd").html( new XMLSerializer().serializeToString(reply) );
                        var x = reply.getElementsByTagName('PatientSSN')[0];
                        if(x != null){
                            var y = x.childNodes[0];
                            var patientssn = y.nodeValue;
                            $("#patientInfossn").val(patientssn);
                            $("#patientInfoPassword").val(patientPassword);
                            $("#patientInfoStatus").val("patient");
                            $("#directing").trigger("click");
                        }
                        else{
                            $("#patientInfossn").val(patientSSN);
                            $("#patientInfoPassword").val(patientPassword);
                            $("#patientInfoStatus").val("adminOrNot");
                            $("#directing").trigger("click");
                        }
                    }
                },
                error   : function(err) { // some unknown error happened
                    console.log(err);
                    alert(" There is an error! Please try again. " + err);
                }
            });
        });
    });
</script>
</body>

</html>
