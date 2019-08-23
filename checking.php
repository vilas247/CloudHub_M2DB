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

	// Createt Product REST API URL
	$apiUrl = $url."/V1/products";

	$ch = curl_init();
	$data = [
	    "product" => [
	        "sku" => "MYSKUCHECK",
	        "name" => "My Sku Check",
	        "attribute_set_id" => 4,
	        "price" => 0,
	        "status" => 1,
	        "visibility" => 4,
	        "type_id" => 'configurable',
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
	                "value" => "mudescription"
	            ],
	            [
	                "attribute_code" => "short_description",
	                "value" => "mudescription"
	            ],
				[
					"attribute_code" => "size1",
					"value" => 52
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
	echo"===10";print_r($response);echo "<br/>";
	
	$ch = curl_init();
				$data = [
					"product" => [
						"sku" => "MYSKUCHECK1",
						"name" => "My Sku Check1",
						"attribute_set_id" => "4",
						"price" => 20,
						"status" => 1,
						"visibility" => 2,
						"type_id" => "virtual",
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
								"value" => "mudescription"
							],
							[
								"attribute_code" => "short_description",
								"value" => "mudescription"
							],
							[
								"attribute_code" => "size1",
								"value" => 168
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
				
				/* Map child product */
				
				$apiUrl1 = $url."/default/V1/configurable-products/MYSKUCHECK/options";
				
				$data = [
					"option" => [
						"attribute_id" => "141",
						"label" => "Size1",
						"position" => 0,
						"is_use_default" => true,
						"values" => [
							[
								'value_index' => 168
							]
						]
					]
				];
				
				$data_string = json_encode($data);
				$headers = array('Content-Type:application/json','Authorization:Bearer '.$adminToken);

				$ch = curl_init();
				curl_setopt($ch,CURLOPT_URL, $apiUrl1);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				echo "<br />23>> ".$response = curl_exec($ch);echo "<br/>";

				$response = json_decode($response, TRUE);
				curl_close($ch);
				
				
				/* Link product to parent */
				
				$apiUrl1 = $url."/default/V1/configurable-products/MYSKUCHECK/child";

				$data = [
					"childSku" => "MYSKUCHECK1"
				];
				
				$data_string = json_encode($data);
				print_r($data_string);echo "<br/>";
				$headers = array('Content-Type:application/json','Authorization:Bearer '.$adminToken);

				$ch = curl_init();
				curl_setopt($ch,CURLOPT_URL, $apiUrl1);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				echo "<br />24>> ".$response = curl_exec($ch);echo "<br/>";

				$response = json_decode($response, TRUE);
				curl_close($ch);
				

	
	
	/* Link product to parent */
				
				$apiUrl1 = $url."/V1/products/MYSKUCHECK";
				
				$headers = array('Content-Type:application/json','Authorization:Bearer '.$adminToken);

				$ch = curl_init();
				curl_setopt($ch,CURLOPT_URL, $apiUrl1);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
				//curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				echo "<br />24>> ".$response = curl_exec($ch);echo "<br/>";

				$response = json_decode($response, TRUE);
				curl_close($ch);
?>