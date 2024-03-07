<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: PUT");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once('../../config/db.php');
include_once('./Auth.php');

$db = new db();
$connect = $db->connect();
$user = new Account($connect);

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $allHeaders = getallheaders();
    $auth = new Auth($connect, $allHeaders);

    $data = json_decode(file_get_contents("php://input", true));

    $name = $data->name;
    $address = $data->address;
    $user_tel = $data->tel;

    $returnData = [
        "success" => 0,
        "code" => 404,
        "error" => "Update thông tin thất bại, vui lòng thử lại!"
    ];

    if ($auth->isAuthUpdate($name, $address, $user_tel)) {
        $returnData = $auth->isAuthUpdate($name, $address, $user_tel);
    }

    echo json_encode($returnData);
}

//End of file
