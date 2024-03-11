<?php
header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods:PUT');
header('Access-Control-Allow-Headers:Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once('../../config/db.php');
include_once('../../model/banners.php');

$db = new db();
$connect = $db->connect();

$banners = new Banner($connect);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = json_decode(file_get_contents("php://input"));

    // clean data        
    $banners->id = htmlspecialchars(strip_tags($data->id));
    $banners->title = htmlspecialchars(strip_tags($data->title));
    $banners->img = htmlspecialchars(strip_tags($data->img));
    $banners->is_active = htmlspecialchars(strip_tags($data->is_active));
    $banners->fileUpload = $data->fileUpload ? $data->fileUpload: null;

    if ($banners->create()) {
        echo json_encode(array(
            "code" => 200,
            "message" => "Thêm banner thành công!!",
        ));
    } else {
        echo json_encode(array("message", "Thêm banner thất bại!!"));
    }
}
