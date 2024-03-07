<?php 
    header('Access-Control-Allow-Origin:*');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods:DELETE');
    header('Access-Control-Allow-Headers:Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once('../../config/db.php');
    include_once('../../model/user.php');

    $db = new db();
    $connect = $db->connect();

    $users = new Users($connect);

    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {

        $data = json_decode(file_get_contents("php://input"));
            
        $users->users_id = $data->users_id;

        if($users->resetPassword()) {
            echo json_encode(array(
                "code" => 200,
                "message" => "Reset mật khẩu về mặc định thành công!!",
            ));
        }else{
            echo json_encode(array("message","Reset mật khẩu về mặc định thất bại!!"));
        }
    }
?>