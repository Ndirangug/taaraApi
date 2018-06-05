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


	/* 	-------------------------------------------------------------------------
				This script handles all payment transactions via the ipay API:
					-Mpesa stk push
					-lipa na bonga
					-credit/debit card
					-esazzypay
					-airtel money
					-kenswitch online
		----------------------------------------------------------------------- */

		//function to connect to ipay gateway
		function connectToIpayGateway($live, $orderId, $invoiceNumber, $totalAmount, $telephone, $email, $vendorID, $currency, $p1, $p2, $p3, $p4, $callbackUrl, $emailNotification, $responseFormat, $hashKey){

			$datastring = $live.$orderId.$invoiceNumber.$totalAmount.$telephone.$email.$vendorID.$currency.$p1.$p2.$p3.$p4.$callbackUrl.$emailNotification.$responseFormat;
			$generated_hash = hash_hmac('sha1',$datastring , $hashKey);

			$postData = array(
				"live"=> $live,
                "oid"=> $orderId,
                "inv"=> $invoiceNumber,
                "ttl"=> $totalAmount,
                "tel"=> $telephone,
                "eml"=> $email,
                "vid"=> $vendorID,
                "curr"=> $currency,
                "p1"=> $p1,
                "p2"=> $p2,
                "p3"=> $p3,
                "p4"=> $p4,
                "cbk"=> $callbackUrl,
                "cst"=> $emailNotification,    
				"crl"=> $responseFormat,
				"hsh" => $generated_hash
				
				);

			
				
			$url = 'https://payments.ipayafrica.com/v3/ke';
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postData));
			curl_exec($curl);
			curl_error($curl);
			
		
 
		
		}
?>