<?php
            /* ------------------------------------------------------------------------------------------------
                This function retrieves store details from db on receiving store id from app(from qr code)
            ------------------------------------------------------------------------------------------------ */

        function getStore($storeId, $conn, $openLogFile){
            $retrieveStoreQuery = "SELECT * FROM stores WHERE storeID='$storeId'";
            $executeStoreQuery = mysqli_query($conn, $retrieveStoreQuery);
            $returnValue;
            if ( mysqli_num_rows($executeStoreQuery) > 0) {
                $store = mysqli_fetch_row($executeStoreQuery);
                $returnValue = [$store[0], $store[1], $store[2], $store[3], $store[4]];
                writeLog("Store '".$store[1]."' successfully retrieved", $openLogFile);
            } else {
               writeLog("Store  retrieval failed: ".mysqli_error($conn), $openLogFile);
               $returnValue = [0];
            }

            return json_encode($returnValue);
            
        }
?>