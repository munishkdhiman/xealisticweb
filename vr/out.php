<?php
// Check if the request method is GET
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    // Check if the ID parameter is present in the URL
    if (isset($_GET["ID"])) {
        // Get the user's ID from the URL parameter
        $userID = $_GET["ID"];

        // Replace this with your database connection logic

        $servername = "localhost";
        $username = "datavr";
        $password = "Gesture@1";
        $dbname = "datavr";


        // Create a connection to the database
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Construct the SQL query based on the user ID
        if ($userID == -1) {
            // If the userID is -1, retrieve the whole table data
            $sql = "SELECT * FROM JosphineUser";
        } else {
            // Otherwise, retrieve data for the specific user ID
            $sql = "SELECT * FROM JosphineUser WHERE ID = " . $userID;
        }
        
        $CScoreSQL = "SELECT SUM(JosphineUserTestData.SCORE) FROM JosphineUser JOIN JosphineUserTestData WHERE JosphineUserTestData.ENV = 'Cluttered' AND JosphineUser.ID = JosphineUserTestData.ID AND JosphineUser.ID = " . $userID;
        $CScoreR = $conn->query($CScoreSQL);
        $CScore="";
        if ($CScoreR->num_rows > 0) 
        {
            while ($row = $CScoreR->fetch_assoc()) 
            {
                $CScore = $row["SUM(JosphineUserTestData.SCORE)"];
            }
        }
        
        $UScoreSQL = "SELECT SUM(JosphineUserTestData.SCORE) FROM JosphineUser JOIN JosphineUserTestData WHERE JosphineUserTestData.ENV = 'Uncluttered' AND JosphineUser.ID = JosphineUserTestData.ID AND JosphineUser.ID = " . $userID;
        $UScoreR = $conn->query($UScoreSQL);
        $UScore="";
        if ($UScoreR->num_rows > 0) 
        {
            while ($row = $UScoreR->fetch_assoc()) 
            {
                $UScore = $row["SUM(JosphineUserTestData.SCORE)"];
            }
        }
        
        $TotalScoreSQL = "SELECT SUM(JosphineUserTestData.SCORE) FROM JosphineUser JOIN JosphineUserTestData WHERE JosphineUser.ID = JosphineUserTestData.ID AND JosphineUser.ID = " . $userID;
        $TotalScore = $conn->query($TotalScoreSQL);
        $Score="";
       
        if ($TotalScore->num_rows > 0) 
        {
            while ($row = $TotalScore->fetch_assoc()) 
            {
                $Score = $row["SUM(JosphineUserTestData.SCORE)"];
                
            }
        }
        
        $DateSQL = "SELECT DT FROM JosphineUserTestData WHERE ID = " . $userID;
        $TestDate = $conn->query($DateSQL);
        $Date="";
        if ($TestDate->num_rows > 0) 
        {
            while ($row = $TestDate->fetch_assoc()) 
            {
                $Date  = $row["DT"];
               
            }
        }
        
        // Execute the query and get the result
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // If there are rows in the result, fetch the data and send it as a string with <br> tags as spaces
            $data = "";
            while ($row = $result->fetch_assoc()) {
                // Concatenate the row data into the $data string, adding <br> tags as spaces between rows
                $data .= "ID:" . $row["ID"] . "$";
                $data .= "DT:" . $Date . "$";
                $data .= "NAME:" . $row["NAME"] . "$";
                $data .= "DOB:" . $row["DOB"] . "$";
                $data .= "AGE:" . $row["AGE"] . "$";
                $data .= "INSTRUCTORNAME:" . $row["INSTRUCTORNAME"] . "$";
                $data .= "LOGINSTATUS:Active" . "$";
                $data .= "CLUTTERED SCORE:" . $CScore . "$";
                $data .= "UNCLUTTERED SCORE:" . $UScore . "$";
                $data .= "TOTAL SCORE:" . $Score;
            }
            echo $data;
        } else {
            // If no data found, send a response indicating that
            echo "No data found.";
        }

        // Close the database connection
        $conn->close();
    } else {
        // If the ID parameter is missing, send an error response
        echo "Error: User ID is missing in the request.";
    }
} else {
    // If the request method is not GET, send an error response
    echo "Error: Invalid request method.";
}
?>
