<?php
header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods:DELETE');
header('Access-Control-Allow-Headers:Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once('../../config/db.php');
include_once('../../model/bill.php');

$db = new db();
$connect = $db->connect();

$bill = new Bill($connect);

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {

    $data = json_decode(file_get_contents("php://input"));

    $bill->bill_id = $data->bill_id;

    if ($bill->delete()) {
        echo json_encode(array(
            "code" => 200,
            "message" => "Xóa hóa đơn sản phẩm thành công!!",
        ));
    } else {
        echo json_encode(array("message", "Xóa hóa đơn sản phẩm thất bại!!"));
    }
}
