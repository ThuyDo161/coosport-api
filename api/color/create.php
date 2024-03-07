<?php 
    header('Access-Control-Allow-Origin:*');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods:PUT');
    header('Access-Control-Allow-Headers:Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once('../../config/db.php');
    include_once('../../model/color.php');

    $db = new db();
    $connect = $db->connect();

    $color = new Color($connect);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $data = json_decode(file_get_contents("php://input"));
        
        $color->colorname = $data->colorname;
        $color->color_code = $data->color_code;

        if($color->create()) {
            echo json_encode(array(
                "code" => 200,
                "message" => "Thêm màu sắc sản phẩm thành công!!",
            ));
        }else{
            echo json_encode(array("message","Thêm màu sắc sản phẩm thất bại!!"));
        }
    }
?>