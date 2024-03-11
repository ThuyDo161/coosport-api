<?php
header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json');

include_once('../../config/db.php');
include_once('../../model/category.php');

$db = new db();
$connect = $db->connect();

$category = new Category($connect);

$read = $category->read();
// dem so dong tra ve
$num = $read->rowCount();

$category_arr = [];
$category_arr['category'] = [];
while ($row = $read->fetch(PDO::FETCH_ASSOC)) {
    extract($row);

    $category_item = array(
        'category_id' => $category_id,
        'categoryname' => $categoryname,
        'category_slug' => $category_slug,
        'createddate' => $createddate,
        'modifieddate' => $modifieddate,
        'product_quantity' => $product_quantity
    );
    array_push($category_arr['category'], $category_item);
}
echo json_encode($category_arr);
