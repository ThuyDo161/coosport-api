<?php
header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods:DELETE');
header('Access-Control-Allow-Headers:Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once('../../config/db.php');
include_once('../../model/banners.php');

$db = new db();
$connect = $db->connect();

$banners = new Banner($connect);

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {

    $data = json_decode(file_get_contents("php://input"));

    $banners->id = $data->id;

    if ($banners->delete()) {
        echo json_encode(array(
            "code" => 200,
            "message" => "Xóa banner thành công!!",
        ));
    } else {
        echo json_encode(array("message", "Xóa banner thất bại!!"));
    }
}
