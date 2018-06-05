<?php
echo "hello";
include("open_log_file.php");
writeLog("Ipay call back beginning", $openLogFile);
echo "hello";
$val = "demo"; //assigned iPay Vendor ID... hard code it here.
/*
these values below are picked from the incoming URL and assigned to variables that we
will use in our security check URL
*/
writeLog("getting values", $openLogFile);
$val1 = $_GET["id"];
$val2 = $_GET["ivm"];
$val3 = $_GET["qwh"];
$val4 = $_GET["afd"];
$val5 = $_GET["poi"];
$val6 = $_GET["uyt"];
$val7 = $_GET["ifd"];
writeLog("gotten values", $openLogFile);

$ipnurl = "https://www.ipayafrica.com/ipn/?vendor=".$val."&id=".$val1."&ivm=".
$val2."&qwh=".$val3."&afd=".$val4."&poi=".$val5."&uyt=".$val6."&ifd=".$val7;
writeLog("url formed", $openLogFile);
$fp = fopen($ipnurl, "rb");
writeLog("opening url", $openLogFile);
$status = stream_get_contents($fp, -1, -1);
writeLog("completing", $openLogFile);
writeLog("... $status", $openLogFile);

fclose($fp);


?>