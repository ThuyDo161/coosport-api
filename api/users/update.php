<?php 
    header('Access-Control-Allow-Origin:*');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods:PUT');
    header('Access-Control-Allow-Headers:Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once('../../config/db.php');
    include_once('../../model/user.php');

    $db = new db();
    $connect = $db->connect();

    $user = new Users($connect);

    if ($_SERVER['REQUEST_METHOD'] === 'PUT') {

        $data = json_decode(file_get_contents("php://input"));
        
        $user->users_id = $data->users_id;
        $user->name = $data->name;
        $user->address = $data->address;
        $user->user_tel = $data->user_tel;
        $user->status = $data->status;
        $user->role_id = $data->role_id;

        if($user->update()) {
            echo json_encode(array(
                "code" => 200,
                "message" => "Chỉnh sửa người dùng thành công!!",
            ));
        }else{
            echo json_encode(array("message","Chỉnh sửa người dùng thất bại!!"));
        }
    }
?>