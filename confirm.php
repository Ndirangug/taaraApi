<?php
    include("conn.php");
    writeLog("Ipay call back beginning", $openLogFile);
    
     $val = "demo"; //assigned iPay Vendor ID... hard code it here.
    //  /*
    //  these values below are picked from the incoming URL and assigned to variables that we
    //  will use in our security check URL
    //  */
    writeLog("getting values", $openLogFile);
     $val1 = $_GET["id"];
     $val2 = $_GET["ivm"];
     $val3 = $_GET["qwh"];
     $val4 = $_GET["afd"];
     $val5 = $_GET["poi"];
     $val6 = $_GET["uyt"];
     $val7 = $_GET["ifd"];
    
$itemIds = explode(",",  $_GET['p1']);
$userid= $_GET['p2'];
$invoice_number = $_GET['p3'];
$amount = $_GET['mc'];
$transactionCode = $_GET['txncd'];
$registeredNumber = $_GET['msisdn_idnum'];
$postedNumber = $_GET['msisdn_custnum'];
$customerName = $_GET['msisdn_id'];
$status = $_GET['status'];


<<<<<<< HEAD
<<<<<<< HEAD
// $ipnurl = "https://www.ipayafrica.com/ipn/?vendor=".$val."&id=".$val1."&ivm=".$val2."&qwh=".$val3."&afd=".$val4."&poi=".$val5."&uyt=".$val6."&ifd=".$val7;
// $fp = fopen($ipnurl, "rb");
// $status = stream_get_contents($fp, -1, -1);
=======
//$ipnurl = "https://www.ipayafrica.com/ipn/?vendor=".$val."&id=".$val1."&ivm=".$val2."&qwh=".$val3."&afd=".$val4."&poi=".$val5."&uyt=".$val6."&ifd=".$val7;
//$fp = fopen($ipnurl, "rb");
//$status = stream_get_contents($fp, -1, -1);
>>>>>>> 33097456658835eac6c02337e3cfa43b5eac2ac1
=======
//$ipnurl = "https://www.ipayafrica.com/ipn/?vendor=".$val."&id=".$val1."&ivm=".$val2."&qwh=".$val3."&afd=".$val4."&poi=".$val5."&uyt=".$val6."&ifd=".$val7;
//$fp = fopen($ipnurl, "rb");
//$status = stream_get_contents($fp, -1, -1);
>>>>>>> 33097456658835eac6c02337e3cfa43b5eac2ac1
include("retrieveItem.php");
switch ($status) {
    case 'aei7p7yrx4ae34':
        #success
        writeLog("item ".$itemIds[0].$itemIds[1], $openLogFile);
        foreach ($itemIds as $key => $value) {
        writeLog("attempt 1 $value", $openLogFile);
<<<<<<< HEAD
<<<<<<< HEAD
          markPaid($value, $invoice_number, $conn, $openLogFile); 
=======
          markPaid($value, $conn, $openLogFile); 
>>>>>>> 33097456658835eac6c02337e3cfa43b5eac2ac1
=======
          markPaid($value, $conn, $openLogFile); 
>>>>>>> 33097456658835eac6c02337e3cfa43b5eac2ac1
        }

        $query = "INSERT INTO checkouts (invoice_number, amount, userID)VALUES ('$invoice_number', '$amount', '$userid') ";
        $result = mysqli_query($conn, $query);

        if ($result) {
             writeLog("query successful checkoutId: $invoice_number amount: $amount userId: $userid ", $openLogFile);
        }
        else {
            writeLog("query failed checkoutId: $invoice_number amount: $amount userId: $userid ".mysqli_error($conn), $openLogFile);
        }
        break;
    
    case 'fe2707etr5s4wq':
        writeLog("payment failed invoice number: $invoice_number amount: $amount userId: $userid ", $openLogFile);
        break;

    case 'bdi6p2yy76etrs':
         writeLog("payment pending invoice number: $invoice_number amount: $amount userId: $userid ", $openLogFile);
         break;   

    case 'eq3i7p5yt7645e':
        writeLog("undefined failure invoice number: $invoice_number amount: $amount userId: $userid ", $openLogFile);
        break;
         
    default:
      writeLog("unprecedented Failure $invoice_number amount: $amount userId: $userid ", $openLogFile);
        break;
}
writeLog("completing... ", $openLogFile);
    writeLog("completing... ", $openLogFile);
    // fclose($fp);
?>