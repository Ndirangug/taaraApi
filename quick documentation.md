<!-- To write to log: -->
    
    1.include("open_log_file.php");
    2.writeLog($message, $openLogFile);
    3.include("closeLog.php");
    

<!-- Queries -->
    code 101-success
    code 000-error



<!-- --------Funtions--------- -->

##    Users
    
1.  createNewUser($firstname, $secondname, $email, $phone, $conn, $openLogFile) | return String                   success/fail
2.  updateUserInformation($firstname, $secondname, $email, $phone, $conn, $openLogFile) | return array              current records
3.   deleteUser($email, $conn, $openLogFile) | return string success/fail
4.   retrieveDetails($email, $conn, $openLogFile) | return array with current details

##   Products
    
1. retrieveProductOccurrence($barcode, $storeId, $conn, $openLogFile) [ return array with selected              product array contains barcode, price, vat, title, description]
2. isPaid($rfid, $conn, $openLogFile) | return 1 if item is paid, 0 if not
3. productCompareRetrieve($barcode, $conn, $openLogFile) | return associative array with all matching           occcurrences for barcode given
4. bookItem($rfid, $conn, $openLogFile) | return 1 fro success and 0 for fail
5. markUnPaid($rfid, $conn, $openLogFile)   return 1 fro success and 0 for fail
6. unBookItem($rfid, $conn, $openLogFile)   return 1 fro success and 0 for fail
7. markPaid($rfid, $conn, $openLogFile) return 1 fro success and 0 for fail

##    Stores
    ------
*   <!--  NB:function get store works with floats -->
1.    getStore($latitude, $longitude, $conn, $openLogFile) | return associative array with

##  Ipay

1.connectToIpayGateway($live, $orderId, $invoiceNumber, $totalAmount, $telephone, $email, $vendorID,          $currency, $p1, $p2, $p3, $p4, $callbackUrl, $backLink, $emailNotification, $responseFormat, $hashKey) |     returns json processing info
