<?php

    // This file is to inluded using include or require wherever 'taara' database connection is needed

    //logfileconnection
    include("open_log_file.php");

    // Database connection credentials
    $dbUser="artscirc_android";
    $dbPassword="ndiSho16";
    $dbHost="localhost";
    $dbName="artscirc_taara";

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