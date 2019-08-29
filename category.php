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
	
	$category_name = "Mycategory";
	$apiUrl = $url."​/V1/products/Checksimple";
	//$apiUrl = $url."/V1/products/attribute-sets/4/attributes";
	//echo $apiUrl;exit;
	$ch = curl_init();
	$data = array (
			  'category' => 
			  array (
				'id' => 0,
				'parent_id' => 0,
				'name' => 'string',
				'is_active' => true,
				'position' => 0,
				'level' => 0,
				'children' => '',
				'path' => 'string',
				'available_sort_by' => 
				array (
				  0 => '',
				),
				'include_in_menu' => true,
				'extension_attributes' => 
				array (
				),
				'custom_attributes' => 
				array (
				  0 => 
				  array (
					'value' => $category_name,
				  ),
				),
			  ),
			);
	print_r($data);
	//$data_string = json_encode($data);
	$data_string = $data;
	print_r($data_string);
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL, $apiUrl);
	//curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	//curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	echo "===<<12>>";$response = curl_exec($ch);
print_r($response);exit;
	$response = json_decode($response, TRUE);
	curl_close($ch);

	print '<pre>';
	print_r($response);exit;
?>