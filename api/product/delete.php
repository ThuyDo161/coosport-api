<?php
header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods:DELETE');
header('Access-Control-Allow-Headers:Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once('../../config/db.php');
include_once('../../model/product.php');

$db = new db();
$connect = $db->connect();

$product = new Product($connect);

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {

    $data = json_decode(file_get_contents("php://input"));

    $product->product_id = (int)$data->product_id;

    if ($product->delete()) {
        echo json_encode(array(
            "code" => 200,
            "message" => "Xóa sản phẩm thành công!!",
        ));
    } else {
        echo json_encode(array("message", "Xóa sản phẩm thất bại!!"));
    }
}
