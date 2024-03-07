<?php 
    header('Access-Control-Allow-Origin:*');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods:DELETE');
    header('Access-Control-Allow-Headers:Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once('../../config/db.php');
    include_once('../../model/category.php');

    $db = new db();
    $connect = $db->connect();

    $category = new Category($connect);

    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {

        $data = json_decode(file_get_contents("php://input"));
            
        $category->category_id = $data->category_id;

        if($category->delete()) {
            echo json_encode(array(
                "code" => 200,
                "message" => "Xóa loại sản phẩm thành công!!",
            ));
        }else{
            echo json_encode(array("message","Xóa loại sản phẩm thất bại!!"));
        }
    }
?>