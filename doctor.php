
<?php
if(isset($_POST['branch'])) {
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
    $branch = $_POST['branch'];

    // prepare, bind and execute SQL statement
    $stmt = $conn->prepare("SELECT tc,name,surname FROM `doctor` WHERE 1");
  //  $stmt->bind_param("ss", $patientSSN,$patientPassword); // si: string integer
    $stmt->execute();
    $stmt->bind_result($doctTC,$doctName,$doctSurname);

    $patients = array();
    while ($stmt->fetch()) {
        array_push( $patients, array("TC"=>$doctTC, "Name"=>$doctName,"Surname"=>$doctSurname) );
    }
    $stmt->close(); // close statement


    if($format == 'json') { // JSON output
        header('Content-type: application/json');
        echo json_encode(array("Doctors"=>$patients));
    }
    else { // XML output
        header('Content-type: text/xml');
        echo '<Patients>';

        foreach($patients as $index => $patient) {

            echo '<Patient>';
            foreach($patient as $key => $value) {
                echo '<',$key,'>';
                echo htmlentities($value);
                echo '</',$key,'>';
            }
            echo '</Patient>';

        }

        echo '</Patients>';
    }

    $conn->close(); // close DB connection
}
?>