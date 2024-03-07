<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header('Content-Type: application/json');
header('Access-Control-Allow-Headers:Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once('../../config/db.php');
include_once('../../model/user.php');

$db = new db();
$connect = $db->connect();

$user = new Users($connect);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // get posted data
    $data = json_decode(file_get_contents("php://input", true));

    $user->username = $data->username;
    $user->password = $data->password;
    $user->name = $data->name;
    $user->address = $data->address;
    $user->user_tel = $data->user_tel;
    $user->role_id = $data->role_id;

    $result = $user->create();

    switch ($result) {
        case 200:
            echo json_encode(array(
                'code' => 200,
                'message' => 'Bạn đã đăng ký thành công'
            ));
            break;
        case 201:
            echo json_encode(array(
                'code' => 201,
                'message' => 'Tên đăng nhập đã tồn tại, vui lòng nhập tên khác!'
            ));
            break;
        case 202:
            echo json_encode(array(
                'code' => 202,
                'message' => 'Vui lòng nhập đủ các ô đánh dấu *!'
            ));
            break;
        default:
            echo json_encode(array(
                'code' => 203,
                'message' => 'Có một số thứ đã sai, bạn vui lòng nhập lại thông tin khác!'
            ));
    }
}

//End of file
