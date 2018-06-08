<?php
    include("conn.php");
    writeLog("Ipay call back beginning", $openLogFile);
    
     $val = "demo"; //assigned iPay Vendor ID... hard code it here.
    //  /*
    //  these values below are picked from the incoming URL and assigned to variables that we
    //  will use in our security check URL
    //  */
    define("SERVICE_FEE_RATE", 0.04);
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
$sendInvoiceToPhone = $_GET['p4'];
$transactionCode = $_GET['txncd'];
$registeredNumber = $_GET['msisdn_idnum'];
$postedNumber = $_GET['msisdn_custnum'];
$customerName = $_GET['msisdn_id'];
$status = $_GET['status'];
$subTotal = $amount / (1 + SERVICE_FEE_RATE);
$serviceCharge = $subTotal * SERVICE_FEE_RATE;


// $ipnurl = "https://www.ipayafrica.com/ipn/?vendor=".$val."&id=".$val1."&ivm=".$val2."&qwh=".$val3."&afd=".$val4."&poi=".$val5."&uyt=".$val6."&ifd=".$val7;
// $fp = fopen($ipnurl, "rb");
// $status = stream_get_contents($fp, -1, -1);
include("retrieveItem.php");
switch ($status) {
    case 'aei7p7yrx4ae34':
        #success
        writeLog("item ".$itemIds[0].$itemIds[1], $openLogFile);
       

        $query = "INSERT INTO checkouts (invoice_number, amount, userID, service_charge)VALUES ('$invoice_number', '$amount', '$userid', '$serviceCharge') ";
        $result = mysqli_query($conn, $query);

        if ($result) {
             writeLog("query successful checkoutId: $invoice_number amount: $amount userId: $userid ", $openLogFile);
             
             foreach ($itemIds as $key => $value) {
                writeLog("attempt 1 $value", $openLogFile);
                markPaid($value, $invoice_number, $conn, $openLogFile); 
             }

             if ($sendInvoiceToPhone) {
                 header("location:index.php?android_api_call=invoiceToPhone&phone=$postedNumber&invoice_number=$invoice_number");
             }
             
        } else {
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