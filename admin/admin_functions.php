<?php
require_once '../config.php';

/**
 * Check if user is admin, redirect if not
 */
function requireAdmin() {
    if (!isAdmin()) {
        header('Location: ../login.php');
        exit;
    }
}

/**
 * Add new product
 */
function addProduct($categoryId, $productName, $description, $price, $stockQuantity, $imageUrl) {
    global $conn;
    
    $stmt = $conn->prepare("INSERT INTO products (category_id, product_name, description, price, stock_quantity, image_url) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issdis", $categoryId, $productName, $description, $price, $stockQuantity, $imageUrl);
    
    if ($stmt->execute()) {
        return ['success' => true, 'message' => 'Product added successfully', 'product_id' => $conn->insert_id];
    } else {
        return ['success' => false, 'message' => 'Failed to add product: ' . $conn->error];
    }
}

/**
 * Update product
 */
function updateProduct($productId, $categoryId, $productName, $description, $price, $stockQuantity, $imageUrl = null) {
    global $conn;
    
    if ($imageUrl) {
        $stmt = $conn->prepare("UPDATE products SET category_id = ?, product_name = ?, description = ?, price = ?, stock_quantity = ?, image_url = ?, updated_at = CURRENT_TIMESTAMP WHERE product_id = ?");
        $stmt->bind_param("issdiis", $categoryId, $productName, $description, $price, $stockQuantity, $imageUrl, $productId);
    } else {
        $stmt = $conn->prepare("UPDATE products SET category_id = ?, product_name = ?, description = ?, price = ?, stock_quantity = ?, updated_at = CURRENT_TIMESTAMP WHERE product_id = ?");
        $stmt->bind_param("issdii", $categoryId, $productName, $description, $price, $stockQuantity, $productId);
    }
    
    if ($stmt->execute()) {
        return ['success' => true, 'message' => 'Product updated successfully'];
    } else {
        return ['success' => false, 'message' => 'Failed to update product: ' . $conn->error];
    }
}

/**
 * Delete product (soft delete - set is_active to FALSE)
 */
function deleteProduct($productId) {
    global $conn;
    
    $stmt = $conn->prepare("UPDATE products SET is_active = FALSE WHERE product_id = ?");
    $stmt->bind_param("i", $productId);
    
    if ($stmt->execute()) {
        return ['success' => true, 'message' => 'Product deleted successfully'];
    } else {
        return ['success' => false, 'message' => 'Failed to delete product: ' . $conn->error];
    }
}
/*
* Restore product
*/
function restoreProduct($productId) {
    global $conn;
    
    $stmt = $conn->prepare("UPDATE products SET is_active = TRUE WHERE product_id = ?");
    $stmt->bind_param("i", $productId);
    
    if ($stmt->execute()) {
        return ['success' => true, 'message' => 'Product restored successfully'];
    } else {
        return ['success' => false, 'message' => 'Failed to restore product: ' . $conn->error];
    }
}
/**
 * Get all products (including inactive for admin)
 */
function getAllProductsAdmin() {
    global $conn;
    
    $sql = "SELECT p.*, c.category_name 
            FROM products p 
            LEFT JOIN categories c ON p.category_id = c.category_id 
            WHERE p.is_active = TRUE
            ORDER BY p.created_at DESC";
    
    $result = $conn->query($sql);
    
    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    
    return $products;
}

/**
 * Handle image upload
 */
function uploadProductImage($file) {
    $targetDir = "uploads/";
    $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
    $maxSize = 5 * 1024 * 1024; // 5MB
    
    // Check if file was uploaded
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'No file uploaded or upload error'];
    }
    
    // Check file size
    if ($file['size'] > $maxSize) {
        return ['success' => false, 'message' => 'File too large. Maximum size is 5MB'];
    }
    
    // Check file type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    if (!in_array($mimeType, $allowedTypes)) {
        return ['success' => false, 'message' => 'Invalid file type. Only JPG, PNG, and GIF allowed'];
    }
    
    // Generate unique filename
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = 'product_' . uniqid() . '_' . time() . '.' . $extension;
    $targetPath = $targetDir . $filename;
    
    // Create uploads directory if it doesn't exist
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }
    
    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        return ['success' => true, 'filepath' => $targetPath];
    } else {
        return ['success' => false, 'message' => 'Failed to save uploaded file'];
    }
}

/**
 * Get dashboard statistics
 */
function getDashboardStats() {
    global $conn;
    
    $stats = [];
    
    // Total products
    $result = $conn->query("SELECT COUNT(*) as count FROM products WHERE is_active = TRUE");
    $stats['total_products'] = $result->fetch_assoc()['count'];
    
    // Total users
    $result = $conn->query("SELECT COUNT(*) as count FROM users WHERE user_type = 'customer'");
    $stats['total_users'] = $result->fetch_assoc()['count'];
    
    // Total orders
    $result = $conn->query("SELECT COUNT(*) as count FROM orders");
    $stats['total_orders'] = $result->fetch_assoc()['count'];
    
    // Total revenue
    $result = $conn->query("SELECT SUM(total_amount) as revenue FROM orders WHERE order_status != 'cancelled'");
    $row = $result->fetch_assoc();
    $stats['total_revenue'] = $row['revenue'] ?? 0;
    
    // Low stock products
    $result = $conn->query("SELECT COUNT(*) as count FROM products WHERE stock_quantity < 5 AND is_active = TRUE");
    $stats['low_stock'] = $result->fetch_assoc()['count'];
    
    return $stats;
}


/* Added to functions.php instead
function clean($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}
*/
?>
