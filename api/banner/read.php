<?php
header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json');

include_once('../../config/db.php');
include_once('../../model/banners.php');

$db = new db();
$connect = $db->connect();

$banners = new Banner($connect);

$read = $banners->read();
// dem so dong tra ve
$num = $read->rowCount();

$banners_arr = [];
$banners_arr['banners'] = [];
while ($row = $read->fetch(PDO::FETCH_ASSOC)) {
    extract($row);

    $banners_item = array(
        'id' => $id,
        'title' => $title,
        'img' => $img,
        'is_active' => $is_active? true : false,
        'created_date' => $created_date,
        'updated_date' => $updated_date,
    );
    array_push($banners_arr['banners'], $banners_item);
}
echo json_encode($banners_arr);
