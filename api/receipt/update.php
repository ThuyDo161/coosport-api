<?php
header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods:PUT');
header('Access-Control-Allow-Headers:Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once('../../config/db.php');
include_once('../../model/receipt.php');

$db = new db();
$connect = $db->connect();

$receipt = new Receipt($connect);

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {

    $data = json_decode(file_get_contents("php://input"));

    $receipt->receipt_id = $data->receipt_id;
    $receipt->supplier_id = $data->supplier_id;
    $receipt->items = $data->items ? $data->items : null;
    if ($receipt->update()) {
        echo json_encode(array(
            "code" => 200,
            "message" => "Cập nhật hóa đơn nhập thành công!!",
        ));
    } else {
        echo json_encode(array("message", "Cập nhật hóa đơn nhập thất bại!!"));
    }
}
