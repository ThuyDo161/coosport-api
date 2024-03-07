<?php 
    header('Access-Control-Allow-Origin:*');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods:PUT');
    header('Access-Control-Allow-Headers:Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once('../../config/db.php');
    include_once('../../model/brand.php');

    $db = new db();
    $connect = $db->connect();

    $brand = new Brand($connect);

    if ($_SERVER['REQUEST_METHOD'] === 'PUT') {

        $data = json_decode(file_get_contents("php://input"));
        
        $brand->brand_id = $data->brand_id;
        $brand->brandname = $data->brandname;
        $brand->brand_slug = $data->brand_slug;

        if($brand->update()) {
            echo json_encode(array(
                "code" => 200,
                "message" => "Chỉnh sửa thương hiệu sản phẩm thành công!!",
            ));
        }else{
            echo json_encode(array("message","Chỉnh sửa thương hiệu sản phẩm thất bại!!"));
        }
    }
?>