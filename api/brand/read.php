<?php
header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json');

include_once('../../config/db.php');
include_once('../../model/brand.php');

$db = new db();
$connect = $db->connect();

$brand = new Brand($connect);

$read = $brand->read();
// dem so dong tra ve
$num = $read->rowCount();

$brand_arr = [];
$brand_arr['brand'] = [];
while ($row = $read->fetch(PDO::FETCH_ASSOC)) {
    extract($row);

    $brand_item = array(
        'brand_id' => $brand_id,
        'brandname' => $brandname,
        'brand_slug' => $brand_slug,
        'createddate' => $createddate,
        'modifieddate' => $modifieddate,
        'product_quantity' => $product_quantity
    );
    array_push($brand_arr['brand'], $brand_item);
}
echo json_encode($brand_arr);
