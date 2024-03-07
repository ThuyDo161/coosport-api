<?php
    header('Access-Control-Allow-Origin:*');
    header('Content-Type: application/json');

    include_once('../../config/db.php');
    include_once('../../model/policy.php');

    $db = new db();
    $connect = $db->connect();

    $policy = new Policy($connect);

    $read = $policy->read();
    // dem so dong tra ve
    $num = $read->rowCount();

    if($num>0){
        $policy_arr = [];
        $policy_arr['policy'] = [];
        while($row = $read->fetch(PDO::FETCH_ASSOC)){
            extract($row);

            $policy_item = array(
                'policy_id' => $policy_id,
                'name' => $name,
                'description' => $description,
                'icon' => $icon,
                'createddate' => $createddate,
                'modifieddate' => $modifieddate,
            );
            array_push($policy_arr['policy'], $policy_item);
        }
        echo json_encode($policy_arr);
    }
?>