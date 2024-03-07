<?php 
    header('Access-Control-Allow-Origin:*');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods:DELETE');
    header('Access-Control-Allow-Headers:Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once('../../config/db.php');
    include_once('../../model/color.php');

    $db = new db();
    $connect = $db->connect();

    $color = new Color($connect);

    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {

        $data = json_decode(file_get_contents("php://input"));
            
        $color->color_id = $data->color_id;

        if($color->delete()) {
            echo json_encode(array(
                "code" => 200,
                "message" => "Xóa màu sắc sản phẩm thành công!!",
            ));
        }else{
            echo json_encode(array("message","Xóa màu sắc sản phẩm thất bại!!"));
        }
    }
?>