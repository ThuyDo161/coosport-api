<?php
header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json');

include_once('../../config/db.php');
include_once('../../model/color.php');

$db = new db();
$connect = $db->connect();

$color = new Color($connect);

$read = $color->read();
// dem so dong tra ve
$num = $read->rowCount();

$color_arr = [];
$color_arr['color'] = [];
while ($row = $read->fetch(PDO::FETCH_ASSOC)) {
    extract($row);

    $color_item = array(
        'color_id' => $color_id,
        'colorname' => $colorname,
        'createddate' => $createddate,
        'modifieddate' => $modifieddate,
        'color_code' => $color_code,
        'product_quantity' => $product_quantity,
    );
    array_push($color_arr['color'], $color_item);
}
echo json_encode($color_arr);
