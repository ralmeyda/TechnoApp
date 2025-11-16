<?php
// ...

function requireAdmin() {
    if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
        header('Location: ../login.php');
        exit;
    }
}

function getDashboardStats() {
    global $pdo;

    $totalProducts = $pdo->query("SELECT COUNT(*) FROM products WHERE is_active = 1")->fetchColumn();
    $totalUsers    = $pdo->query("SELECT COUNT(*) FROM users WHERE user_type != 'admin'")->fetchColumn();
    $totalOrders   = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
    $totalRevenue  = $pdo->query("SELECT COALESCE(SUM(total_amount),0) FROM orders WHERE status = 'completed'")->fetchColumn();
    $lowStock      = $pdo->query("SELECT COUNT(*) FROM products WHERE stock_quantity <= 5 AND is_active = 1")->fetchColumn();

    return [
        'total_products' => (int)$totalProducts,
        'total_users'    => (int)$totalUsers,
        'total_orders'   => (int)$totalOrders,
        'total_revenue'  => (float)$totalRevenue,
        'low_stock'      => (int)$lowStock,
    ];
}
function getAllProductsAdmin() {
    global $pdo;

    $stmt = $pdo->query("
        SELECT p.product_id, p.product_name, p.price, p.stock_quantity,
       p.is_active, p.image_url, c.category_name
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.category_id
        WHERE p.is_active = 1
        ORDER BY p.product_id DESC
    ");

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
