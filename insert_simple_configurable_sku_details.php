<?php
	require 'crypto-aes.php';
	require 'config.php';
	define('TIMEOUT',1000000);

	$relogin = false;
	//Step1 : Login API call to get the usertoken and DB code details
	$login_request = '<loginrequest><username>tapan@247commerce.co.uk</username><password>tapan247*</password><ipaddress>127.1.1.1</ipaddress><responsetype>json</responsetype></loginrequest>';
	$loginPostData = CryptoJSAES::encrypt($login_request, $passphrase);

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
		//add logs to database
		//$x247conn = mysqli_connect($x247host,$x247user_name,$x247password,$x247dbname);
		insert_data($x247conn,$x247table_name,$loginURL,'cloudhub',$login_request,json_encode($response));
		
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
		$reLoginRequest = '<reloginrequest><usertoken>'.$usertoken.'</usertoken><username>tapan@247commerce.co.uk</username><password>tapan247*</password><ipaddress>127.1.1.1</ipaddress><responsetype>json</responsetype></reloginrequest>';
		$reLoginPostData = CryptoJSAES::encrypt($reLoginRequest, $passphrase);

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
			//add logs to database
			//$x247conn = mysqli_connect($x247host,$x247user_name,$x247password,$x247dbname);
			insert_data($x247conn,$x247table_name,$reLoginURL,'cloudhub',$reLoginRequest,json_encode($response));
			
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
		
		$sku = '889698247061new';
		//$sku = 'QUIZTPMATCHVARI-19';
		//$sku = '0787551465266new-VARI';
		//$sku = '5060622900084sfp';
		//Step 3 : Get SKU From DB API call to get SKU details
		$skuRequest = '<?xml version="1.0" encoding="utf-8"?><getskufromdbrequest><usertoken><![CDATA['.$usertoken.']]></usertoken><dbcode>'.$dbcode.'</dbcode><responsetype><![CDATA[json]]></responsetype><sku><![CDATA['.$sku.']]></sku><marketplacecode><![CDATA[0]]></marketplacecode><accountcode><![CDATA[0]]></accountcode></getskufromdbrequest>';
		$getSKUPostData = CryptoJSAES::encrypt($skuRequest, $passphrase);

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
			
			//add logs to database
			//$x247conn = mysqli_connect($x247host,$x247user_name,$x247password,$x247dbname);
			insert_data($x247conn,$x247table_name,$getSkuFromDBURL,'cloudhub',$skuRequest,json_encode($response));

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
		$QuantityForSkuRequest = '<getquantityforskurequest><usertoken><![CDATA['.$usertoken.']]></usertoken><dbcode>'.$dbcode.'</dbcode><responsetype><![CDATA[json]]></responsetype><sku><![CDATA['.$sku.']]></sku><orderbyclause>0</orderbyclause></getquantityforskurequest>';
		$getQuantityForSkuPostData = CryptoJSAES::encrypt($QuantityForSkuRequest, $passphrase);

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
			
			//add logs to database
			//$x247conn = mysqli_connect($x247host,$x247user_name,$x247password,$x247dbname);
			insert_data($x247conn,$x247table_name,$getQuantityForSkuURL,'cloudhub',$QuantityForSkuRequest,json_encode($response));

		    print '<pre />';
		    print_r($response);
		    

		}

		curl_close($ch); 

		//step 5: GetSkuImagesFromDB API call to get Image details
		$skuImagesRequest = '<?xml version="1.0" encoding="utf-8"?><getskuimagesfromdbrequest><usertoken><![CDATA['.$usertoken.']]></usertoken><dbcode>'.$dbcode.'</dbcode><responsetype><![CDATA[json]]></responsetype><sku><![CDATA['.$sku.']]></sku></getskuimagesfromdbrequest>';
		$getSkuImagesFromDBPostData = CryptoJSAES::encrypt($skuImagesRequest, $passphrase);

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
			
			//add logs to database
			//$x247conn = mysqli_connect($x247host,$x247user_name,$x247password,$x247dbname);
			insert_data($x247conn,$x247table_name,$getSkuImagesFromDBURL,'cloudhub',$skuImagesRequest,json_encode($response));

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
		$skuPriceRequest = '<?xml version="1.0" encoding="utf-8"?><getpriceforskurequest><usertoken><![CDATA['.$usertoken.']]></usertoken><dbcode>'.$dbcode.'</dbcode><responsetype><![CDATA[json]]></responsetype><marketplacecode>1</marketplacecode><accountcode><![CDATA[1]]></accountcode><sku><![CDATA['.$sku.']]></sku></getpriceforskurequest>';
		$getPriceforSKUPostData = CryptoJSAES::encrypt($skuPriceRequest, $passphrase);

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
			
			//add logs to database
			//$x247conn = mysqli_connect($x247host,$x247user_name,$x247password,$x247dbname);
			insert_data($x247conn,$x247table_name,$getPriceforSKUURL,'cloudhub',$skuPriceRequest,json_encode($response));

		    print '<pre />';
		    print_r($response);
		    

		}

		curl_close($ch); 
	}


	//exit;


	//product creation API in M2
	$token_url= $url."/V1/integration/admin/token";

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
	
	//add logs to database
	//$x247conn = mysqli_connect($x247host,$x247user_name,$x247password,$x247dbname);
	insert_data($x247conn,$x247table_name,$token_url,'philipstoys',$data_string,json_encode($token));
	
	$adminToken=  json_decode($token);
	$headers = array('Content-Type:application/json','Authorization:Bearer '.$adminToken);
	
	/* get All attrbutes from API */
	$apiUrl = $url."/V1/products/attribute-sets/".$x247_config['attribute_set_id']."/attributes";
	
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
	
	//add logs to database
	//$x247conn = mysqli_connect($x247host,$x247user_name,$x247password,$x247dbname);
	insert_data($x247conn,$x247table_name,$apiUrl,'philipstoys','',json_encode($custom_attributes));
	
	$insert_cust_attr = array();
	foreach($child_att as $att_k=>$att_v){
		for($i=1;$i<=5;$i++){
			if(isset($att_v['CustomVariationName'.$i]) && !empty($att_v['CustomVariationName'.$i]) && !empty($att_v['CustomVariationValue'.$i]) ){
				//$insert_cust_attr[$att_v['CustomVariationName'.$i]][] = $att_v['CustomVariationValue'.$i];
				$insert_cust_attr[$att_v['CustomVariationName'.$i]][] = $att_v['CustomVariationValue'.$i];
			}
		}
	}
	//print_r($insert_cust_attr);exit;
	
	foreach($insert_cust_attr as $in_k=>$in_v){
		//print_r($in_v);exit;
		$key = array_search(strtolower($in_k), array_column($custom_attributes, 'attribute_code'));
		//echo $key;exit;
		//print_r($custom_attributes[$key]);exit;
		if(!empty($key)){
			$option_values = $custom_attributes[$key]['options'];
			$new_option_values = array();
			foreach($in_v as $opt_k=>$opt_v){
				$key1 = array_search($opt_v,array_column($option_values,'label'));
				if(empty($key1)){
					$apiUrl = $url."/V1/products/attributes/".$custom_attributes[$key]['attribute_id']."/options";
					$data = [
						"option" => [
							"value" => $opt_v,
							"is_default" => false,
							"store_labels" => [
								[
									"store_id" => 0,
									"label" => $opt_v
								]
							]
							
						]
					];
					$data_string = json_encode($data);
					print_r($data_string);

					$ch = curl_init();
					curl_setopt($ch,CURLOPT_URL, $apiUrl);
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
					curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
					curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
					echo "<br />11>> ".$response = curl_exec($ch);

					$child_response = json_decode($response, TRUE);
					curl_close($ch);

					print '<pre>';
					print_r($child_response);
					
					//add logs to database
					//$x247conn = mysqli_connect($x247host,$x247user_name,$x247password,$x247dbname);
					insert_data($x247conn,$x247table_name,$apiUrl,'philipstoys',$data_string,json_encode($child_response));
				}
			}
		}else{
			$apiUrl = $url."/V1/products/attributes/";
			$new_option_values = array();
			$first = 0;
			foreach($in_v as $opt_k=>$opt_v){
				if($first == 0){
					$new_option_values[] = array ('label' => $opt_v,"is_default" => true);
				}else{
					$new_option_values[] = array ('label' => $opt_v);
				}
				$first++;
			}
			$data = array (
				  'attribute' => 
				  array (
					'is_wysiwyg_enabled' => false,
					'is_html_allowed_on_front' => false,
					'used_for_sort_by' => false,
					'is_filterable' => true,
					'is_filterable_in_search' => true,
					'is_used_in_grid' => true,
					'is_visible_in_grid' => false,
					'is_filterable_in_grid' => true,
					'position' => 0,
					'apply_to' => 
					array (
					),
					'is_searchable' => '1',
					'is_visible_in_advanced_search' => '1',
					'is_comparable' => '1',
					'is_used_for_promo_rules' => '0',
					'is_visible_on_front' => '0',
					'used_in_product_listing' => '1',
					'is_visible' => true,
					'scope' => 'global',
					'attribute_code' => strtolower($in_k),
					'frontend_input' => 'select',
					'entity_type_id' => '4',
					'is_required' => false,
					'options' => $new_option_values,
					'is_user_defined' => true,
					'default_frontend_label' => strtolower($in_k),
					'frontend_labels' => NULL,
					'backend_type' => 'int',
					'source_model' => 'Magento%5C%5CEav%5C%5CModel%5C%5CEntity%5C%5CAttribute%5C%5CSource%5C%5CTable',
					'default_value' => '',
					'is_unique' => '0',
				  ),
				);
			$data_string = json_encode($data);
			print_r($data_string);

			$ch = curl_init();
			curl_setopt($ch,CURLOPT_URL, $apiUrl);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			echo "<br />11>> ".$response = curl_exec($ch);

			$custom_insert_att = json_decode($response, TRUE);
			curl_close($ch);
			
			//add logs to database
			//$x247conn = mysqli_connect($x247host,$x247user_name,$x247password,$x247dbname);
			insert_data($x247conn,$x247table_name,$apiUrl,'philipstoys',$data_string,json_encode($custom_insert_att));
			
			$apiUrl = $url."/V1/products/attribute-sets/attributes/";
				$data = [
						  "attributeSetId"=> $x247_config['attribute_set_id'],
						  "attributeGroupId"=> $x247_config['product_group_id'],
						  "attributeCode"=> $custom_insert_att['attribute_code'],
						  "sortOrder"=> 0
						];
				$data_string = json_encode($data);
				//print_r($data_string);exit;

				$ch = curl_init();
				curl_setopt($ch,CURLOPT_URL, $apiUrl);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				echo "<br />12>> ".$response = curl_exec($ch);

				$child_response = json_decode($response, TRUE);
				curl_close($ch);

				print '<pre>';
				print_r($child_response);
				
			//add logs to database
			//$x247conn = mysqli_connect($x247host,$x247user_name,$x247password,$x247dbname);
			insert_data($x247conn,$x247table_name,$apiUrl,'philipstoys',$data_string,json_encode($child_response));
		}
	}
	
	/* get All attrbutes from API */
	$apiUrl = $url."/V1/products/attribute-sets/".$x247_config['attribute_set_id']."/attributes";
	
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
	
	//add logs to database
	//$x247conn = mysqli_connect($x247host,$x247user_name,$x247password,$x247dbname);
	insert_data($x247conn,$x247table_name,$apiUrl,'philipstoys','',json_encode($custom_attributes));
	
	
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
						"attribute_set_id" => $x247_config['attribute_set_id'],
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
				
				//add logs to database
				//$x247conn = mysqli_connect($x247host,$x247user_name,$x247password,$x247dbname);
				insert_data($x247conn,$x247table_name,$apiUrl,'philipstoys',$data_string,json_encode($response));

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
							"base64EncodedData" => base64_encode(file_get_contents($main_image)),
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
				
				//add logs to database
				//$x247conn = mysqli_connect($x247host,$x247user_name,$x247password,$x247dbname);
				insert_data($x247conn,$x247table_name,$apiUrl1,'philipstoys',$data_string,json_encode($response));
				
				$pos++;
			}
		}
	}
	if($producttype == "configurable"){
		// Createt Product REST API URL configurble product
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
				"attribute_set_id" => $x247_config['attribute_set_id'],
				"status" => 1,
				"visibility" => 4,
				"type_id" => $producttype,
				"weight" => "1",
				"extension_attributes" => [
					"category_links" => [
						[
							"position" => 0,
							"category_id" => "5"
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
		
		//add logs to database
		//$x247conn = mysqli_connect($x247host,$x247user_name,$x247password,$x247dbname);
		insert_data($x247conn,$x247table_name,$apiUrl,'philipstoys',$data_string,json_encode($response));
		
		$response = json_decode($response, TRUE);
		curl_close($ch);
		echo"===13";print_r($response);
		
	}else{
		// Createt Product REST API URL simple product
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
				"attribute_set_id" => $x247_config['attribute_set_id'],
				"price" => $WebstoreStandardPrice,
				"status" => 1,
				"visibility" => 4,
				"type_id" => $producttype,
				"extension_attributes" => [
					"category_links" => [
						[
							"position" => 0,
							"category_id" => "5"
						]
					],
					"stock_item" => [
						"qty" => $MasterQuantity,
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
		
		//add logs to database
		//$x247conn = mysqli_connect($x247host,$x247user_name,$x247password,$x247dbname);
		insert_data($x247conn,$x247table_name,$apiUrl,'philipstoys',$data_string,json_encode($response));
		
		$response = json_decode($response, TRUE);
		curl_close($ch);
	}
	
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
	
	//add logs to database
	//$x247conn = mysqli_connect($x247host,$x247user_name,$x247password,$x247dbname);
	insert_data($x247conn,$x247table_name,$apiUrl1,'philipstoys',$data_string,json_encode($response));
	mysqli_close($x247conn);

?>