<?php
header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json');

include_once('../../config/db.php');
include_once('../../model/receipt.php');

$db = new db();
$connect = $db->connect();
$year = isset($_GET['year']) ? $_GET['year'] : date("Y");

$receipt = new Receipt($connect);

$read = $receipt->Spending($year);
// dem so dong tra ve
$num = $read->rowCount();

if ($num > 0) {
    while ($row = $read->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $spending = array(
            'Jan' => (float)$Thang1,
            'Feb' => (float)$Thang2,
            'Mar' => (float)$Thang3,
            'Apr' => (float)$Thang4,
            'May' => (float)$Thang5,
            'Jun' => (float)$Thang6,
            'Jul' => (float)$Thang7,
            'Aug' => (float)$Thang8,
            'Sep' => (float)$Thang9,
            'Oct' => (float)$Thang10,
            'Nov' => (float)$Thang11,
            'Dec' => (float)$Thang12,
            "year" => (int)$year
        );
    }
    echo json_encode($spending);
}
