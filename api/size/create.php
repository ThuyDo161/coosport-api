<?php 
    header('Access-Control-Allow-Origin:*');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods:PUT');
    header('Access-Control-Allow-Headers:Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once('../../config/db.php');
    include_once('../../model/size.php');

    $db = new db();
    $connect = $db->connect();

    $size = new Size($connect);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $data = json_decode(file_get_contents("php://input"));
        
        $size->sizename = $data->sizename;

        if($size->create()) {
            echo json_encode(array(
                "code" => 200,
                "message" => "Thêm kích thước sản phẩm thành công!!",
            ));
        }else{
            echo json_encode(array("message","Thêm kích thước sản phẩm thất bại!!"));
        }
    }
?>