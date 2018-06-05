<?php


                                //  DEPRECATED!!!!!!!!!!!!!
                                //  DEPRECATED!!!!!!!!!!!!!
                                //  DEPRECATED!!!!!!!!!!!!!
                                //  DEPRECATED!!!!!!!!!!!!!
                                //  DEPRECATED!!!!!!!!!!!!!
                                //  DEPRECATED!!!!!!!!!!!!!
                                //  DEPRECATED!!!!!!!!!!!!!
                                //  DEPRECATED!!!!!!!!!!!!!
                                //  DEPRECATED!!!!!!!!!!!!!
                                //  DEPRECATED!!!!!!!!!!!!!
                                //  DEPRECATED!!!!!!!!!!!!!
                                //  DEPRECATED!!!!!!!!!!!!!
                                //  DEPRECATED!!!!!!!!!!!!!
                                //  DEPRECATED!!!!!!!!!!!!!
                                //  DEPRECATED!!!!!!!!!!!!!
                                //  DEPRECATED!!!!!!!!!!!!!

                      
    // -------------------------------------------------------------------------------------------------------------------------------
    //     This script calculates the distance betweeen two coordinates(coordinates passed from GPS and from the database)
    //     If the distance is within the set limit, then it retrieves the store details whose coordinates met the distance criteria.
    // ---------------------------------------------------------------------------------------------------------------------------------

    function getStore($latitude, $longitude, $conn, $openLogFile){
        $gpsLatitude = deg2rad($latitude);
        $gpsLongitude = deg2rad($longitude);
        $R = 6371e3; // metres
        $latDiff;
        $lonDiff;
        $returnValue;

        $retrieveStoresQuery = "SELECT * FROM stores";
        $runRetrieveStoresQuery = mysqli_query($conn, $retrieveStoresQuery);

        if(mysqli_num_rows($runRetrieveStoresQuery)>0){
            writeLog("Query Successful ", $openLogFile);
            

            while ($store = mysqli_fetch_assoc($runRetrieveStoresQuery)) {
                $dbLatitude = deg2rad($store['latitude']);
                $dbLongitude = deg2rad($store['longitude']);

                //this is to make sure to subtract the smaller value from the bigger value to avoid negatives
               if($dbLatitude > $gpsLatitude ){
                   $latDiff = $dbLatitude - $gpsLatitude;
               }else{
                   $latDiff = $gpsLatitude - $dbLatitude;
               }

               if ($dbLongitude > $gpsLongitude) {
                   $lonDiff = $dbLongitude - $gpsLongitude;
               } else {
                    $lonDiff = $gpsLongitude - $dbLongitude;
               }

               //cosines formula calculates ditance betewen two coordinates with metres accuracy
               $distance = acos( sin($gpsLatitude)*sin($dbLatitude) + cos($gpsLatitude)*cos($dbLatitude) * cos($lonDiff) ) * $R ; //in metres
               
               //if distance is less than 100 metres, break out of loop and assign current store values to $returnValue
               if($distance <= 100){
                   $returnValue = $store;
                   break;
               }
            }
        }else{
            writeLog("Query Failed: ".mysqli_error($conn));
            $returnValue = ["Query Failed: ".mysqli_error($conn)];
        }
        
        return $returnValue;
    }
?>