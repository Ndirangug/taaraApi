
    
    
    <?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require '../PHPMailer/src/Exception.php';
    require '../PHPMailer/src/PHPMailer.php';
    require '../PHPMailer/src/SMTP.php';

    /* -------------------------------------------------------------------
            ENTRY POINT FOR ALL API CALLS FROM ANDROID APP
    ------------------------------------------------------------------  */   
include("conn.php");
include("userInformation.php");
  date_default_timezone_set("Africa/Nairobi");
function output($data){
   // echo "<pre>";
    print_r($data);
    //echo "</pre>";
  
}

        //--------------------------Users-----------------------

    //call to create user
    //createNewUser("George", "Ndirangu", "ndirangu.mepawa@gmail.com", "254746649576", $conn, $openLogFile);

    //call to update user
    //updateUserInformation("George", "Ndirangu", "ndirangu.mepawa@gmail.com", "254746649576", $conn, $openLogFile);
    
    //call to delete user
    //deleteUser("ndirangu.mepawa@gmail.com", $conn, $openLogFile);
    
    //call to retrieve user details with email
    // $retrieved = retrieveDetailsWithEmail("ndirangu.mepawa@gmail.com", $conn, $openLogFile);
    //output($retrieved);

    //call to retrieve user details with phone
     //$retrieved = retrieveDetailsWithPhone("254746649576", $conn, $openLogFile);
     //output($retrieved);



        //------------------------Products---------------------------
    include("retrieveItem.php");

    //call to retrieve specific item using rfid 
    // $rfidStoreID="4,5412";  
    //  $productRetrieved = retrieveProductOccurrence($rfidStoreID, $conn, $openLogFile);
    //  output($productRetrieved);

    //call to confirm whether item is paid
    // echo isPaid(2151, $conn, $openLogFile);

    //call to compare products across supermarkets
    // output(productCompareRetrieve('6161105860327', $conn, $openLogFile));
    $rfid=2151;
    //call to undo item booked
    // unBookItem($rfid, $conn, $openLogFile);

    //call to book item
    // bookItem($rfid, $conn, $openLogFile);

    //call to mark item as paid
    // markPaid($rfid, $conn, $openLogFile);

    //call to undo item paid
    // markUnPaid($rfid, $conn, $openLogFile);
    

    // ------------------------------Stores------------------------------
    include("findStore.php");

     //call to locate supermarket and retrieve details using geo info
   // output(getStore(5, $conn, $openLogFile));
    

    //funtion to process payment
    //get rsponse from payment api and update product occcurrence state to paid
<<<<<<< HEAD
    
     // ------------------------------------   Recent Items   -------------------------------------------------

    include("recents.php");
    
    //    --------------------------------------------------------------------------------------------------
=======
>>>>>>> 33097456658835eac6c02337e3cfa43b5eac2ac1



