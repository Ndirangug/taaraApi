<?php

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
echo "
<html>
<head><title>Ipay</title></head>
<body>
";
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
				);

echo '<form style="display:none" method="post" id="pay" action="https://payments.ipayafrica.com/v3/ke">';
foreach ($postData as $key => $value) {
      echo $key;
     echo '&nbsp;:<input name="'.$key.'" type="text" value="'.$value.'"></br
>';
 }

echo 'hsh:&nbsp;<input name="hsh" type="text" value="'.$generated_hash.'" ></td>
<button type="submit">&nbsp;Lipa&nbsp;</button>
</form>';
echo "
<script>
    document.getElementById('pay').submit();
</script>
<body>
</html>
";
 }
?>