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
         

    //function to retrieve specific item form specific store
    function retrieveProductOccurrence($rfid, $storeID, $conn, $openLogFile){
        $retrieveOccurrenceQuery = "SELECT occurrenceID, rfid, barcode, price, booked, VAT FROM product_occurrence WHERE storeID='$storeID' && rfid='$rfid'";
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
        $retrievalOccurrencesQuery = "SELECT occurrenceID, barcode, storeID, price from product_occurrence WHERE barcode='$barcode'";
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

            $returnValue[$n] = array("occurrenceID"=>$occurrence['occurrenceID'], "barcode" => $occurrence['barcode'], "product_name" => $product['product_title'], "product_description" => $product['product_description'], "price" => $occurrence['price'], "store" => $store['store_name']);
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
<<<<<<< HEAD
    function markPaid($rfid, $invoice_number, $conn, $openLogFile){
        $markPaid = "UPDATE product_occurrence SET paid='1', invoice_number='$invoice_number' WHERE rfid='$rfid' ";
=======
    function markPaid($rfid, $conn, $openLogFile){
        $markPaid = "UPDATE product_occurrence SET paid='1' WHERE rfid='$rfid' ";
>>>>>>> 33097456658835eac6c02337e3cfa43b5eac2ac1
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
?>