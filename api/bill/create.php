<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header('Content-Type: application/json');
header('Access-Control-Allow-Headers:Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once('../../config/db.php');
include_once('../../model/bill.php');

$db = new db();
$connect = $db->connect();

$bill = new Bill($connect);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// get posted data
	$data = json_decode(file_get_contents("php://input", true));
	if (!is_array($data->items) || count($data->items) <= 0) {
		die();
	}

	$bill->user_id = $data->user_id;
	$bill->location = $data->location;
	$bill->name = $data->name;
	$bill->tel = $data->tel;
	$bill->items = $data->items;
	$result = $bill->create();

	switch ($result) {
		case 200:
			echo json_encode(array(
				'code' => 200,
				'success' => 'Bạn đã đặt hàng thành công!! Cảm ơn bạn đã lựa chọn chúng tôi! <3'
			));
			break;
		default:
			echo json_encode(array(
				'code' => 201,
				'error' => 'Đã xảy ra lỗi đặt hàng, vui lòng thử lại sau :< !!'
			));
			break;
	}
}
