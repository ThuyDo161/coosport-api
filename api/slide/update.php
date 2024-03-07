<?php
header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods:POST');
header('Access-Control-Allow-Headers:Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once('../../config/db.php');
include_once('../../model/slide.php');

$db = new db();
$connect = $db->connect();

$slide = new HeroSlide($connect);

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {

    $data = json_decode(file_get_contents("php://input"));

    $slide->slide_id = htmlspecialchars(strip_tags($data->slide_id));
    $slide->title = htmlspecialchars(strip_tags($data->title));
    $slide->description = htmlspecialchars(strip_tags($data->description));
    $slide->path = htmlspecialchars(strip_tags($data->path));
    $slide->color = htmlspecialchars(strip_tags($data->color));
    $slide->img = htmlspecialchars(strip_tags($data->img));;
    $slide->fileUpload = $data->fileUpload;

    if ($slide->update()) {
        echo json_encode(array(
            "code" => 200,
            "message" 
            => "Cập nhật slide thành công!!",
        ));
    } else {
        echo json_encode(array("message", "Cập nhật slide thất bại, vui lòng thử lại nhập đủ các ô đánh dấu *!!"));
    }
}
