<?php
    // file to be used wherever a connection to the log file is to be closed

    
    $closeLogFile = fclose($openLogFile);

    if(!$closeLogFile){
        $time = date("D j-M-Y, h:i:s");
        fwrite($openLogFile, "\n[$time] Close operation failed for file 'log.txt' ");
    }
?>