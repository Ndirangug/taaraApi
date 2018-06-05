<?php
          // ---------------------------------------------------------------
        //             USER INFROMATION MANAGEMENT
        // -----------------------------------------------------------
    // This section handles all user data manipulation operrtations:
    //     -Create a new user
    //     -Update information on existing user
    //     -Delete existing user
    //     -Retrieve user details based on a specified parameter

    //Function to create a new user after signup on firebase
    function createNewUser($firstname, $secondname, $email, $phone, $conn, $openLogFile){

        $returnMessage;
        $newUserQuery = "INSERT INTO users (first_name, last_name, email, phone)
        VALUES ('$firstname', '$secondname', '$email', '$phone')";

        $executeNewUserQuery = mysqli_query($conn, $newUserQuery);
 
        if($executeNewUserQuery){
            writeLog("User created successfully ", $openLogFile);
            $returnMessage = "User created successfully";
        }
    
        else{
            writeLog(mysqli_error($conn), $openLogFile);
            $returnMessage = mysqli_error($conn);
        }

        return json_encode($returnMessage);
    }

    

    //function to update user info with email as select parameter
    function updateUserInformation($firstname, $secondname, $email, $phone, $conn, $openLogFile){

        $returnMessage;
        $updateQuery = "UPDATE users SET first_name='$firstname', last_name='$secondname', phone='$phone' WHERE email='$email' ";
        $executeUpdateQuery = mysqli_query($conn, $updateQuery);

        if($executeUpdateQuery){
            writeLog("User info updated successfully", $openLogFile);
            $returnMessage = ['Update Success', $firstname, $secondname, $email, $phone ];
        }
        else{
            writeLog(mysqli_error($conn), $openLogFile);
            $returnMessage = ['Update Failure', $firstname, $secondname, $email, $phone ];
        }

    return json_encode($returnMessage);
    }

    //function to delete existimg user
    function deleteUser($email, $conn, $openLogFile){

        $returnMessage;
        $deleteQuery = "DELETE FROM users WHERE email = '$email'";
        $executeDelete = mysqli_query($conn, $deleteQuery);

        if($executeDelete){
            writeLog("User $email deleted successfully", $openLogFile); 
            $returnMessage = "Delete success";
        }

        else{
            writeLog(mysqli_error($conn), $openLogFile);
            $returnMessage = "Delete fail";
        }

        return json_encode($returnMessage);
    }
    
    //function to retrieve details based on email 
    function retrieveDetailsWithEmail($email, $conn, $openLogFile){

        $returnMessage;
        $retrieveInfo = "SELECT * FROM users WHERE email = '$email'";
        $executeRetrieveQuery = mysqli_query($conn, $retrieveInfo);
        $record = mysqli_fetch_assoc($executeRetrieveQuery);

        if($record != null){
            writeLog("User ".$record['first_name']." retrieved successfully", $openLogFile);
            $returnMessage = ['success', $record['first_name'], $record['last_name'], $record['email'], $record['phone'],$record['userID'] ];
        }

        else{
            writeLog(mysqli_error($conn), $openLogFile);
            $returnMessage = ['error'.mysqli_error($conn)];
        }

        return json_encode($returnMessage);
    }

    //function to retrieve details based on phone 
    function retrieveDetailsWithPhone($phone, $conn, $openLogFile){

        $returnMessage;
        $retrieveInfo = "SELECT * FROM users WHERE phone = '$phone'";
        $executeRetrieveQuery = mysqli_query($conn, $retrieveInfo);
        $record = mysqli_fetch_assoc($executeRetrieveQuery);

        if($record != null){
            writeLog("User ".$record['first_name']." retrieved successfully", $openLogFile);
            $returnMessage = ['success', $record['first_name'], $record['last_name'], $record['email'], $record['phone'], $record['userID'] ];
        }

        else{
            writeLog(mysqli_error($conn), $openLogFile);
            $returnMessage = ['error'.mysqli_error($conn)];
        }

        return json_encode($returnMessage);
    }


?>