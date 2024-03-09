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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = json_decode(file_get_contents("php://input"));

    $slide->slide_id = htmlspecialchars(strip_tags($data->slide_id));
    $slide->title = htmlspecialchars(strip_tags($data->title));
    $slide->description = htmlspecialchars(strip_tags($data->description));
    $slide->path = htmlspecialchars(strip_tags($data->path));
    $slide->color = htmlspecialchars(strip_tags($data->color));
    $slide->img = htmlspecialchars(strip_tags($data->img));;
    $slide->fileUpload = isset($_GET['fileUpload']) ? $_GET['fileUpload'] : null;

    if ($slide->create()) {
        echo json_encode(array(
            "code" => 200,
            "message" => "Thêm mới slide thành công!!",
        ));
    } else {
        echo json_encode(array("message", "Thêm mới slide thất bại, vui lòng thử lại nhập đủ các ô đánh dấu *!!"));
    }
}
