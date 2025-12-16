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
if ($METHOD == "SEND_DATA")
{
    $NAME = $_POST['NAME'];
    $DOB = $_POST['DOB'];
    $AGE = $_POST['AGE'];
    $INSTRUCTORNAME = $_POST['INSTRUCTORNAME'];
    $LOGINSTATUS = $_POST['LOGINSTATUS'];
    
    $checkIfUserAlreadyExistSql = "SELECT ID FROM JosphineUser WHERE NAME = '$NAME' AND DOB = '$DOB' AND INSTRUCTORNAME = '$INSTRUCTORNAME'";
    $checkIfUserAlreadyExist = $conn->query($checkIfUserAlreadyExistSql);
    if ($checkIfUserAlreadyExist->num_rows > 0) 
    {
        $ID = "";
        while ($row = $checkIfUserAlreadyExist->fetch_assoc()) {
            // Concatenate the row data into the $data string, adding <br> tags as spaces between rows
            $ID = 'User Already Exist!. Your ID is #' . $row["ID"];
        }
        echo $ID;
    }
    else
    {
        $sql = "INSERT INTO JosphineUser (NAME, DOB, AGE, INSTRUCTORNAME, LOGINSTATUS, TASKID) VALUES ('$NAME','$DOB','$AGE', '$INSTRUCTORNAME', '$LOGINSTATUS', 'C=0%U=0')";
        if ($conn->query($sql) === TRUE) {
            $GetUserIDSQL = "SELECT ID FROM JosphineUser WHERE NAME = '$NAME' AND DOB = '$DOB' AND INSTRUCTORNAME = '$INSTRUCTORNAME'";
            $GetUserID = $conn->query($GetUserIDSQL);
            if ($GetUserID->num_rows > 0) 
            {
                $ID = "";
                while ($row = $GetUserID->fetch_assoc()) {
                    // Concatenate the row data into the $data string, adding <br> tags as spaces between rows
                    $ID = 'New record created successfully. #' . $row["ID"];
                }
                echo $ID;
            }
            else
            {
                echo "Error: Something went wrong";
            }
        } else {
          echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
else if($METHOD == "DISABLE_LOGIN_ACTIVE")
{
    $ListUserWithActiveLoginSQL = "SELECT ID FROM JosphineUser WHERE LOGINSTATUS = 'Active'";
    $ListUserWithActiveLogin = $conn->query($ListUserWithActiveLoginSQL);
    if ($ListUserWithActiveLogin->num_rows > 0) 
    {
        while ($row = $ListUserWithActiveLogin->fetch_assoc()) {
            $ID = $row["ID"];
            $ActiveUserSQL = "UPDATE JosphineUser SET LOGINSTATUS = 'Deactive', ENV = '' WHERE ID = '$ID'";
            $conn->query($ActiveUserSQL);
        }
    }
}
else if($METHOD == "SELECT_CLASSROOM_ENV")
{
    $ID = $_POST['ID'];
    $ENV = $_POST['ENV'];
    
    $UpdateLoginStatusSQL = "UPDATE JosphineUser SET LOGINSTATUS = 'Active', ENV = '$ENV' WHERE ID = '$ID'";
    $UpdateLoginStatus = $conn->query($UpdateLoginStatusSQL);
}
else if($METHOD == "Task 1 - Interaction" || $METHOD == "Task 2 - Affect" || $METHOD == "Task 3 - Modulation" || $METHOD == "Task 4 - Chunking" || $METHOD == "Task 5 - Attention" || $METHOD == "Task 6 – Prosody")
{
    $SQL = $_POST['SQL'];
    
    $SQL_RESULT = $conn->query($SQL);
    if ($SQL_RESULT->num_rows > 0) 
    {
        $data = "";
        while ($row = $SQL_RESULT->fetch_assoc()) {
            $data .= $row["QID"] . ":" . $row["RESTIME"] . ":" . $row["SCORE"] . ":" . $row["HEADMOVEMENT"] . "$";
        }
        echo $data;
    }
    else 
    {
        echo "No Record found ";
    }
}
else if($METHOD == "CPT-DX Test")
{
    $SQL = $_POST['SQL'];
    
    $SQL_RESULT = $conn->query($SQL);
    if ($SQL_RESULT->num_rows > 0) 
    {
        $data = "";
        while ($row = $SQL_RESULT->fetch_assoc()) {
           // $data .= $row["SUM(RESTIME)"] . ":" . $row["COUNT(*)"] . ":" . $row["MAX(HEADMOVEMENT)"] . "$";
            $data .= $row["QID"] . ":" . $row["RESTIME"] . ":" . $row["SCORE"] . ":" . $row["HEADMOVEMENT"] . "$";
        }
        echo $data;
    }
    else 
    {
        echo "No Record found ";
    }
}
else if($METHOD == "Overall Data Display")
{
    $ID = $_POST['ID'];
    $ENV= $_POST['ENV'];
    $SQL = "SELECT DT, SUM(SCORE) FROM `JosphineUserTestData` WHERE ID = " . $ID . " AND ENV = '" . $ENV . "' AND TESTID = \"cpt\" 
            UNION ALL
            SELECT DT, SUM(SCORE) FROM `JosphineUserTestData` WHERE ID = " . $ID . " AND ENV = '" . $ENV . "' AND (RES1 = \"question\" OR RES1 = \"statement\")
            UNION ALL
            SELECT DT, SUM(SCORE) FROM `JosphineUserTestData` WHERE ID = " . $ID . " AND ENV = '" . $ENV . "' AND (RES1 = \"happy\" OR RES1 = \"sad\")
            UNION ALL
            SELECT DT, SUM(SCORE) FROM `JosphineUserTestData` WHERE ID = " . $ID . " AND ENV = '" . $ENV . "' AND (RES1 = \"same\" OR RES1 = \"different\")
            UNION ALL
            SELECT DT, SUM(SCORE) FROM `JosphineUserTestData` WHERE ID = " . $ID . " AND ENV = '" . $ENV . "' AND (RES1 = \"2\" OR RES1 = \"3\")
            UNION ALL
            SELECT DT, SUM(SCORE) FROM `JosphineUserTestData` WHERE ID = " . $ID . " AND ENV = '" . $ENV . "' AND (RES1 = \"polite\" OR RES1 = \"demanding\")
            UNION ALL
            SELECT DT, SUM(SCORE) FROM `JosphineUserTestData` WHERE ID = " . $ID . " AND ENV = '" . $ENV . "' AND (RES1 = \"loud\" OR RES1 = \"quite\")";
    
    $SQL_RESULT = $conn->query($SQL);
    if ($SQL_RESULT->num_rows > 0) 
    {
        $data = "";
        while ($row = $SQL_RESULT->fetch_assoc()) {
            $data .= $row["DT"] . ":" . $row["SUM(SCORE)"] . "$";
        }
        echo $data;
    }
    else 
    {
        echo "No Record found ";
    }
}

$conn->close();
?>
