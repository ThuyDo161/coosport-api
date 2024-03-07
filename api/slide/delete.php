<?php 
    header('Access-Control-Allow-Origin:*');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods:DELETE');
    header('Access-Control-Allow-Headers:Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once('../../config/db.php');
    include_once('../../model/slide.php');

    $db = new db();
    $connect = $db->connect();

    $slide = new HeroSlide($connect);

    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {

        $data = json_decode(file_get_contents("php://input"));
            
        $slide->slide_id = $data->slide_id;

        if($slide->delete()) {
            echo json_encode(array(
                "code" => 200,
                "message" => "Xóa slide thành công!!",
            ));
        }else{
            echo json_encode(array("message","Xóa slide thất bại!!"));
        }
    }
?>