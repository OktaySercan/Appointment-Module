<?php
session_start();
if(isset($_POST['format'])){

    $patientSSN = "";
    $patientName = "";
    foreach ($_SESSION['activePatient'] as $item) {
       $patientSSN =  $item['patientssn'];
       $patientName =  $item['patientname'];
    }
    $format = $_POST['format'];
    $activePatient = array();

    array_push( $activePatient, array("PatientSSN"=>$patientSSN,"PatientName"=>$patientName) );

    if($format == 'json') { // JSON output
        header('Content-type: application/json');
        echo json_encode(array('ActivePatient'=>$activePatient));
    }
    else { // XML output
        header('Content-type: text/xml');
        echo '<ActivePatient>';

        foreach($activePatient as $index => $patient) {

            echo '<Patient>';
            foreach($patient as $key => $value) {
                echo '<',$key,'>';
                echo htmlentities($value);
                echo '</',$key,'>';
            }
            echo '</Patient>';

        }

        echo '</ActivePatient>';
    }
    exit();
}
?>