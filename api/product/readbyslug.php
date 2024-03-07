<?php
header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json');

include_once('../../config/db.php');
include_once('../../model/product.php');

$db = new db();
$connect = $db->connect();

$product = new Product($connect);
$product->product_slug = isset($_GET['slug']) ? $_GET['slug'] : die();
$product->_limit = isset($_GET['_limit']) ? $_GET['_limit'] : null;
$product->_page = isset($_GET['_page']) ? $_GET['_page'] : null;
$product->costFrom = isset($_GET['_from']) ? $_GET['_from'] : null;
$product->costTo = isset($_GET['_to']) ? $_GET['_to'] : null;
$read = $product->readBySlug();
// dem so dong tra ve
$num = $read->rowCount();

if ($num > 0) {
    $product_arr = [];
    $product_arr['product'] = [];
    isset($_GET['_limit']) && isset($_GET['_page']) ? $product_arr['pages'] = [] : '';
    while ($row = $read->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $colorname_arr = explode(',', $children_color);
        $sizename_arr = explode(',', $children_size);

        array_push($colorname_arr, $colorname);
        array_push($sizename_arr, $sizename);

        // lọc trùng lặp
        $colorname_arr = array_unique($colorname_arr);
        $sizename_arr = array_unique($sizename_arr);

        $product_item = array(
            'product_id' => $product_id,
            'productname' => $productname,
            'pricesell' => $pricesell,
            'priceentry' => $priceentry,
            'count' => $count,
            'description' => $description,
            'categoryname' => $categoryname,
            'brandname' => $brandname,
            'colorname' => $colorname_arr,
            'sizename' => $sizename_arr,
            'img' => explode(',', $img),
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
}
