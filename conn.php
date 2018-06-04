<?php

    // This file is to inluded using include or require wherever 'taara' database connection is needed

    //logfileconnection
    include("open_log_file.php");

    // Database connection credentials
<<<<<<< HEAD
    $dbUser="artscirc_android";
    $dbPassword="ndiSho16";
    $dbHost="localhost:3306";
    $dbName="artscirc_taara";
=======
    $dbUser="root";
    $dbPassword="";
    $dbHost="localhost";
    $dbName="taara";
>>>>>>> 33097456658835eac6c02337e3cfa43b5eac2ac1

    $conn = mysqli_connect($dbHost, $dbUser, $dbPassword, $dbName);

    if(!$conn){
        $logMessage = "Database Connection Failed: " + mysqli_connect_error($conn);
        writeLog($logMessage, $openLogFile);
        die();
    }

    else{
        $logMessage = "Database connection success for taara";
        writeLog($logMessage, $openLogFile);
    }
?>