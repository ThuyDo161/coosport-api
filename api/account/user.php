<?php
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Headers: access");
	header("Access-Control-Allow-Methods: GET");
	header("Content-Type: application/json; charset=UTF-8");
	header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");	

	include_once('../../config/db.php');
	include_once('./Auth.php');

	$db = new db();
    $connect = $db->connect();

	$allHeaders = getallheaders();
	$auth = new Auth($connect,$allHeaders);

	$returnData = [
		"success" => 0,
		"status" => 401,
		"message" => "Unauthorized"
	];
	
	if($auth->isAuth()){
		$returnData = $auth->isAuth();
	}
	
	echo json_encode($returnData);

//End of file
?>