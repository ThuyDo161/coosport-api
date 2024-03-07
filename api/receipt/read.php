<?php
header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json');

include_once('../../config/db.php');
include_once('../../model/receipt.php');

$db = new db();
$connect = $db->connect();

$receipt = new Receipt($connect);

$read = $receipt->read();
// dem so dong tra ve
$num = $read->rowCount();

if ($num > 0) {
    $receipt_arr = [];
    $receipt_arr['receipt'] = [];
    while ($row = $read->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $items = $receipt->detail($receipt_id);
        $item_arr = [];
        while ($i = $items->fetch(PDO::FETCH_ASSOC)) {
            extract($i);
            $_item = array(
                'id' => $product_id,
                'item_name' => $productname,
                'item_price' => $priceentry,
                'quantity' => $quantity,
            );
            array_push($item_arr, $_item);
        }

        $receipt_item = array(
            'receipt_id' => $receipt_id,
            'receipt_date' => $receipt_date,
            'user_id' => $user_id,
            'user_name' => $name,
            'status' => $status,
            'totalprice' => $totalprice,
            'supplier_id' => $supplier_id,
            'supplier_name' => $supplier_name,
            'supplier_address' => $supplier_address,
            'supplier_tel' => $supplier_tel,
            'modifieddate' => $modifieddate,
            'items' => $item_arr,
        );
        array_push($receipt_arr['receipt'], $receipt_item);
    }
    echo json_encode($receipt_arr);
} else {
    $receipt_arr['receipt'] = [];
    echo json_encode($receipt_arr);
}
