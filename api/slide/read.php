<?php
    header('Access-Control-Allow-Origin:*');
    header('Content-Type: application/json');

    include_once('../../config/db.php');
    include_once('../../model/slide.php');

    $db = new db();
    $connect = $db->connect();

    $slide = new HeroSlide($connect);

    $read = $slide->read();
    // dem so dong tra ve
    $num = $read->rowCount();

    if($num>0){
        $slide_arr = [];
        $slide_arr['slide'] = [];
        while($row = $read->fetch(PDO::FETCH_ASSOC)){
            extract($row);

            $slide_item = array(
                'slide_id' => $slide_id,
                'title' => $title,
                'description' => $description,
                'img' => $img,
                'color' => $color,
                'path' => $path,
            );
            array_push($slide_arr['slide'], $slide_item);
        }
        echo json_encode($slide_arr);
    }
?>