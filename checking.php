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
	
	/*$apiUrl = $url."/V1/products";
	$size_array = [];
	$data = [
		"product" => [
			"sku" => "TESTVARNEWCHILD12",
			"name" => "Test variation pp12 child",
			"attribute_set_id" => 4,        
			"status" => 1,
			"visibility" => 4,
			"price" => '20',
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
					"value" => "Description of produclmsgkldfdfkglt here2"
				],
				[
					"attribute_code" => "short_description",
					"value" => "short descriptklmxgbldfmlion of product2"
				],
				[
					"attribute_code" => "size",
					"value" => 30
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
	echo "<br />10>> ".$response = curl_exec($ch);

	$child_response = json_decode($response, TRUE);
	curl_close($ch);

	print '<pre>';
	print_r($child_response);
	
	$apiUrl = $url."/V1/products";
	$data = [
		"product" => [
			"sku" => "TESTVARNEWCHILD13",
			"name" => "Test variation pp13 pchild1",
			"attribute_set_id" => 4,        
			"status" => 1,
			"visibility" => 4,
			"price" => '20',
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
					"value" => "Description of product hklmlere2"
				],
				[
					"attribute_code" => "short_description",
					"value" => "short description of proknjknjkjkduct2"
				],
				[
					"attribute_code" => "size",
					"value" => 31
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

	$child_response1 = json_decode($response, TRUE);
	curl_close($ch);

	print '<pre>';
	print_r($child_response1);
	
	
	$size_array[]['value_index'] = 9;
	$size_array[]['value_index'] = 10;
	$item_sku = 'test_var_222';
	$ch = curl_init();
	$data = [
		"product" => [
			"sku" => $item_sku,
			"name" => "Test variation222 pp",
			"attribute_set_id" => 4,        
			"status" => 1,
			"visibility" => 4,
			"type_id" => "configurable",
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
				"configurable_product_options" => [
					[
						"attribute__id"=>"138",
						"label"=>"Size",
						"position"=>0,
						"values"=>$size_array
					]
				],
				"configurable_product_links"=>[
				   $child_response['id'],$child_response1['id']
				]
			],
			"custom_attributes" => [
				[
					"attribute_code" => "description",
					"value" => "Description of lmg;mkglproduct here2"
				],
				[
					"attribute_code" => "short_description",
					"value" => "short description l,ghfgmklof product2"
				]
			]
		]
	];
	print_r($data);
	$data_string = json_encode($data);
	print_r($data_string);
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL, $apiUrl);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	echo "<br />12>> ".$response = curl_exec($ch);

	$response = json_decode($response, TRUE);
	curl_close($ch);

	print '<pre>';
	print_r($response);exit;*/
	
	/*$apiUrl = $url."/default/V1/products/attribute-sets";
	$data = array (
		  'attributeSet' => 
		  array (
			'attribute_set_name' => 'Games',
			'sort_order' => 10,
			'entity_type_id' => 4,
		  ),
		  'skeletonId' => 4,
		);
	$data_string = json_encode($data);

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
	print_r($child_response);exit;*/
	
	/*$apiUrl = $url."/V1/products/attribute-sets/attributes";
	$data = [
				"attributeSetId" => 4,
				"attributeGroupId" => 7,
				"attributeCode" => 138,
				"sortOrder" => 0
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
	echo "<br />11>> ".$response = curl_exec($ch);

	$child_response = json_decode($response, TRUE);
	curl_close($ch);

	print '<pre>';
	print_r($child_response);exit;*/
	
	/*$apiUrl = $url."/V1/products/attributes/10/options";
	$data = [
				"option" => [
					"lable" => 138,
					"value" => "31",
					"sort_order" => 0,
					"is_default" => false,
					"store_labels" => [
						[
							"store_id" => 0,
							"label" => "size"
						]
					]
					
				]
			];
	$data_string = json_encode($data);
	//print_r($data_string);exit;

	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL, $apiUrl);
	//curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	//curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	echo "<br />11>> ".$response = curl_exec($ch);

	$child_response = json_decode($response, TRUE);
	curl_close($ch);

	print '<pre>';
	print_r($child_response);*/
	
	$apiUrl = $url."/V1/products/attribute-sets/4/attributes";
	$data = array (
		  'attributeSet' => 
		  array (
			'attribute_set_id' => 4,
			'attribute_set_name' => 'Default',
			'sort_order' => 0,
			'entity_type_id' => 4,
			'extension_attributes' => 
				array (
				),
		  ),
		);
	$data_string = json_encode($data);
	//print_r($data_string);exit;

	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL, $apiUrl);
	//curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
	//curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	echo "<br />11>> ".$response = curl_exec($ch);

	$child_response = json_decode($response, TRUE);
	curl_close($ch);

	print '<pre>';
	print_r($child_response);
	
	echo array_search('games', array_column($child_response, 'attribute_code'));
?>