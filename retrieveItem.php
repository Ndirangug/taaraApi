<?php

        // ----------------------------------------------------------------------
        //                          ITEM RETRIEVAL
        //  -------------------------------------------------------------    
        //  This script does the following:
        //  -retrieve items with specific barcode from all supermarkets
        //  -retrieve specific item from specific store
        //  -check if item is paid using rfid as selection parameter
        //  -mark item as paid
        //  -mark item as booked
         
        define("VAT_RATE", 0.16);

    //function to retrieve specific item form specific store
    function retrieveProductOccurrence($rfid, $storeID, $conn, $openLogFile){
        $retrieveOccurrenceQuery = "SELECT * FROM product_occurrence WHERE storeID='$storeID' && rfid='$rfid'";
        $executeRetrieve = mysqli_query($conn, $retrieveOccurrenceQuery);
        $returnValue;
        if(mysqli_num_rows($executeRetrieve) > 0){
            $record = mysqli_fetch_assoc($executeRetrieve);
            writeLog($record['occurrenceID']." retrieved", $openLogFile);

            $retrieveProductInfo = "SELECT product_title, product_description FROM product WHERE barcode='$record[barcode]' ";
            $executeProductInfoQuery = mysqli_query($conn, $retrieveProductInfo);

            if(mysqli_num_rows($executeProductInfoQuery) > 0){
                $product = mysqli_fetch_assoc($executeProductInfoQuery);
                writeLog($record['occurrenceID']." ".$product['product_title']. "retrieved", $openLogFile);
                $returnValue = [$record['occurrenceID'],$record['barcode'], $record['price'],$record['rfid'],$record['VAT'],$product['product_title'],  $product['product_description']];
            }

            else{
                $returnValue = ["Product details not foundrfid:".$rfid." storeId:".$storeID.mysqli_error($conn)];
                writeLog("Product details not foundrfid:".$rfid." storeId:".$storeID.mysqli_error($conn), $openLogFile);
            }
            
        }

        else{
            writeLog("Product occurrence not found rfid:".$rfid." storeId:".$storeID.mysqli_error($conn), $openLogFile);
            $returnValue = ["Product occurrence not foundrfid:".$rfid." storeId:".$storeID.mysqli_error($conn)];
        }

        return json_encode($returnValue);
    }

    //function to check if productoccurrence paid for
    function isPaid($rfid, $conn, $openLogFile){

        $returnValue;
        $retrievalQuery = "SELECT paid, barcode FROM product_occurrence WHERE rfid ='$rfid' ";
        $executeRetrieval = mysqli_query($conn, $retrievalQuery);

        if(mysqli_num_rows($executeRetrieval) > 0){
            $item = mysqli_fetch_assoc($executeRetrieval);
            writeLog($item['barcode']." retrieved successfully", $openLogFile);

            if($item['paid'] == 1){
                $returnValue = 1;
            }

            else {
                $returnValue = 0;
            }
        }
        else {
            writeLog("Item retrieval failure: ".mysqli_error($conn), $openLogFile);
        }

        return json_encode($returnValue);
    }

    //function to retrieve all items with certain barcode
    function productCompareRetrieve($barcode, $conn, $openLogFile){
        $retrievalOccurrencesQuery = "SELECT * from product_occurrence WHERE barcode='$barcode'";
        $returnValue;

        $n = 0;
        $executeOccurrenceRetrieval = mysqli_query($conn, $retrievalOccurrencesQuery);
        while($occurrence = mysqli_fetch_assoc($executeOccurrenceRetrieval)){
            
            writeLog("Product occurrence ".$occurrence['occurrenceID']." successfully retrieved", $openLogFile);
            
            $product;
            $productSelectNameQuery = "SELECT product_title, product_description from product WHERE barcode = '$occurrence[barcode]'";
            $executeProductNameQuery = mysqli_query($conn, $productSelectNameQuery);
            if(mysqli_num_rows($executeProductNameQuery) > 0){
               $product = mysqli_fetch_assoc($executeProductNameQuery);
               writeLog($product['product_title']." successfully retrieved", $openLogFile);
            }

            else{
                writeLog("Product retrieval failed: ".mysqli_error($conn), $openLogFile);
                $returnValue = ["Product retrieval failed: ".mysqli_error($conn)];
                die("error");
            }
            
            $store;
            $retrievalStoreName = "SELECT store_name from stores WHERE storeID='$occurrence[storeID]'";
            $executeStoreRetrieval = mysqli_query($conn, $retrievalStoreName);
            if(mysqli_num_rows($executeStoreRetrieval) > 0){
                $store = mysqli_fetch_assoc($executeStoreRetrieval);
                writeLog($store['store_name']." successfully retrieved", $openLogFile);
            }

            else {
                writeLog("Storename for storeid ".$occurrence['storeID']." failed: ".mysqli_error($conn), $openLogFile);
                $returnValue = ["Storename for storeid ".$occurrence['storeID']." failed: ".mysqli_error($conn)];
            }

            $returnValue[$n] = [$occurrence['occurrenceID'], $occurrence['barcode'], $product['product_title'], $product['product_description'], $occurrence['price'],  $store['store_name'], $occurrence['VAT']];
            $n++;
        }
       
        return json_encode($returnValue);
    }

    // //function to mark item as booked
    // function bookItem($rfid, $storeID, $conn, $openLogFile){
    //     $bookItemQuery = "UPDATE product_occurrence SET booked='1' WHERE rfid='$rfid' && storeID = '$storeID' ";
    //     $executeBookItemQuery = mysqli_query($conn, $bookItemQuery);
    //     $returnValue;
    //     if ($executeBookItemQuery) {
    //         $returnValue = [1];
    //         writeLog("Item rfid $rfid successfully booked", $openLogFile);
    //     }

    //     else {
    //         writeLog("Item $rfid booking failure", $openLogFile);
    //         $returnValue = [0];
    //     }

    // return json_encode($returnValue);
    // }

    //function to mark item as unpaid
    function markUnPaid($rfid, $conn, $openLogFile){
        $markUnpaid = "UPDATE product_occurrence SET paid='0' WHERE rfid='$rfid' ";
        $executeMarkUnpaid = mysqli_query($conn, $markUnpaid);
        $returnValue;
        if ($executeMarkUnpaid) {
            $returnValue = 1;
            writeLog("Item rfid $rfid successfully marked unpaid", $openLogFile);
        }

        else {
            writeLog("Item $rfid unpayment failure", $openLogFile);
            $returnValue = 0;
        }

    return json_encode($returnValue);
    }

    //function to unbook item
    function unBookItem($rfid, $conn, $openLogFile){
        $unBookItemQuery = "UPDATE product_occurrence SET booked='0' WHERE rfid='$rfid' ";
        $executeUnBookItemQuery = mysqli_query($conn, $unBookItemQuery);
        $returnValue;
        if ($executeUnBookItemQuery) {
            $returnValue = 1;
            writeLog("Item rfid $rfid successfully unbooked", $openLogFile);
        }

        else {
            writeLog("Item $rfid unbooking failure", $openLogFile);
            $returnValue = 0;
        }

    return json_encode($returnValue);
    }

    //function to mark item as paid
    function markPaid($occurrenceID, $invoice_number, $conn, $openLogFile){
        $markPaid = "UPDATE product_occurrence SET paid='1', invoice_number='$invoice_number' WHERE occurrenceID = '$occurrenceID'  ";
        $markPaidQuery = mysqli_query($conn, $markPaid);
        $returnValue;
        if ($markPaidQuery) {
            $returnValue = 1;
            writeLog("Item rfid $rfid successfully paid", $openLogFile);
        }

        else {
            writeLog("Item $rfid payment failure", $openLogFile);
            $returnValue = 0;
        }

    return json_encode($returnValue);
    }

    //retrieve all items with certain invoice number and generate invoice
    function generateInvoice($invoice_number, $phoneNumber, $conn, $openLogFile){
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

              $returnValue[$i] = [$productBoughtDetails['product_title'], $productBoughtDetails['product_description'], $price, $vat ];
            $i++;
           }

           $checkoutDetailsQuery = "SELECT time, amount FROM checkouts WHERE invoice_number = '$invoice_number'";
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
        }else {
            writeLog("Paid products retieval failed".mysqli_error($conn), $openLogFile);
            die();
        }

        return $returnValue;
    }
    
?>
