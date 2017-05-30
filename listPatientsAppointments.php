<?php
if(isset($_POST['SSN'])) {
    // connect DB
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "hospitaldb";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection error: " . $conn->connect_error);
    }

    $conn->set_charset("utf8");

    // read POST variables
    $format = "json"; // xml is the default
    $ssn = $_POST['SSN'];
    $currentDate = date("Y-m-d");
    // prepare, bind and execute SQL statement
    $stmt = $conn->prepare("SELECT a.ID,a.PatientName,a.PatientSSN,ai.date,ai.time,g.name,s.name,a.DoctorName FROM appointmentinfo AS ai,appointment as a,subbranch as s,generalbranch as g WHERE a.PatientSSN=? and a.Appointment_InfoID=ai.ID and a.SubBranchID=s.ID and s.generalID=g.ID and ai.date >= '$currentDate' ORDER BY ai.date");
    $stmt->bind_param("s", $ssn); // si: string integer
    $stmt->execute();
    $stmt->bind_result($id,$pname,$pssn,$date,$time,$gname,$sname,$dname);

    $appointments = array();
    while ($stmt->fetch()) {
        array_push( $appointments, array("AppointmentID"=>$id, "PatientName"=>$pname, "PatientSSN"=>$pssn,"Date"=>$date,"Time"=>$time,"BranchName"=>$gname,"SubbranchName"=>$sname,"DoctorName"=>$dname) );
    }

    $stmt->close(); // close statement


    if($format == 'json') { // JSON output
        header('Content-type: application/json');
        echo json_encode(array('appointments'=>$appointments));
    }
    else { // XML output
        header('Content-type: text/xml');
        echo '<appointments>';

        foreach($appointments as $index => $appointment) {

            echo '<appointment>';
            foreach($appointment as $key => $value) {
                echo '<',$key,'>';
                echo htmlentities($value);
                echo '</',$key,'>';
            }
            echo '</appointment>';

        }

        echo '</appointments>';
    }

    $conn->close(); // close DB connection
}

?>