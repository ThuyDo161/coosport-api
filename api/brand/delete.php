<?php 
    header('Access-Control-Allow-Origin:*');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods:DELETE');
    header('Access-Control-Allow-Headers:Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once('../../config/db.php');
    include_once('../../model/brand.php');

    $db = new db();
    $connect = $db->connect();

    $brand = new Brand($connect);

    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {

        $data = json_decode(file_get_contents("php://input"));
            
        $brand->brand_id = $data->brand_id;

        if($brand->delete()) {
            echo json_encode(array(
                "code" => 200,
                "message" => "Xóa thương hiệu sản phẩm thành công!!",
            ));
        }else{
            echo json_encode(array("message","Xóa thương hiệu sản phẩm thất bại!!"));
        }
    }
?>