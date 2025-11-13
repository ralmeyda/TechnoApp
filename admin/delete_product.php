<?php
require_once '../config.php';
require_once 'admin_functions.php';

requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = intval($_POST['product_id'] ?? 0);
    
    if ($productId > 0) {
        $result = deleteProduct($productId);
        
        if ($result['success']) {
            header('Location: manage_products.php?deleted=1');
        } else {
            header('Location: manage_products.php?error=delete_failed');
        }
    } else {
        header('Location: manage_products.php?error=invalid_id');
    }
} else {
    header('Location: manage_products.php');
}
exit;
?>
if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']);
    $result = deleteProduct($product_id);

    if ($result['success']) {
        header("Location: manage_products.php?deleted=1");
        exit;
    } else {
        header("Location: manage_products.php?error=1");
        exit;
    }
} else {
    header("Location: manage_products.php");
    exit;
}
?>
