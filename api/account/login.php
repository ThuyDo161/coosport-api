<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header('Content-Type: application/json');
header('Access-Control-Allow-Headers:Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once('../../config/db.php');
include_once('../../model/account.php');
include_once('../../config/JwtHandler.php');

$db = new db();
$connect = $db->connect();

$user = new Account($connect);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// get posted data
	$data = json_decode(file_get_contents("php://input", true));
	$returnData = [];

	$user->username = $data->username;
	$user->password = $data->password;
	$flgAdm = $data->flgAdm ?? null;

	$result = $user->login($flgAdm);

	if ($result->rowCount() < 1) {
		echo json_encode(array(
			'code' => 201,
			'error' => 'Tài khoản hoặc mật khẩu không chính xác! Vui lòng nhập lại'
		));
	} else {
		$row = $result->fetch(PDO::FETCH_ASSOC);
		$jwt = new JwtHandler();
		$token = $jwt->_jwt_encode_data(
			'http://localhost:8080/php/coosport-api',
			array("user_id" => $row['username'])
		);
		$returnData = [
			'code' => 200,
			'success' => 'Đăng nhập thành công!',
			'token' => $token
		];
		echo json_encode($returnData);
	}
}

//End of file
