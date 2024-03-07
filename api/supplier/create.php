<?php 
    header('Access-Control-Allow-Origin:*');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods:PUT');
    header('Access-Control-Allow-Headers:Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once('../../config/db.php');
    include_once('../../model/supplier.php');

    $db = new db();
    $connect = $db->connect();

    $supplier = new Supplier($connect);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $data = json_decode(file_get_contents("php://input"));
        
        $supplier->supplier_name = $data->supplier_name;
        $supplier->supplier_address = $data->supplier_address;
        $supplier->supplier_tel = $data->supplier_tel;

        if($supplier->create()) {
            echo json_encode(array(
                "code" => 200,
                "message" => "Thêm nhà cung cấp sản phẩm thành công!!",
            ));
        }else{
            echo json_encode(array("message","Thêm nhà cung cấp sản phẩm thất bại!!"));
        }
    }
?>