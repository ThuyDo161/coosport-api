<?php 
    header('Access-Control-Allow-Origin:*');
    header('Content-Type: application/json');

    include_once('../../config/db.php');
    include_once('../../model/product.php');

    $db = new db();
    $connect = $db->connect();

    $product = new Product($connect);
    $product->MaSanPham = isset($_GET['id']) ? $_GET['id'] : die();
    $product->show();

    $product_item = array(
        'id' => $product->MaSanPham,
        'name' => $product->TenSanPham,
        'MaChuDe' => $product->MaChuDe,
        'TenChuDe' => $product->TenChuDe,
        'MaNXB' => $product->MaNXB,
        'TenNXB' => $product->TenNXB,
        'cost' => $product->DonGiaBan,
        'SoLuong' => $product->SoLuong,
        'img' => $product->Anh,
        'mota' => $product->MoTa,
        'isSach' => $product->isSach,
        'MaNCC' => $product->MaNCC,
        'TenNCC' => $product->TenNCC,
        'category' => $product->TenVanTat,
    );
    echo json_encode($product_item);
?>