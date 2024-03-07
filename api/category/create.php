<?php 
    header('Access-Control-Allow-Origin:*');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods:PUT');
    header('Access-Control-Allow-Headers:Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once('../../config/db.php');
    include_once('../../model/category.php');

    $db = new db();
    $connect = $db->connect();

    $category = new Category($connect);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $data = json_decode(file_get_contents("php://input"));
        
        $category->categoryname = $data->categoryname;
        $category->category_slug = $data->category_slug;

        if($category->create()) {
            echo json_encode(array(
                "code" => 200,
                "message" => "Thêm loại sản phẩm thành công!!",
            ));
        }else{
            echo json_encode(array("message","Thêm loại sản phẩm thất bại!!"));
        }
    }
?>