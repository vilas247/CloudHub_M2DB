<?php
//CloudHub Details
$passphrase = "38aystr0ngpa55w0rd";
$loginURL = 'https://node1.247cloudhub.co.uk/UserApis/API/UserSettings/Login';
$reLoginURL = 'https://node1.247cloudhub.co.uk/UserApis/API/UserSettings/ReLogin';
$getSkuFromDBURL = 'https://node1.247cloudhub.co.uk/InventoryAPI/Api/Product/GetSkuFromDB';
$getQuantityForSkuURL = 'https://node1.247cloudhub.co.uk/InventoryAPI/api/Quantity/GetQuantityForSku';
$getSkuImagesFromDBURL = 'https://node1.247cloudhub.co.uk/InventoryAPI/api/Product/GetSkuImagesFromDB';

//Magento DB Details
$x247_config = array();
$x247_config['attribute_set_id'] = 4;
$x247_config['product_group_id'] = 7;

//Magento db url,username,password
$url = "http://127.0.0.1/Magento2.3.2/index.php/rest";
$username= "vilask247commerce";
$password= "Vilask*123";

//Magento DB Details

$x247host = "localhost";
$x247dbname = "Magento2.3.2";
$x247user_name = "root";
$x247password = "";
$x247table_name = "m2db_cloudhub_api_log";

$x247conn = mysqli_connect($x247host,$x247user_name,$x247password,$x247dbname);

// Check connection
if (mysqli_connect_errno()){
	echo "Failed to connect to MySQL: " . mysqli_connect_error();exit;
}


if ($result = $x247conn->query("SHOW TABLES LIKE '".$x247table_name."'")) {
	if($result->num_rows == 0){
		$sql = "CREATE TABLE ".$x247table_name." (`id` int(11) NOT NULL,`api_url` varchar(255) DEFAULT NULL,`api_type` enum('philipstoys','cloudhub','','') NOT NULL,`api_request` longtext,
				`api_response` longtext,`created_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,`updated_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,`status` int(2) DEFAULT 0) ENGINE=InnoDB DEFAULT CHARSET=latin1";
		
		if ($x247conn->query($sql) === TRUE) {
			echo "Table ".$x247table_name." created successfully";echo "<br/>";
		}else{
			echo "Table ".$x247table_name." Not Created";exit;
		}
		
		$sql1 = "ALTER TABLE ".$x247table_name." ADD PRIMARY KEY (`id`)";
				
		if ($x247conn->query($sql1) === TRUE) {
			echo "Table ".$x247table_name." primary key added";echo "<br/>";
		}else{
			echo "Primary Key Adding error";exit;
		}
		
		$sql2 = "ALTER TABLE ".$x247table_name." MODIFY `id` int(11) NOT NULL AUTO_INCREMENT";
				
		if ($x247conn->query($sql2) === TRUE) {
			echo "Table ".$x247table_name." created auto increment";echo "<br/>";
		}else{
			echo "Auto increment failed";exit;
		}
		
	}else{
		echo "Table ".$x247table_name." Exists";echo "<br/>";
	}
	//mysqli_close($x247conn);
}

function insert_data($conn,$table_name,$api_url,$api_type,$api_request,$api_response){
	$sql = "INSERT INTO $table_name(api_url,api_type,api_request,api_response,status) values('$api_url','$api_type','$api_request','$api_response','1')";
	echo $sql;
	$conn->query($sql);
	//mysqli_close($conn);
}


?>