<?php
    header('Access-Control-Allow-Origin:*');
    header('Content-Type: application/json');

    include_once('../../config/db.php');
    include_once('../../model/size.php');

    $db = new db();
    $connect = $db->connect();

    $size = new Size($connect);

    $read = $size->read();
    // dem so dong tra ve
    $num = $read->rowCount();

    if($num>0){
        $size_arr = [];
        $size_arr['size'] = [];
        while($row = $read->fetch(PDO::FETCH_ASSOC)){
            extract($row);

            $size_item = array(
                'size_id' => $size_id,
                'sizename' => $sizename,
                'createddate' => $createddate,
                'modifieddate' => $modifieddate,
                'product_quantity' => $product_quantity,
            );
            array_push($size_arr['size'], $size_item);
        }
        echo json_encode($size_arr);
    }
?>