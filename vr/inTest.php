<?php

 $servername = "localhost";
        $username = "datavr";
        $password = "Gesture@1";
        $dbname = "datavr";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
// Get data from Unity C#
$METHOD = $_POST['METHOD'];

if($METHOD=="SEND_DATA")
{
    $ID = $_POST['ID'];
    $ENV = $_POST['ENV'];
    $TESTID = $_POST['TESTID'];
    $DT = $_POST['DT'];
    $QID = $_POST['QID'];
    $RESTIME = $_POST['RESTIME'];
    $HEADMOVEMENT = $_POST['HEADMOVEMENT'];
    $RES1 = $_POST['RES1'];
    $SCORE = $_POST['SCORE'];
    
    $sql = "INSERT INTO JosphineUserTestData (ID, ENV, TESTID, DT, QID, RESTIME, HEADMOVEMENT, RES1, SCORE) VALUES ('$ID','$ENV','$TESTID', '$DT', '$QID','$RESTIME','$HEADMOVEMENT', '$RES1', '$SCORE')";
    
    if ($conn->query($sql) === TRUE) {
      echo "New record created successfully";
    } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
    }   
}
else if($METHOD=="GET_ACTIVE_USER")
{
    $ListUserWithActiveLoginSQL = "SELECT * FROM JosphineUser WHERE LOGINSTATUS = 'Active'";
    $ListUserWithActiveLogin = $conn->query($ListUserWithActiveLoginSQL);
    if ($ListUserWithActiveLogin->num_rows > 0) 
    {
        $data = "";
        while ($row = $ListUserWithActiveLogin->fetch_assoc()) {
            $data .= "ID:" . $row["ID"] . "$";
            $data .= "NAME:" . $row["NAME"] . "$";
            $data .= "DOB:" . $row["DOB"] . "$";
            $data .= "AGE:" . $row["AGE"] . "$";
            $data .= "INSTRUCTORNAME:" . $row["INSTRUCTORNAME"] . "$";
            $data .= "LOGINSTATUS:Active" . "$";
            $data .= "ENV:" . $row["ENV"] . "$";
            $data .= "TASKID:" . $row["TASKID"] . "$";
        }
        echo $data;
    }
    else
    {
        echo "Something went wrong.";
    }
}
else if($METHOD=="TASK_COMPLETED")
{
    $ID = $_POST['ID'];
    $TASKID = $_POST['TASKID'];
    
    $TASK_COMPLETED_SQL = "UPDATE JosphineUser SET TASKID='".$TASKID."' WHERE ID='".$ID."'";
    echo "data Updated";
    $conn->query($TASK_COMPLETED_SQL);
}

$conn->close();
?>
