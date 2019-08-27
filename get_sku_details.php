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
	$child_att = array();
	$producttype = 'simple';
	if(isset($usertoken) && $usertoken != '' && isset($dbcode) && $dbcode != '') {
		//1. Bundle SKU: 5060622900084sfp
		//2. Variation parent SKU: QUIZTPMATCHVARI-19
		//3. Variation Child SKU: 0787551465266new-VARI
		//4. Normal SKU: 889698247061new
		
		//$sku = '889698247061new';
		$sku = 'QUIZTPMATCHVARI-19';
		//$sku = '0787551465266new-VARI';
		//$sku = '5060622900084sfp';
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
			
			$WebstoreProductTitle = !empty($response['parent']['WebstoreProductTitle'])?$response['parent']['WebstoreProductTitle']:$response['parent']['ProductTitle'];
			$SKU = $response['parent']['SKU'];
			$WebstoreStandardPrice = !empty($response['parent']['WebstoreStandardPrice'])?$response['parent']['WebstoreStandardPrice']:0;
			$Weight = $response['parent']['Weight'];
			$MasterQuantity = $response['parent']['MasterQuantity'];
			$WebstoreProductDescription = $response['parent']['WebstoreProductDescription'];
			
			$child_att = $response['children'];
			
			
			if($response['parent']['SKUType'] == "Master SKU" && $response['parent']['VariationItem'] == "YES"){
				$producttype = 'configurable';
			}

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
			$main_image = '';
			$sku_images = array();
			foreach($response['skuimages'] as $k=>$v){
				$sku_images[$v['sku']] = $v;
			}
			$mainImage = $sku_images[$SKU]['mainimage'];
		    

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


	//exit;


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
	
	/* get All attrbutes from API */
	$apiUrl = $url."/V1/products/attribute-sets/4/attributes";
	
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL, $apiUrl);
	//curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
	//curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$response = curl_exec($ch);

	$custom_attributes = json_decode($response, TRUE);
	curl_close($ch);
	
	
	// Iterate for child products
	$pos = 0;
	$child_insert_ids = [];
	if($producttype == "configurable" && count($child_att) > 0){
		foreach($child_att as $kk=>$vv){
			if($vv['Relationship'] == "Child" && $vv['ParentSKU'] == $SKU){
				
				// insert product as simple 
				$price = 0;
				if(isset($vv['WebstoreStandardPrice']) && !empty($vv['WebstoreStandardPrice'])){
					$price = $vv['WebstoreStandardPrice'];
				}else if(isset($vv['eBayBINPrice']) && !empty($vv['eBayBINPrice'])){
					$price = $vv['eBayBINPrice'];
				}
				
				$custom_att = array();
				$custom_attributes_array = [];
				$custom_attributes_array = [
							[
								"attribute_code" => "description",
								"value" => $WebstoreProductDescription
							],
							[
								"attribute_code" => "short_description",
								"value" => $WebstoreProductDescription
							]
						];
				for($i=1;$i<=5;$i++){
					if(isset($vv['CustomVariationName'.$i]) && !empty($vv['CustomVariationName'.$i]) && !empty($vv['CustomVariationValue'.$i]) ){
						$key = array_search(strtolower($vv['CustomVariationName'.$i]), array_column($custom_attributes, 'attribute_code'));
						if(!empty($key)){
							$option_values = $custom_attributes[$key]['options'];
							$key1 = array_search($vv['CustomVariationValue'.$i],array_column($option_values,'label'));
							$fin_option_data = $option_values[$key1];
							$custom_attributes_array[] = ['attribute_code'=>strtolower($vv['CustomVariationName'.$i]),'value'=>$fin_option_data['value']];
						}
					}
				}
				
				$ch = curl_init();
				$apiUrl = $url."/V1/products";
				$data = [
					"product" => [
						"sku" => $vv['SKU'],
						"name" => $vv['ProductTitle'],
						"attribute_set_id" => "4",
						"price" => $price,
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
								"qty" => $vv['MasterQuantity'],
								"is_in_stock" => true
							]
						],
						"custom_attributes" => $custom_attributes_array
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
				$child_insert_ids[] = $response['id'];
				////exit;

				//insert child product images
				$apiUrl1 = $url."/V1/products/".$vv['SKU']."/media";
				$main_image = $sku_images[$vv['SKU']]['mainimage'];
				$imgData = [
					"entry" => [
						"media_type" => "image",
						"label" => "Image",
						"position" => $pos,
						"disabled" => false,
						"types" => [
							"image",
							"small_image",
							"thumbnail"
						],
						"content" => [
							"base64EncodedData" => base64_encode(file_get_contents($mainImage)),
							"type" => "image/jpeg",
							"name" => $vv['SKU'].".jpg"
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
				echo "<br />12>> ".$response = curl_exec($ch);

				$response = json_decode($response, TRUE);
				curl_close($ch);
				
				$pos++;
			}
		}
	}
	
	$apiUrl = $url."/V1/products";
	$ch = curl_init();
	
	$custom_att = array();
	$parent_custom_options = [];
	$fin_parent_custom_options = [];
	$pos = 0;
	foreach($child_att as $k=>$v){
		for($i=1;$i<=5;$i++){
			if(isset($v['CustomVariationName'.$i]) && !empty($v['CustomVariationName'.$i]) && !empty($v['CustomVariationValue'.$i]) ){
				$custom_att[$v['CustomVariationName'.$i]] = $v['CustomVariationValue'.$i];
				$key = array_search(strtolower($v['CustomVariationName'.$i]), array_column($custom_attributes, 'attribute_code'));
				$option_values = $custom_attributes[$key]['options'];
				$key1 = array_search($v['CustomVariationValue'.$i],array_column($option_values,'label'));
				$fin_option_data = $option_values[$key1];
				if(isset($parent_custom_options[$v['CustomVariationName'.$i]])){
					$temp_data_values = $parent_custom_options[$v['CustomVariationName'.$i]]['values'];
					$temp_data_values[] = array('value_index' => $fin_option_data['value']);
					$parent_custom_options[$v['CustomVariationName'.$i]]['values'] = $temp_data_values;
				}else{
					$data = [
							"attribute__id"=>$custom_attributes[$key]['attribute_id'],
							"label"=>$v['CustomVariationName'.$i],
							"position"=>$pos,
							"values"=>array(array('value_index' => $fin_option_data['value']))
						];
					$parent_custom_options[$v['CustomVariationName'.$i]] = $data;	
					$pos++;
				}
						
			}
		}
	}
	foreach($parent_custom_options as $k => $v){
		$fin_parent_custom_options[] = $v;
	}
	$data = [
	    "product" => [
	        "sku" => $SKU,
	        "name" => $WebstoreProductTitle,
	        "attribute_set_id" => 4,
	        "status" => 1,
	        "visibility" => 4,
	        "type_id" => $producttype,
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
	                "is_in_stock" => true
	            ],
				"configurable_product_options" => $fin_parent_custom_options,
				"configurable_product_links"=> $child_insert_ids,
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
	echo "<br/>";echo "product_data++";print_r($data);echo "<br/>";
	$data_string = json_encode($data);
	print_r($data_string);echo "<br/>";
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
	echo"===13";print_r($response);
	
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
	echo "<br />14>> ".$response = curl_exec($ch);

	$response = json_decode($response, TRUE);
	curl_close($ch);
	
	
	/* Link product to parent */
	
	$apiUrl1 = $url."/V1/products/".$SKU;
	$headers = array('Content-Type:application/json','Authorization:Bearer '.$adminToken);
	
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL, $apiUrl1);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
	//curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	echo "<br />15>> ".$response = curl_exec($ch);
	$response = json_decode($response, TRUE);
	curl_close($ch);
	        
	echo "custom_att===";print_r($custom_att);exit;

?>