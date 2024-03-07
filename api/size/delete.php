<?php 
    header('Access-Control-Allow-Origin:*');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods:DELETE');
    header('Access-Control-Allow-Headers:Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once('../../config/db.php');
    include_once('../../model/size.php');

    $db = new db();
    $connect = $db->connect();

    $size = new Size($connect);

    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {

        $data = json_decode(file_get_contents("php://input"));
            
        $size->size_id = $data->size_id;

        if($size->delete()) {
            echo json_encode(array(
                "code" => 200,
                "message" => "Xóa kích thước sản phẩm thành công!!",
            ));
        }else{
            echo json_encode(array("message","Xóa kích thước sản phẩm thất bại!!"));
        }
    }
?>