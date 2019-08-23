<?php
	require 'crypto-aes.php';
	define('TIMEOUT',1000000);

	$passphrase = "38aystr0ngpa55w0rd";


	$relogin = false;
	//Step1 : Login API call to get the usertoken and DB code details
	$loginURL = 'https://node1.247cloudhub.co.uk/UserApis/API/UserSettings/Login';
	$loginPostData = CryptoJSAES::encrypt('<loginrequest><username>tapan@247commerce.co.uk</username><password>tapan247*</password><ipaddress>127.1.1.1</ipaddress><responsetype>json</responsetype></loginrequest>', $passphrase);

	$ch = curl_init($loginURL);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);      // return result in a variable
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSLVERSION, 4);     
	curl_setopt($ch, CURLOPT_POST, true); 
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); 
	curl_setopt($ch, CURLOPT_TIMEOUT, TIMEOUT); 
	if(!is_null($loginPostData))	{
	   curl_setopt($ch, CURLOPT_POSTFIELDS, $loginPostData); 
	} 
	if(!($data = curl_exec($ch))) {  	                    
		echo curl_error($ch);
	}       
	else {   
	    //echo $data;
	    echo $decrypted = CryptoJSAES::decrypt($data, $passphrase);

	    $response = json_decode($decrypted, true);

	    if (strpos(strtolower($response['statusmessage']), 'active session already exists') !== false) {
		    //making relogin flag true to execute relogin call
		    $relogin = true;
		    $usertoken = $response['usertoken'];
		}
		else {
			$usertoken = $response['usertoken'];
		    $dbcode = $response['dbcode'];
		}

	}
	curl_close($ch); 

	

	if($relogin) {
		//Step2 : Re Login API call to get the usertoken and DB code details
		$reLoginURL = 'https://node1.247cloudhub.co.uk/UserApis/API/UserSettings/ReLogin';
		$reLoginPostData = CryptoJSAES::encrypt('<reloginrequest><usertoken>'.$usertoken.'</usertoken><username>tapan@247commerce.co.uk</username><password>tapan247*</password><ipaddress>127.1.1.1</ipaddress><responsetype>json</responsetype></reloginrequest>', $passphrase);

		$ch = curl_init($reLoginURL);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);      // return result in a variable
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSLVERSION, 4);     
		curl_setopt($ch, CURLOPT_POST, true); 
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); 
		curl_setopt($ch, CURLOPT_TIMEOUT, TIMEOUT);   
		if(!is_null($reLoginPostData))	{
		   curl_setopt($ch, CURLOPT_POSTFIELDS, $reLoginPostData); 
		} 
		if(!($data = curl_exec($ch))) {  	                    
			echo curl_error($ch);
		}       
		else {   
		    //echo $data;
		    echo $decrypted = CryptoJSAES::decrypt($data, $passphrase);

		    $response = json_decode($decrypted, true);

		    /*print '<pre />';
		    print_r($response);*/

		    $usertoken = $response['usertoken'];
		    $dbcode = $response['dbcode'];
		    

		}

		curl_close($ch); 
	}

	$dbcode = 77; //for champion dreams

	if(isset($usertoken) && $usertoken != '' && isset($dbcode) && $dbcode != '') {
		//1. Bundle SKU: 5060622900084sfp
		//2. Variation parent SKU: QUIZTPMATCHVARI-19
		//3. Variation Child SKU: 0787551465266new-VARI
		//4. Normal SKU: 889698247061new
		
		$sku = '889698247061new'; 
		//Step 3 : Get SKU From DB API call to get SKU details
		$getSkuFromDBURL = 'https://node1.247cloudhub.co.uk/InventoryAPI/Api/Product/GetSkuFromDB';
		$getSKUPostData = CryptoJSAES::encrypt('<?xml version="1.0" encoding="utf-8"?><getskufromdbrequest><usertoken><![CDATA['.$usertoken.']]></usertoken><dbcode>'.$dbcode.'</dbcode><responsetype><![CDATA[json]]></responsetype><sku><![CDATA['.$sku.']]></sku><marketplacecode><![CDATA[0]]></marketplacecode><accountcode><![CDATA[0]]></accountcode></getskufromdbrequest>', $passphrase);

		$ch = curl_init($getSkuFromDBURL);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);      // return result in a variable
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSLVERSION, 4);     
		curl_setopt($ch, CURLOPT_POST, true); 
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); 
		curl_setopt($ch, CURLOPT_TIMEOUT, TIMEOUT);    
		if(!is_null($getSKUPostData))	{
		   curl_setopt($ch, CURLOPT_POSTFIELDS, $getSKUPostData); 
		} 
		if(!($data = curl_exec($ch))) {  	                    
			echo curl_error($ch);
		}       
		else {   
		    //echo $data;
		    echo $decrypted = CryptoJSAES::decrypt($data, $passphrase);

		    $response = json_decode($decrypted, true);

		    print '<pre />';
		    print_r($response);

		    $WebstoreProductTitle = $response['parent']['WebstoreProductTitle'];
		    $SKU = $response['parent']['SKU'];
		    $WebstoreStandardPrice = $response['parent']['WebstoreStandardPrice'];
		    $Weight = $response['parent']['Weight'];
		    $MasterQuantity = $response['parent']['MasterQuantity'];
		    $MasterQuantity = $response['parent']['MasterQuantity'];
		    $WebstoreProductDescription = $response['parent']['WebstoreProductDescription'];

		}

		curl_close($ch); 



		//step 4: GetQuantityForSku API call to get QTY details
		$getQuantityForSkuURL = 'https://node1.247cloudhub.co.uk/InventoryAPI/api/Quantity/GetQuantityForSku';
		$getQuantityForSkuPostData = CryptoJSAES::encrypt('<getquantityforskurequest><usertoken><![CDATA['.$usertoken.']]></usertoken><dbcode>'.$dbcode.'</dbcode><responsetype><![CDATA[json]]></responsetype><sku><![CDATA['.$sku.']]></sku><orderbyclause>0</orderbyclause></getquantityforskurequest>', $passphrase);

		$ch = curl_init($getQuantityForSkuURL);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);      // return result in a variable
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSLVERSION, 4);     
		curl_setopt($ch, CURLOPT_POST, true); 
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); 
		curl_setopt($ch, CURLOPT_TIMEOUT, TIMEOUT); 
		if(!is_null($getQuantityForSkuPostData))	{
		   curl_setopt($ch, CURLOPT_POSTFIELDS, $getQuantityForSkuPostData); 
		} 
		if(!($data = curl_exec($ch))) {  	                    
			echo curl_error($ch);
		}       
		else {   
		    //echo $data;
		    echo $decrypted = CryptoJSAES::decrypt($data, $passphrase);

		    $response = json_decode($decrypted, true);

		    print '<pre />';
		    print_r($response);
		    

		}

		curl_close($ch); 

		//step 5: GetSkuImagesFromDB API call to get Image details
		$getSkuImagesFromDBURL = 'https://node1.247cloudhub.co.uk/InventoryAPI/api/Product/GetSkuImagesFromDB';
		$getSkuImagesFromDBPostData = CryptoJSAES::encrypt('<?xml version="1.0" encoding="utf-8"?><getskuimagesfromdbrequest><usertoken><![CDATA['.$usertoken.']]></usertoken><dbcode>'.$dbcode.'</dbcode><responsetype><![CDATA[json]]></responsetype><sku><![CDATA['.$sku.']]></sku></getskuimagesfromdbrequest>', $passphrase);

		$ch = curl_init($getSkuImagesFromDBURL);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);      // return result in a variable
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSLVERSION, 4);     
		curl_setopt($ch, CURLOPT_POST, true); 
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); 
		curl_setopt($ch, CURLOPT_TIMEOUT, TIMEOUT);  
		if(!is_null($getSkuImagesFromDBPostData))	{
		   curl_setopt($ch, CURLOPT_POSTFIELDS, $getSkuImagesFromDBPostData); 
		} 
		if(!($data = curl_exec($ch))) {  	                    
			echo curl_error($ch);
		}       
		else {   
		    //echo $data;
		    echo $decrypted = CryptoJSAES::decrypt($data, $passphrase);

		    $response = json_decode($decrypted, true);

		    print '<pre />';
		    print_r($response);

		    $mainImage = $response['skuimages'][0]['mainimage'];
		    

		}

		curl_close($ch); 


		//step 6: GetPriceforSKU API call to get Price details
		$getPriceforSKUURL = 'https://node1.247cloudhub.co.uk/InventoryAPI/Api/Price/GetPriceforSKU';
		$getPriceforSKUPostData = CryptoJSAES::encrypt('<?xml version="1.0" encoding="utf-8"?><getpriceforskurequest><usertoken><![CDATA['.$usertoken.']]></usertoken><dbcode>'.$dbcode.'</dbcode><responsetype><![CDATA[json]]></responsetype><marketplacecode>1</marketplacecode><accountcode><![CDATA[1]]></accountcode><sku><![CDATA['.$sku.']]></sku></getpriceforskurequest>', $passphrase);

		$ch = curl_init($getPriceforSKUURL);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);      // return result in a variable
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSLVERSION, 4);     
		curl_setopt($ch, CURLOPT_POST, true); 
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); 
		curl_setopt($ch, CURLOPT_TIMEOUT, TIMEOUT);  
		if(!is_null($getPriceforSKUPostData))	{
		   curl_setopt($ch, CURLOPT_POSTFIELDS, $getPriceforSKUPostData); 
		} 
		if(!($data = curl_exec($ch))) {  	                    
			echo curl_error($ch);
		}       
		else {   
		    //echo $data;
		    echo $decrypted = CryptoJSAES::decrypt($data, $passphrase);

		    $response = json_decode($decrypted, true);

		    print '<pre />';
		    print_r($response);
		    

		}

		curl_close($ch); 
	}


	exit;


	//product creation API in M2
	$url = "http://127.0.0.1/magento-2.2.4/index.php/rest";
	$token_url= $url."/V1/integration/admin/token";

	$username= "admin";
	$password= "admin123";

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

	// Createt Product REST API URL
	$apiUrl = $url."/V1/products";

	/*$WebstoreStandardPrice = $response['parent']['WebstoreStandardPrice'];
    $Weight = $response['parent']['Weight'];
    $MasterQuantity = $response['parent']['MasterQuantity'];
    $MasterQuantity = $response['parent']['MasterQuantity'];
    $WebstoreProductDescription = $response['parent']['WebstoreProductDescription'];*/

	$ch = curl_init();
	$data = [
	    "product" => [
	        "sku" => $SKU,
	        "name" => $WebstoreProductTitle,
	        "attribute_set_id" => 4,
	        "price" => $WebstoreStandardPrice,
	        "status" => 1,
	        "visibility" => 2,
	        "type_id" => "simple",
	        "weight" => "1",
	        "extension_attributes" => [
	            "category_links" => [
	                [
	                    "position" => 0,
	                    "category_id" => "5"
	                ],
	                [
	                    "position" => 1,
	                    "category_id" => "7"
	                ]
	            ],
	            "stock_item" => [
	                "qty" => "500",
	                "is_in_stock" => true
	            ]
	        ],
	        "custom_attributes" => [
	            [
	                "attribute_code" => "description",
	                "value" => $WebstoreProductDescription
	            ],
	            [
	                "attribute_code" => "short_description",
	                "value" => $WebstoreProductDescription
	            ]
	        ]
	    ]
	];
	$data_string = json_encode($data);

	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL, $apiUrl);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	echo "<br />11>> ".$response = curl_exec($ch);

	$response = json_decode($response, TRUE);
	curl_close($ch);

	////exit;


	$apiUrl1 = $url."/V1/products/".$SKU."/media";

	$imgData = [
	    "entry" => [
	        "media_type" => "image",
	        "label" => "Image",
	        "position" => 1,
	        "disabled" => false,
	        "types" => [
	            "image",
	            "small_image",
	            "thumbnail"
	        ],
	        "content" => [
	            "base64EncodedData" => base64_encode(file_get_contents($mainImage)),
	            "type" => "image/jpeg",
	            "name" => $SKU.".jpg"
	        ]
	    ]
	];
	$data_string = json_encode($imgData);
	$headers = array('Content-Type:application/json','Authorization:Bearer '.$adminToken);

	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL, $apiUrl1);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	echo "<br />22>> ".$response = curl_exec($ch);

	$response = json_decode($response, TRUE);
	curl_close($ch);
	        

?>