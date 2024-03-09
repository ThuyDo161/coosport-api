<?php
header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json');

include_once('../../config/db.php');
include_once('../../model/bill.php');

$db = new db();
$connect = $db->connect();

$bill = new Bill($connect);

$read = $bill->readAll();
// dem so dong tra ve
$num = $read->rowCount();

$bill_arr = [];
$bill_arr['bill'] = [];
while ($row = $read->fetch(PDO::FETCH_ASSOC)) {
    extract($row);

    $items = $bill->detail($bill_id);
    $item_arr = [];
    while ($i = $items->fetch(PDO::FETCH_ASSOC)) {
        extract($i);
        $_item = array(
            'id' => $product_id,
            'item_name' => $productname,
            'item_price' => $pricesell,
            'quantity' => $quantity,
            'color' => $colorname,
            'size' => $sizename,
        );
        array_push($item_arr, $_item);
    }

    $bill_item = array(
        'bill_id' => $bill_id,
        'bill_date' => $bill_date,
        'user_id' => $user_id,
        'user_name' => $name,
        'user_tel' => $user_tel,
        'address' => $address,
        'status' => $status_bill,
        'totalprice' => $totalprice,
        'note' => $note,
        'deliverytime' => $deliverytime,
        'modifieddate' => $modifieddate,
        'items' => $item_arr,
    );
    array_push($bill_arr['bill'], $bill_item);
}
echo json_encode($bill_arr);
