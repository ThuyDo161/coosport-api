<?php
header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json');

include_once('../../config/db.php');
include_once('../../model/user.php');

$db = new db();
$connect = $db->connect();

$users = new Users($connect);

$read = $users->read();
$role = $users->readRole();
// dem so dong tra ve
$num = $read->rowCount();

$users_arr = [];
$users_arr['users'] = [];
$users_arr['roles'] = [];
while ($row = $read->fetch(PDO::FETCH_ASSOC)) {
    extract($row);

    $users_item = array(
        'users_id' => $users_id,
        'name' => $name,
        'address' => $address,
        'user_tel' => $user_tel,
        'role_id' => $role_id,
        'role_name' => $rolename,
        'status' => $status,
        'username' => $username,
        'password' => $password,
        'createddate' => $createddate,
        'modifieddate' => $modifieddate,
    );
    array_push($users_arr['users'], $users_item);
}
while ($row = $role->fetch(PDO::FETCH_ASSOC)) {
    extract($row);

    $roles_item = array(
        'role_id' => $role_id,
        'rolename' => $rolename,
        'createddate' => $createddate,
        'modifieddate' => $modifieddate,
    );
    array_push($users_arr['roles'], $roles_item);
}
echo json_encode($users_arr);
