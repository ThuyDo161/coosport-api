<?php
header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods:PUT');
header('Access-Control-Allow-Headers:Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once('../../config/db.php');
include_once('../../model/bill.php');

$db = new db();
$connect = $db->connect();

$bill = new Bill($connect);

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {

    $data = json_decode(file_get_contents("php://input"));

    $bill->bill_id = $data->bill_id;
    $bill->deliverytime = $data->deliverytime;
    $bill->status_bill = $data->status;

    if ($bill->update()) {
        echo json_encode(array(
            "code" => 200,
            "message" => "Xác nhận hóa đơn sản phẩm thành công!!",
        ));
    } else {
        echo json_encode(array("message", "Xác nhận hóa đơn sản phẩm thất bại!!"));
    }
}
