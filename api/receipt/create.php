<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header('Content-Type: application/json');
header('Access-Control-Allow-Headers:Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once('../../config/db.php');
include_once('../../model/receipt.php');

$db = new db();
$connect = $db->connect();

$receipt = new Receipt($connect);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// get posted data
	$data = json_decode(file_get_contents("php://input", true));
	if (!is_array($data->items) || count($data->items) <= 0) {
		die();
	}

	$receipt->user_id = $data->user_id;
	$receipt->supplier_id = $data->supplier_id;
	$receipt->items = $data->items;
	$result = $receipt->create();

	switch ($result) {
		case 200:
			echo json_encode(array(
				'code' => 200,
				'success' => 'Tạo hóa đơn nhập thành công!!'
			));
			break;
		default:
			echo json_encode(array(
				'code' => 201,
				'error' => 'Đã xảy ra lỗi, vui lòng thử lại sau :< !!'
			));
			break;
	}
}