//    ------------------------Pay---------------------------------
    include("pay.php");

    // $live = '0';
    // $orderId = '232e';
    // $invoiceNumber = '3fvt43t3t';
    // $totalAmount = '3453535';
    // $telephone = '254790516130';
    // $email = 'ndirangu.mepawa@gmail.com';
    // $vendorID = 'demo';
    // $currency = 'KES';
    // $p1 = '';
    // $p2 = '';
    // $p3 = '';
    // $p4 = '';
    // $callbackUrl = $_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
    // $emailNotification = 1;
    // $responseFormat = 2;
    // $hashKey = 'demo';
    // $response = connectToIpayGateway($live, $orderId, $invoiceNumber, $totalAmount, $telephone, $email, $vendorID, $currency, $p1, $p2, $p3, $p4, $callbackUrl, $emailNotification, $responseFormat, $hashKey);
    // if ($response!=null) {
    //    echo "success";
    //    output($response);
    // } else {
    //    echo "null";
    // }
    
    
   /*  --------------------------------------------------------------------------------------------------
                                         GET API CALLS
    
     -------------------------------------------------------------------------------------------------- */
     if (isset($_GET['android_api_call'])) {
         
        switch ($_GET['android_api_call']) {
            case 'retrieveUserWithEmail':
                $ouput = output(retrieveDetailsWithEmail($_GET['email'], $conn, $openLogFile));
                writeLog("user ".$_GET['email']." retrieval ". json_decode($output).mysqli_error($conn), $openLogFile);
                break;

            case 'retrieveUserWithPhone':
                $ouput =  output(retrieveDetailsWithPhone($_GET['phone'], $conn, $openLogFile));
                writeLog("user ".$_GET['phone']." retrieval ". json_decode($output).mysqli_error($conn) , $openLogFile);
                break;

            case 'createNewUser':
                $ouput = output(createNewUser($_GET['firstName'], $_GET['secondName'], $_GET['email'], $_GET['phone'], $conn, $openLogFile));
                writeLog("create new user ".$email." " . json_decode($output).mysqli_error($conn), $openLogFile);
                break;

            case 'retrieveProductOccurrence':
                $output = output(retrieveProductOccurrence($_GET['rfid'], $_GET['storeID'], $conn, $openLogFile));
                 writeLog("Retrieve prodcut occurrence rfid:".$_GET['rfid']." storeId".$_GET['storeID'] . json_decode($output).mysqli_error($conn), $openLogFile);
                break;

            case 'markItemPaid':
                $output = output(markPaid($_GET['rfid'], $_GET['storeID'],  $conn, $openLogFile));
                writeLog("Mark item as paid rfid:".$_GET['rfid']." storeId".$_GET['storeID']." " . json_decode($output).mysqli_error($conn), $openLogFile);
                break;

            // case 'markItemBooked':
            //     $output = output(bookItem($_GET['rfid'], $_GET['storeID'],  $conn, $openLogFile));
            //     writeLog("Mark item as book rfid:".$_GET['rfid']." storeId".$_GET['storeID']." " . json_decode($output).mysqli_error($conn), $openLogFile);
            //     break; 
            
            case 'getStore':
                $output =  output(getStore($_GET['storeID'], $conn, $openLogFile));
                writeLog("Store retrieval...:".$_GET['storeID']." storeId".$_GET['storeID']." " . json_decode($output).mysqli_error($conn), $openLogFile);
                break;

            case 'updateProfle':
                $ouput = output(updateUserInformation($_GET['first_name'], $_GET['second_name'], $_GET['email'], $_GET['phone'], $conn, $openLogFile));
                writeLog("attempt to change user infromation for ".  $_GET['email']." ".mysqli_error($conn), $openLogFile);
                break;

            case 'checkout':

                
               
<<<<<<< HEAD
<<<<<<< HEAD
                
                $uniqueCodeGenerator = $_GET['userId'].date("djmYHis");
                $live = '0';
                $autopay = 1;
=======
                $letters = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
                $uniqueCodeGenerator = $letters[rand(0,25)].rand(0,9).$letters[rand(0,25)].rand(0,9).$letters[rand(0,25)].rand(0,9).$letters[rand(0,25)].rand(0,9).$letters[rand(0,25)].$letters[rand(0,25)].date("djmYHis");
                $live = '0';
>>>>>>> 33097456658835eac6c02337e3cfa43b5eac2ac1
=======
                $letters = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
                $uniqueCodeGenerator = $letters[rand(0,25)].rand(0,9).$letters[rand(0,25)].rand(0,9).$letters[rand(0,25)].rand(0,9).$letters[rand(0,25)].rand(0,9).$letters[rand(0,25)].$letters[rand(0,25)].date("djmYHis");
                $live = '0';
>>>>>>> 33097456658835eac6c02337e3cfa43b5eac2ac1
                $orderId = strrev($uniqueCodeGenerator);
                writeLog("Order id ".$orderId." queued for processing", $openLogFile);
                $invoiceNumber = $orderId;
                $totalAmount = $_GET['amount'];
                $telephone = $_GET['phone'];
                $email = $_GET['email'];
                $vendorID = 'demo';
                $currency = 'KES';
                $p1 = $_GET['itemIds'];
                $p2 = $_GET['userId'];
                $p3 = $orderId;
                $p4 = 
                $callbackUrl = "https://artscircle.co.ke/taaraBackend/confirm.php";
                $emailNotification = 1;
                $responseFormat = 0;
                $hashKey = 'demo';
                 $response = connectToIpayGateway($live, $orderId, $invoiceNumber, $totalAmount, $telephone, $email, $vendorID, $currency, $p1, $p2, $p3, $p4, $callbackUrl, $emailNotification, $responseFormat, $hashKey);
<<<<<<< HEAD
<<<<<<< HEAD
                writeLog("$p1, $p2, $callbackUrl", $openLogFile);             
                  
=======
=======
>>>>>>> 33097456658835eac6c02337e3cfa43b5eac2ac1
                echo "Loading...";               
                   /*
                    if(success){
                        foreach ($itemsIds as $key => $value) {
                            markPaid($value, $conn, $openLogFile);
                            writeLog("item rfid ".$value." marked as paid", $openLogFile);
                         } 

                         return json_encode(['success']);
                    }
                    else(
                        return json_encode(['error_description']);
                    )
                */
<<<<<<< HEAD
>>>>>>> 33097456658835eac6c02337e3cfa43b5eac2ac1
=======
>>>>>>> 33097456658835eac6c02337e3cfa43b5eac2ac1

                
                break;  
                
            case 'contact':
            $email;
               if ($_GET['category'] == 'Help') {
                  $email = "ndirangu.mepawa@outlook.com";
               }
               elseif ($_GET['category'] == 'Enquiries') {
                   $email = "ndirangu.mepawa@outlook.com";
               }
               elseif ($_GET['category'] == 'General_Feedback') {
                    $email = "ndirangu.mepawa@outlook.com";
               }
              
               $mail = new PHPMailer(true);  
               try {
<<<<<<< HEAD
<<<<<<< HEAD
                    //Server settings
                    $mail->SMTPDebug = 0;                                 // Enable verbose debug output
                    $mail->isSMTP();                                      // Set mailer to use SMTP
                    $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
                    $mail->SMTPAuth = true;                               // Enable SMTP authentication
                    $mail->Username = 'ndirangu.mepawa@gmail.com';                 // SMTP username
                    $mail->Password = 'ndiSho16';                           // SMTP password
                    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
                    $mail->Port = 587;                                    // TCP port to connect to
                
                    //Recipients
                    $mail->setFrom('ndirangu.mepawa@gmail.com', 'Taara App');
                    $mail->addAddress('ndirangu.mepawa@outlook.com');     // Add a recipient
                    $mail->addReplyTo($email, 'User');
                   
                    $message = str_replace("_", " ", $_GET['message']);
                    $messageFormated = str_replace(",", "<br/>", $message);
                
                   
                    //Content
                    $mail->isHTML(true);                                  // Set email format to HTML
                    $mail->Subject = str_replace("_", " ", $_GET['category']).": ". str_replace("_", " ", $_GET['subject']);
                    $mail->Body    = $messageFormated;
                    $mail->AltBody = $messageFormated;
                
                    $mail->send();
                     echo json_encode(['completed']);
                } catch (Exception $e) {
                    echo json_encode(['error']);
                     writeLog("'Message could not be sent. Mailer Error: ".$mail->ErrorInfo, $openLogFile);
                }
                writeLog("Attemp to send message subject: ".$_GET['subject']." to email $email", $openLogFile);
           
                break; 
                
             case 'log':
            writeLog($_GET['text'], $openLogFile);
                # code...
                break;  
                
            case 'recents':
            writeLog("attempt to get recents for userID: ".$_GET['userID'], $openLogFile);
            echo  retrieveRecents($_GET['userID'], $conn, $openLogFile);
                break;   
                
            case 'allhistory':
            writeLog("attempt to get all history for userID: ".$_GET['userID'], $openLogFile);
            echo allRecents($_GET['userID'], $conn, $openLogFile);
                break;        
=======
=======
>>>>>>> 33097456658835eac6c02337e3cfa43b5eac2ac1
    //Server settings
    $mail->SMTPDebug = 0;                                 // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'ndirangu.mepawa@gmail.com';                 // SMTP username
    $mail->Password = 'ndiSho16';                           // SMTP password
    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom('ndirangu.mepawa@gmail.com', 'Taara App');
    $mail->addAddress('ndirangu.mepawa@outlook.com');     // Add a recipient
    $mail->addReplyTo($email, 'User');
   
    $message = str_replace("_", " ", $_GET['message']);
    $messageFormated = str_replace(",", "<br/>", $message);

   
    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = str_replace("_", " ", $_GET['category']).": ". str_replace("_", " ", $_GET['subject']);
    $mail->Body    = $messageFormated;
    $mail->AltBody = $messageFormated;

    $mail->send();
     echo json_encode(['completed']);
} catch (Exception $e) {
    echo json_encode(['error']);
     writeLog("'Message could not be sent. Mailer Error: ".$mail->ErrorInfo, $openLogFile);
}
                writeLog("Attemp to send message subject: ".$_GET['subject']." to email $email", $openLogFile);
           
                break;    
<<<<<<< HEAD
>>>>>>> 33097456658835eac6c02337e3cfa43b5eac2ac1
=======
>>>>>>> 33097456658835eac6c02337e3cfa43b5eac2ac1

            default:
                writeLog("android api call ".$_GET['android_api_call']." execution failed", $openLogFile);
                break;
            
        }
     }

include("closeLog.php");
?>
