<?php
require_once '../config.php';
require_once 'admin_functions.php';

requireAdmin();

$data = json_decode(file_get_contents('php://input'), true);
$product_id = intval($data['product_id'] ?? 0);
$new_stock = isset($data['stock']) ? intval($data['stock']) : null;

if(!$product_id || $new_stock === null || $new_stock < 0){
    echo json_encode(['success'=>false,'message'=>'Invalid request']);
    exit;
}

$stmt = $pdo->prepare("UPDATE products SET stock_quantity=? WHERE product_id=?");
$stmt->execute([$new_stock, $product_id]);

echo json_encode(['success'=>true,'new_stock'=>$new_stock]);