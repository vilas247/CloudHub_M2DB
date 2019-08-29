<?php
//product creation API in M2
	$url = "http://127.0.0.1/Magento2.3.2/index.php/rest";
	$token_url= $url."/V1/integration/admin/token";

	$username= "vilask247commerce";
	$password= "Vilask*123";

	//Authentication REST API magento 2,    
	$ch = curl_init();
	$data = array("username" => $username, "password" => $password);
	$data_string = json_encode($data);

	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL, $token_url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	    'Content-Type: application/json'
	    ));
	echo $token = curl_exec($ch);
	$adminToken=  json_decode($token);
	$headers = array('Content-Type:application/json','Authorization:Bearer '.$adminToken);
	
	$apiUrl = $url."/V1/products/889698247061new";

		$ch = curl_init();
		$data_string = json_encode($data);

		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, $apiUrl);
		//curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		//curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		echo "<br />11>> ".$response = curl_exec($ch);
		$response = json_decode($response, TRUE);
		curl_close($ch);
?>