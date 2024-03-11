<?php
header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json');

include_once('../../config/db.php');
include_once('../../model/supplier.php');

$db = new db();
$connect = $db->connect();

$supplier = new Supplier($connect);

$read = $supplier->read();
// dem so dong tra ve
$num = $read->rowCount();

$supplier_arr = [];
$supplier_arr['supplier'] = [];
while ($row = $read->fetch(PDO::FETCH_ASSOC)) {
    extract($row);

    $supplier_item = array(
        'supplier_id' => $supplier_id,
        'supplier_name' => $supplier_name,
        'supplier_address' => $supplier_address,
        'supplier_tel' => $supplier_tel,
    );
    array_push($supplier_arr['supplier'], $supplier_item);
}
echo json_encode($supplier_arr);
