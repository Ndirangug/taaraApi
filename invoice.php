<?php

    /* invoice array sample
   
            Array (
                [0] => Array                                            
                    (
                        [title] => Daily Nation                                    
                        [description] => Friday, May 4 2018                               
                        [price] => 59                                               
                        [calculated VAT] => 9.44  
                        [price+vat] => 68.44                                           
                    )

                [1] => Array                                            item2
                    (
                        [title] => Daily Nation
                        [description] => Friday, May 4 2018
                        [price] => 60
                        [calculated VAT] => 9.6
                        [price+vat] => 69.6
                    )

                [2] => Array                                            item3
                    (
                        [title] => Kartasi Exercise Book
                        [description] => 200 pages, ruled, A5 hardcover
                        [price] => 60
                        [calculated VAT] => 9.6
                        [price+vat] => 69.6
                    )

                [3] => the mall(westlands)                              store
                [4] => 2018-05-31 13:34:58                              time
                [5] => 350                                              toal paid
                [6] => service charge                                   service charge
            ) */

    //retrieve all items with certain invoice number and generate invoice
    function getInvoiceData($invoice_number, $phoneNumber, $conn, $openLogFile){
        $returnValue;
    
        $retrieveOccurrencesquery = "SELECT * FROM product_occurrence WHERE invoice_number = '$invoice_number'";
        $executeRetrieveOccurrencesquery = mysqli_query($conn, $retrieveOccurrencesquery);

        if (mysqli_num_rows($executeRetrieveOccurrencesquery) > 0) {
            $i = 0;
            $productBoughtDetails;
            $storeDetails;
            $checkoutDetails;

           while ($productBought = mysqli_fetch_assoc($executeRetrieveOccurrencesquery)) {
              $productDetailsQuery = "SELECT product_title, product_description FROM product WHERE barcode = '$productBought[barcode]'";
              $executeProductDetailsQuery = mysqli_query($conn, $productDetailsQuery);

              if (mysqli_num_rows($executeProductDetailsQuery) > 0) {
                  $productBoughtDetails = mysqli_fetch_assoc($executeProductDetailsQuery);
                  array_push($productBoughtDetails, $productBought['price']);
                  array_push($productBoughtDetails, $productBought['VAT']);
                  
                  $storeNameQuery = "SELECT store_name FROM stores WHERE storeID = '$productBought[storeID]'";
                  $executestoreNameQuery = mysqli_query($conn, $storeNameQuery);

                  if (mysqli_num_rows($executestoreNameQuery) > 0) {
                      $storeDetails = mysqli_fetch_assoc($executestoreNameQuery);
                    
                      
                  }else{
                      writeLog("store name retrieval failed ".mysqli_error($conn), $openLogFile);
                      die();
                  }
              }else {
                  writeLog("product bought details retieval failed".mysqli_error($conn), $openLogFile);
                  die();
              }
              $price = $productBought['price'];
              $vat = "0";

              if ($productBought['VAT'] == "16") {
                  $vat = VAT_RATE * doubleval($price);
              }

              $returnValue[$i] = [$productBoughtDetails['product_title'], $productBoughtDetails['product_description'], $price, $vat, $price+$vat ];
            $i++;
           }

           $checkoutDetailsQuery = "SELECT time, amount, service_charge FROM checkouts WHERE invoice_number = '$invoice_number'";
           $executecheckoutDetailsQuery = mysqli_query($conn, $checkoutDetailsQuery);

           if (mysqli_num_rows($executecheckoutDetailsQuery) > 0) {
               $checkoutDetails = mysqli_fetch_assoc($executecheckoutDetailsQuery);
               
           }else{
              writeLog("checkout details retieval failed".mysqli_error($conn), $openLogFile);
            die(); 
           }

           array_push($returnValue, $storeDetails['store_name']);
           array_push($returnValue, $checkoutDetails['time']);
           array_push($returnValue, $checkoutDetails['amount']);
           array_push($returnValue, $checkoutDetails['service_charge']);
        }else {
            writeLog("Paid products retieval failed".mysqli_error($conn), $openLogFile);
            die();
        }

        return $returnValue;
    }

    function generatePdfInvoice($invoice_number, $phoneNumber, $conn, $openLogFile){
        $invoiceData = getInvoiceData($invoice_number, $phoneNumber, $conn, $openLogFile);
        $timeGeneGenerated = date("D j-M-Y, h:i:s a");
        $dateOfPurchase = date("D j-M-Y, h:i:s a", strtotime($invoiceData[count($invoiceData) - 3]));
        $serviceCharge = $invoiceData[count($invoiceData) - 1];
        $totalPaid = $invoiceData[count($invoiceData) - 2];
        $storeName = $invoiceData[count($invoiceData) - 4];
       
        require('../fpdf/fpdf.php');

        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(40,10, "genetated: $timeGeneGenerated\n Purchased: $dateOfPurchase by  +$phoneNumber");
        
        $pdf->Output();

        return $invoiceData;
    }

    function smsInvoice($invoice_number, $phoneNumber, $conn, $openLogFile){
        $invoiceData = getInvoiceData($invoice_number, $phoneNumber, $conn, $openLogFile);
        $timeGeneGenerated = date("D j-M-Y, h:i:s a");
        $dateOfPurchase = date("D j-M-Y, h:i:s a", strtotime($invoiceData[count($invoiceData) - 3]));
        $serviceCharge = $invoiceData[count($invoiceData) - 1];
        $totalPaid = $invoiceData[count($invoiceData) - 2];
        $storeName = $invoiceData[count($invoiceData) - 4];

        //Sending Messages using sender id/short code
        require_once('AfricasTalkingGateway.php');
        $username   = "sandbox";
        $apikey     = "d3e167277949411edb0370f73cbe5b5b94bc7a6b900ea7c8c2b4612e2e2c9fcb";
        $recipients = "+254746649576, +254724622360";
        $message    = "$storeName $serviceCharge $dateOfPurchase $timeGeneGenerated";
        // Specify your AfricasTalking shortCode or sender id
        $from = "Taara";
        $gateway    = new AfricasTalkingGateway($username, $apikey);
        try 
        {
        $results = $gateway->sendMessage($recipients, $message, $from);
        foreach($results as $result) {
            echo " Number: " .$result->number;
            echo " Status: " .$result->status;
            echo " StatusCode: " .$result->statusCode;
            echo " MessageId: " .$result->messageId;
            echo " Cost: "   .$result->cost."\n";
        }
        }
        catch ( AfricasTalkingGatewayException $e )
        {
        echo "Encountered an error while sending: ".$e->getMessage();
        }
        // DONE!!! 
    }
?>