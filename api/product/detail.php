<?php
header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json');

include_once('../../config/db.php');
include_once('../../model/product.php');

$db = new db();
$connect = $db->connect();

$product = new Product($connect);
$product->product_id = isset($_GET['id']) ? $_GET['id'] : die();
$product->_limit = isset($_GET['_limit']) ? $_GET['_limit'] : null;
$product->_page = isset($_GET['_page']) ? $_GET['_page'] : null;
$read = $product->show();
// dem so dong tra ve
$num = $read->rowCount();

$product_arr = [];
$product_arr['product'] = [];
isset($_GET['_limit']) && isset($_GET['_page']) ? $product_arr['pages'] = [] : '';
while ($row = $read->fetch(PDO::FETCH_ASSOC)) {
    extract($row);

    $product_item = array(
        'product_id' => $product_id,
        'productname' => $productname,
        'pricesell' => $pricesell,
        'priceentry' => $priceentry,
        'count' => $count,
        'description' => $description,
        'categoryname' => $categoryname,
        'category_id' => $category_id,
        'brandname' => $brandname,
        'brand_id' => $brand_id,
        'colorname' => $colorname,
        'color_code' => $color_code,
        'color' => $color,
        'sizename' => $sizename,
        'size' => $size,
        'img' => array_filter(explode(',', $img)),
        'parent_id' => $parent_id,
        'product_slug' => $product_slug,
    );
    array_push($product_arr['product'], $product_item);
}
$page_item = array(
    'total' => $product->_total_page,
    'limit' => $product->_limit,
    'page' => $product->_page
);
isset($_GET['_limit']) && isset($_GET['_page']) ? array_push($product_arr['pages'], $page_item) : '';

echo json_encode($product_arr);
