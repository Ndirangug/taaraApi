<?php
    // file to be used wherever a connection to the log file is needed
<<<<<<< HEAD
    
=======

>>>>>>> 33097456658835eac6c02337e3cfa43b5eac2ac1
    $path = realpath("log.txt");
    $openLogFile = fopen($path, "a+");

    if (is_resource($openLogFile)){
        writeLog("File 'log.txt' opened successfully", $openLogFile);
    }
    else{
        echo "Log.txt failed to open";
    }

    //custom function to write a log message to the log file
    function writeLog($message, $logFile){
<<<<<<< HEAD
<<<<<<< HEAD
    date_default_timezone_set("Africa/Nairobi");
=======
        date_default_timezone_set("Africa/Nairobi");
>>>>>>> 33097456658835eac6c02337e3cfa43b5eac2ac1
=======
        date_default_timezone_set("Africa/Nairobi");
>>>>>>> 33097456658835eac6c02337e3cfa43b5eac2ac1
        $time = date("D j-M-Y, h:i:s a");
        fwrite($logFile, "\n[$time] $message"); 
    }
?>