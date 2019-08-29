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
	
	/*$apiUrl = $url."/V1/products/attributes/4/options";
			$data = [
						"option" => [
							"value" => "test4",
							"is_default" => false,
							"store_labels" => [
								[
									"store_id" => 0,
									"label" => "test4"
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
	//print_r($response);exit;
	$custom_attributes = json_decode($response, TRUE);
	curl_close($ch);
	
	$api_name = "fragment4";
	$api_value = "test1";
	$key = array_search($api_name, array_column($custom_attributes, 'attribute_code'));
	if(!empty($key)){
		$option_values = $custom_attributes[$key]['options'];
		$key1 = array_search($api_value,array_column($option_values,'label'));
		if(empty($keys)){
			$apiUrl = $url."/V1/products/attributes/".$custom_attributes[$key]['id']."/options";
			$data = [
						"option" => [
							"value" => "test4",
							"is_default" => false,
							"store_labels" => [
								[
									"store_id" => 0,
									"label" => "test4"
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
			print_r($child_response);
		}
	}else{
		$apiUrl = $url."/V1/products/attributes/";
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
				'attribute_code' => $api_name,
				'frontend_input' => 'select',
				'entity_type_id' => '4',
				'is_required' => false,
				'options' => 
				array (
				  0 =>  array (
						'label' => $api_value,
					  ),
				),
				'is_user_defined' => true,
				'default_frontend_label' => $api_name,
				'frontend_labels' => NULL,
				'backend_type' => 'int',
				'source_model' => 'Magento%5C%5CEav%5C%5CModel%5C%5CEntity%5C%5CAttribute%5C%5CSource%5C%5CTable',
				'default_value' => '',
				'is_unique' => '0',
			  ),
			);
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

		$custom_insert_att = json_decode($response, TRUE);
		curl_close($ch);
		
		$apiUrl = $url."/V1/products/attribute-sets/attributes/";
			$data = [
					  "attributeSetId"=> 4,
					  "attributeGroupId"=> 7,
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
			echo "<br />11>> ".$response = curl_exec($ch);

			$child_response = json_decode($response, TRUE);
			curl_close($ch);

			print '<pre>';
			print_r($child_response);
	}
	
	
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
	//print_r($custom_attributes);exit;
	
	$custom_attributes_array = [];
				$custom_attributes_array = [
							[
								"attribute_code" => "description",
								"value" => "sdfsdfsdfsf dsfdsf"
							],
							[
								"attribute_code" => "short_description",
								"value" => "sdfsdffjnh dfihusd"
							]
						];
	$key = array_search(strtolower($api_name), array_column($custom_attributes, 'attribute_code'));
	if(!empty($key)){
		$option_values = $custom_attributes[$key]['options'];
		$key1 = array_search($api_value,array_column($option_values,'label'));
		if(!empty($key1)){
			$fin_option_data = $option_values[$key1];
			$custom_attributes_array[] = ['attribute_code'=>strtolower($api_name),'value'=>$fin_option_data['value']];
		}
	}
	
	$apiUrl = $url."/V1/products";
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
	echo "<br />10>> ".$response = curl_exec($ch);

	$child_response = json_decode($response, TRUE);
	curl_close($ch);

	print '<pre>';
	print_r($child_response);
	
	$key = array_search(strtolower($api_name), array_column($custom_attributes, 'attribute_code'));
	if(!empty($key)){
		$option_values = $custom_attributes[$key]['options'];
		$key1 = array_search($api_value,array_column($option_values,'label'));
		if(!empty($key1)){
			$fin_option_data = $option_values[$key1];
			$custom_attributes_array[] = ['attribute_code'=>strtolower($api_name),'value'=>$fin_option_data['value']];
		}
	}
	$cust_options = $custom_attributes[$key]['options'];
	$data = [
		"attribute_id"=>$custom_attributes[$key]['attribute_id'],
		"label"=>ucfirst($custom_attributes[$key]['attribute_code']),
		"position"=>1,
		"values"=>array(array('value_index' => $cust_options[1]['value']))
		];
	$fin_parent_custom_options = $data;
	//$fin_parent_custom_options = array();
	
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
				"configurable_product_options" => $fin_parent_custom_options,
				"configurable_product_links"=>[
				   $child_response['id']
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
	print_r($response);exit;
?>