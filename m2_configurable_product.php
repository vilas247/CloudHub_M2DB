<?php
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

$item_sku = 'test_var_1';
$ch = curl_init();
$data = [
    "product" => [
        "sku" => $item_sku,
        "name" => "Test variation product1",
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
                    "attribute__id"=>"93",
                    "label"=>"Color",
                    "position"=>0,
                    "values"=>[
                         [
                           "value_index"=>49
                         ],
                         [
                           "value_index"=>50
                         ],
                         [
                           "value_index"=>51
                         ],
                         [
                           "value_index"=>52
                         ]
                   ]
                ],
                [
                    "attribute__id"=>"141",
                    "label"=>"Size",
                    "position"=>0,
                    "values"=>[
                         [
                           "value_index"=>176
                         ],
                         [
                           "value_index"=>177
                         ],
                         [
                           "value_index"=>178
                         ],
                         [
                           "value_index"=>179
                         ]
                   ]
                ]
            ],
            "configurable_product_links"=>[
               2057,
               2058
            ]
        ],
        "custom_attributes" => [
            [
                "attribute_code" => "description",
                "value" => "Description of product here2"
            ],
            [
                "attribute_code" => "short_description",
                "value" => "short description of product2"
            ]
        ]
    ]
];
print_r($data);
$data_string = json_encode($data);
print_r($data_string);exit;
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

print '<pre>';
print_r($response);

////exit;


$apiUrl1 = $url."/V1/products/".$item_sku."/media";

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
            "base64EncodedData" => base64_encode(file_get_contents('https://images-na.ssl-images-amazon.com/images/I/41LZEkSQ0ML._SL1500_.jpg')),
            "type" => "image/jpeg",
            "name" => $item_sku.".jpg"
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