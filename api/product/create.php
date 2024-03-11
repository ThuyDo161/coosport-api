<?php
header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods:POST');
header('Access-Control-Allow-Headers:Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once('../../config/db.php');
include_once('../../model/product.php');

$db = new db();
$connect = $db->connect();

$product = new Product($connect);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = json_decode(file_get_contents("php://input"));

    $product->productname = htmlspecialchars(strip_tags($data->productname));
    $product->category_id = htmlspecialchars(strip_tags($data->category_id));
    $product->brand_id = htmlspecialchars(strip_tags($data->brand_id));
    $product->pricesell = htmlspecialchars(strip_tags($data->pricesell));
    $product->priceentry = htmlspecialchars(strip_tags($data->priceentry));
    $product->color = htmlspecialchars(strip_tags($data->color));
    $product->size = htmlspecialchars(strip_tags($data->size));
    $product->description = htmlspecialchars(strip_tags($data->description));
    $product->parent_id = htmlspecialchars(strip_tags($data->parent_id));
    $product->product_slug = htmlspecialchars(strip_tags($data->product_slug));
    $product->count = htmlspecialchars(strip_tags($data->count));
    $product->img = $data->img;

    if($product->create()) {
        echo json_encode(array(
            "code" => 200,
            "message" => "Thêm mới sản phẩm thành công!!",
        ));
    }else{
        echo json_encode(array("message","Thêm mới sản phẩm thất bại!!"));
    }
}
