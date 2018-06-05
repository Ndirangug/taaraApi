<?php

    function retrieveRecents($userId, $conn, $openLogFile){
        $retrieveUserCheckouts = "SELECT * FROM checkouts WHERE userID = '$userId'";
        $executeRetrieveCheckouts = mysqli_query($conn, $retrieveUserCheckouts);
        $checkouts;
        $product_occurrence;
        $returnValue;
         

        if (mysqli_num_rows($executeRetrieveCheckouts) > 0) {
            $j = 0;
            while ($checkouts =  mysqli_fetch_assoc($executeRetrieveCheckouts) ) {
             $invoice_number = $checkouts['invoice_number'];
              $productOccurrenceQuery = "SELECT * FROM product_occurrence WHERE invoice_number = '$invoice_number'";
              $executeProductOccurrenceQuery = mysqli_query($conn, $productOccurrenceQuery);
              $num_rows = mysqli_num_rows($executeProductOccurrenceQuery);

              if ($num_rows > 0) {
                  for ($i=0; $i < $num_rows; $i++) { 
                     $product_occurrence[$j] = mysqli_fetch_assoc($executeProductOccurrenceQuery);
                     
                     $j++;
                  }
                   
              }else {
                  writeLog("Product occurrence retrieval failed".mysqli_error($conn), $openLogFile);
                  print_r($checkouts);
                  print_r($executeProductOccurrenceQuery);
                  die();
              }
             
                
               
          }

        }else {
            writeLog("User checkouts rertieval failed".mysqli_error($conn), $openLogFile);
            die();
         }

        

         $randomResult[0] = $product_occurrence[rand(1, count($product_occurrence)) - 1 ];
         $randomResult[1] = $product_occurrence[rand(1, count($product_occurrence)) - 1 ];

        

         $returnValue = json_encode([
             [$randomResult[0]['price'], $randomResult[0]['rfid'], $randomResult[0]['VAT']],
             [$randomResult[1]['price'], $randomResult[1]['rfid'], $randomResult[1]['VAT']]
             
         ]);

        return $returnValue;


        
    }

    function allRecents($userId, $conn, $openLogFile){
        $retrieveUserCheckouts = "SELECT * FROM checkouts WHERE userID = '$userId'";
        $executeRetrieveCheckouts = mysqli_query($conn, $retrieveUserCheckouts);
        $checkouts;
        $product_occurrence;
        $returnValue;
         

        if (mysqli_num_rows($executeRetrieveCheckouts) > 0) {
            $j = 0;
            while ($checkouts =  mysqli_fetch_assoc($executeRetrieveCheckouts) ) {
             $invoice_number = $checkouts['invoice_number'];
             $checkoutTime = $checkouts['time'];
              $productOccurrenceQuery = "SELECT * FROM product_occurrence WHERE invoice_number = '$invoice_number'";
              $executeProductOccurrenceQuery = mysqli_query($conn, $productOccurrenceQuery);
              $num_rows = mysqli_num_rows($executeProductOccurrenceQuery);

              if ($num_rows > 0) {
                  for ($i=0; $i < $num_rows; $i++) { 
                     $product_occurrence[$j] = mysqli_fetch_assoc($executeProductOccurrenceQuery);
                     $storeIdretrieved = $product_occurrence[$j]['storeID'];
                     $barcodeRetrieved = $product_occurrence[$j]['barcode'];
                     $selectStoreName  = "SELECT store_name FROM stores WHERE storeID='$storeIdretrieved'";
                     $selectProductName= "SELECT product_title, product_description FROM product WHERE barcode = '$barcodeRetrieved'";
                     
                     $executeselectStoreName = mysqli_query($conn, $selectStoreName);
                     $excuteselectProductName = mysqli_query($conn, $selectProductName);
                     
                     if (mysqli_num_rows($executeselectStoreName) > 0 && mysqli_num_rows($excuteselectProductName) > 0 ) {
                         $prodcutName = mysqli_fetch_assoc($excuteselectProductName);
                         $storename = mysqli_fetch_assoc($executeselectStoreName);

                         array_push($product_occurrence[$j], $storename['store_name']);
                         array_push($product_occurrence[$j], $checkoutTime);
                         array_push($product_occurrence[$j], $prodcutName['product_title']);
                         array_push($product_occurrence[$j], $prodcutName['product_description']);
                         
                     }else{
                        writeLog("Product or store name retrieval failed: ".mysqli_error($conn), $openLogFile);
                        die();
                     }
                    
                     $j++;
                  }
                   
              }else {
                  writeLog("Product occurrence retrieval failed".mysqli_error($conn), $openLogFile);
                  
                  die();
              }
             
                
               
          }

        }else {
            writeLog("User checkouts rertieval failed".mysqli_error($conn), $openLogFile);
            die();
         }

        

         

         for ($i=0; $i <count($product_occurrence) ; $i++) { 
            $returnValue[$i] = [$product_occurrence[$i]['price'], $product_occurrence[$i]['barcode'], $product_occurrence[$i]['VAT'], $product_occurrence[$i][0], $product_occurrence[$i][1], $product_occurrence[$i][2], $product_occurrence[$i][3]];
         }
        

       

        return json_encode($returnValue);

        
        
    }

    
?>