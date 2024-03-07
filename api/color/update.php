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

    if ($_SERVER['REQUEST_METHOD'] === 'PUT') {

        $data = json_decode(file_get_contents("php://input"));
        
        $color->color_id = $data->color_id;
        $color->colorname = $data->colorname;
        $color->color_code = $data->color_code;

        if($color->update()) {
            echo json_encode(array(
                "code" => 200,
                "message" => "Chỉnh sửa màu sắc thành công!!",
            ));
        }else{
            echo json_encode(array("message","Chỉnh sửa màu sắc thất bại!!"));
        }
    }
?>